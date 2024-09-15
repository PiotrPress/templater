<?php declare( strict_types = 1 );

namespace PiotrPress;

use PiotrPress\Templater\Template;

class Templater implements TemplaterInterface {
    protected string $directory = '';
    protected string $extension = '.php';

    public function __construct( string $directory = '', string $extension = '.php' ) {
        $this->directory = $directory;
        $this->extension = $extension;
    }

    protected function find( string $template ) : string {
        if( \is_file( $file = ( $this->directory ? \rtrim( $this->directory, '/' ) . '/' : '' ) . $template ) ) return $file;
        elseif( \is_file( $file .= $this->extension ) ) return $file;
        return '';
    }

    public function render( string $template, array $context = [] ) : string {
        return (string)( new Template( $this->find( $template ), $context ) );
    }

    public function display( string $template, array $context = [] ) : void {
        echo $this->render( $template, $context );
    }
}