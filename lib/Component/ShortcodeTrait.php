<?php
namespace Svil\Component;

trait ShortcodeTrait
{
	public function initShortcode( $name = '' )
	{
		if( !$name ){
			$this->shortcode = $name ?: $this->getDashedName();
		}
		add_filter('the_content', [$this, 'cleanShortcode']);
		add_filter('acf_the_content', [$this, 'cleanShortcode']);
		add_shortcode($this->shortcode, [$this, 'doShortcode']);
	}

	public function cleanShortcode($content)
	{
		// opening tag
		$content = preg_replace("/(<p>)?\[($this->shortcode)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);

		// closing tag
		$content = preg_replace("/(<p>)?\[\/($this->shortcode)](<\/p>|<br \/>)?/", "[/$2]", $content);

		return $content;
	}

	public function doShortcode($atts = [], string $content = '', string $tag = '')
	{
		$atts = (array)$atts;
		$atts['content'] = $content;
		return $this->render($atts, true);
	}
}