<?php declare( strict_types = 1 );

namespace PiotrPress\Templater;

class Template {
    private string $file;
    private array $context;
    private int $level;

    public function __construct( string $file, array $context = [] ) {
        $this->file = $file;
        $this->context = $context;
    }

    public function __toString() : string {
        if( ! \file_exists( $this->file ) )
            throw new \Exception( 'Template file not found: ' . $this->file );

        if( $this->context ) \extract( $this->context, \EXTR_SKIP );

        $this->level = \ob_get_level();
        if( ! \ob_start( function () {
            return ! (bool)\ini_get( 'display_errors' );
        } ) ) return '';

        \set_error_handler( function ( int $level, string $message, string $file = '', int $line = null ) {
            throw new \ErrorException( $message, 0, $level, $file, $line );
        }, \error_reporting() );

        try {
            include $this->file;
        } catch( \Exception $exception ) {
            while( \ob_get_level() > $this->level ) \ob_end_clean();
            throw $exception;
        }

        \restore_error_handler();

        $output = \ob_get_contents();
        \ob_end_clean();
        return $output ?: '';
    }
}