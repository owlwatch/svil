<?php
namespace Svil;

if( !function_exists('Svil\\template') ){
	function template( $templates=[], $context=[], $echo=true )
	{
		if( is_string( $templates ) ) {
			$templates = [$templates];
		}
		foreach( $templates as $template ){
			$file = locate_template( 'resources/templates/'.$template.'.php' );
			if( $file ){
				if( !empty( $context ) ) extract( $context );

				ob_start();
				include $file;
				$output = ob_get_clean();
				
				if( $echo ){
					echo $output;
					return;
				}
				else {
					return $output;
				}
			}
		}
		return false;
	}
}