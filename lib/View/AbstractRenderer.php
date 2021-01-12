<?php
namespace Svil\View;

use Svil\View\RendererInterface;
use Svil\View\TemplateNotFoundException;

abstract class AbstractRenderer implements RendererInterface
{

	private $directories = [];

	public function __construct( array $directories = [] )
	{
		$this->directories = $directories;
	}

	public function render(array $templates = [], array $context = [], bool $return = false)
	{
		foreach( $templates as $template ){
			$path = stripslashes($template);
			foreach( $this->directories as $directory ){
				$file = $directory.'/'.$path.'.php';
				if( file_exists( $file ) ){
					if( !empty( $context ) ) extract( $context );
					ob_start();
					include $file;
					$output = ob_get_clean();
					
					if( $return ){
						return $output;
					}
					else {
						echo $output;
						return true;
					}
				}
			}
		}

		throw new TemplateNotFoundException( 'Missing Template ['.implode(',', $templates).'] in '.implode(', ', $this->directories) );
	}
}