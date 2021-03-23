<?php declare(strict_types=1);

namespace PiotrPress;

use const EXTR_SKIP;

use Exception;
use ErrorException;

use function is_file;
use function rtrim;
use function extract;
use function ini_get;
use function ob_get_level;
use function ob_start;
use function ob_get_contents;
use function ob_end_clean;

class Templater {
    protected string $directory = '';
    protected string $extension = '.php';

    public function __construct( string $directory = '', string $extension = '.php' ) {
        $this->directory = $directory;
        $this->extension = $extension;
    }

    protected function find( $file ) : string {
        if ( is_file( $path = ( $this->directory ? rtrim( $this->directory, '/' ) . '/' : '' ) . $file ) ) return $path;
        elseif ( is_file( $path .= $this->extension ) ) return $path;

        return '';
    }

    public function render( string $file, array $context = [] ) : string {
        if ( ! $path = $this->find( $file ) ) return '';
        if ( $context ) extract( $context, EXTR_SKIP );

        $level = ob_get_level();
        if ( ! ob_start( function () {
            return ! (bool)ini_get( 'display_errors' );
        } ) ) return '';

        set_error_handler( function ( int $errno, string $errstr, string $errfile = '', int $errline = null ) {
            throw new ErrorException( $errstr, 0, $errno, $errfile, $errline );
        }, error_reporting() );

        try {
            include $path;
        } catch ( Exception $exception ) {
            while ( ob_get_level() > $level ) ob_end_clean();
            throw $exception;
        }

        restore_error_handler();

        $output = ob_get_contents();
        ob_end_clean();
        return $output ?: '';
    }

    public function display( string $file, array $context = [] ) : void {
        echo $this->render( $file, $context );
    }
}