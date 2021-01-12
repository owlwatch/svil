<?php
namespace Svil\View;

interface RendererInterface
{
	/**
	 * Render a template
	 *
	 * @param array $templates A list of possible templates
	 * @param array $context An associative array of variables to be used in the template
	 * @param boolean $return Flag to output or return the rendered template
	 * 
	 * @throws Svil\View\TemplateNotFoundException;
	 * 
	 * @return mixed
	 */
	public function render( 
		array $templates = [], 
		array $context = [],
		bool $return = false
	);
}