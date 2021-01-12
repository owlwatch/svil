<?php
namespace Svil\View;

use Svil\View\AbstractRenderer;

class ThemeRenderer extends AbstractRenderer
{
	public function __construct()
	{
		$directories = [get_stylesheet_directory().'/resources/templates'];

		if(get_stylesheet_directory() !== get_template_directory()){
			$directories[] = get_template_directory().'/resources/templates';
		}

		parent::__construct($directories);
	}
}