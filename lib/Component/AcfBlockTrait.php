<?php
namespace Svil\Component;

trait AcfBlockTrait
{

	protected $icon = 'screen-options';

	protected $blockCategory = 'widgets';

	protected function initAcfBlock()
	{
		$config = array_merge(
			$this->getDefaultBlockConfig(),
			$this->getBlockConfig()
		);
		acf_register_block_type($config);
	}

	public function enqueueBlockAssets()
	{
		if (!is_admin()) {
			return;
		}
		// wp_enqueue_script(
		//     'block-scripts',
		//     get_stylesheet_directory_uri().'/public/block-scripts.js',
		//     THEME::VERSION
		// );
	}

	public function getDefaultBlockConfig()
	{
		return [
			'name' => $this->template,
			'title' => $this->template,
			'description' => '',
			'icon' => $this->icon,
			'enqueue_assets' => [$this, 'enqueueBlockAssets'],
			'render_callback' => [$this, 'renderBlock'],
			'category' => $this->blockCategory,
		];
	}

	public function getBlockConfig()
	{
		return [];
	}

	public function getBlockVariables($block, $content = '', $is_preview = false, $post_id = 0)
	{
		$fields = get_fields() ?: [];
		return array_merge($block, $fields);
	}

	public function renderBlock($block, $content = '', $is_preview = false, $post_id = 0)
	{
		$vars = $this->getBlockVariables($block, $content, $is_preview, $post_id);
		return $this->render($vars);
	}
}