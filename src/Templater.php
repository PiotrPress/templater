<?php declare( strict_types = 1 );

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
use function error_reporting;
use function set_error_handler;
use function restore_error_handler;

class Templater implements TemplaterInterface {
    protected string $directory = '';
    protected string $extension = '.php';

    public function __construct( string $directory = '', string $extension = '.php' ) {
        $this->directory = $directory;
        $this->extension = $extension;
    }

    protected function find( string $template ) : string {
        if ( is_file( $file = ( $this->directory ? rtrim( $this->directory, '/' ) . '/' : '' ) . $template ) ) return $file;
        elseif ( is_file( $file .= $this->extension ) ) return $file;

        return '';
    }

    public function render( string $template, array $context = [] ) : string {
        if ( ! $file = $this->find( $template ) ) return '';
        if ( $context ) extract( $context, EXTR_SKIP );

        $level = ob_get_level();
        if ( ! ob_start( function () {
            return ! (bool)ini_get( 'display_errors' );
        } ) ) return '';

        set_error_handler( function ( int $level, string $message, string $file = '', int $line = null ) {
            throw new ErrorException( $message, 0, $level, $file, $line );
        }, error_reporting() );

        try {
            include $file;
        } catch ( Exception $exception ) {
            while ( ob_get_level() > $level ) ob_end_clean();
            throw $exception;
        }

        restore_error_handler();

        $output = ob_get_contents();
        ob_end_clean();
        return $output ?: '';
    }

    public function display( string $template, array $context = [] ) : void {
        echo $this->render( $template, $context );
    }
}