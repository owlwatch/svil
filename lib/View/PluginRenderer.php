<?php
namespace Svil\View;

class PluginRenderer extends AbstractRenderer
{
	public function __construct( $name )
	{
		$directories = [get_stylesheet_directory().'/resources/templates/'.$name];

		if(get_stylesheet_directory() !== get_template_directory()){
			$directories[] = get_template_directory().'/resources/templates/'.$name;
		}

		$directories[] = WP_PLUGIN_DIR.'/'.$name.'/resources/templates';

		parent::__construct($directories);
	}
}