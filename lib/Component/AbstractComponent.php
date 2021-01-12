<?php

namespace Svil\Component;

use Svil\View\RendererInterface;

abstract class AbstractComponent
{

	protected $renderer;

	protected $template = '';

	protected $count = 0;

	public function __construct( RendererInterface $renderer )
	{
		$this->renderer = $renderer;
		$this->setup();
		if (!$this->template) {
			$this->template = $this->getDashedName();
		}
		$this->init();
	}

	public function setup()
	{
		// what does this do
	}

	protected function init()
	{
	}

	protected function getDashedName()
	{
		static $dashed;
		if (!isset($dashed)) {
			$className = end(explode('\\', get_class($this)));
			$dashed = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className));
		}
		return $dashed;
	}

	public function getName()
	{
		return $this->template;
	}

	public function getDefaultConfig()
	{
		return [];
	}

	public function getConfig(array $config = [])
	{
		return shortcode_atts($this->getDefaultConfig(), $config);
	}

	public function getTemplateVariables(array $config = [])
	{
		return $config;
	}

	public function render(array $config = [], $return=false)
	{
		$config = $this->getConfig($config);
		$context = $this->getTemplateVariables($config);
		$context['id'] = $this->template . '-' . ($this->count++);
		return $this->renderer->render(['components/'.$this->template], $context, $return);
	}

	public function capture(array $config = [])
	{
		return $this->render($config, true);
	}
}
