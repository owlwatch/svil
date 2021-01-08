<?php

namespace Svil\Taxonomy;

use Svil\Util\Inflect;
use Svil\Taxonomy\MissingTaxonomyException;

abstract class AbstractTaxonomy
{
	const TAXONOMY = self::TAXONOMY;

	protected $name;

	public function __construct()
	{
		$this->setup();
		if (!defined('static::TAXONOMY')) {
			throw new MissingTaxonomyException();
		}
		add_action('init', [$this, 'register'], 2);
		$this->init();
	}

	public function setup()
	{
		// set name property for shortcutting labels
	}

	protected function init()
	{
	}

	public function register()
	{
		$defaults = $this->getDefaultArguments();
		$args = $this->getArguments();

		if (isset($args['labels'])) {
			$args['labels'] = array_merge($defaults['labels'], $args['labels']);
		}

		$arguments = array_merge($defaults, $args);
		register_taxonomy(static::TAXONOMY, $this->getPostTypes(), $arguments);
	}

	protected function getDefaultArguments()
	{
		$Plural = $this->name;
		$Singular = Inflect::singularize($Plural);
		$plural = lcfirst($Plural);
		$singular = lcfirst($Singular);

		$labels = array(
			'name'                       => _x($Plural, 'taxonomy general name', 'nici-theme'),
			'singular_name'              => _x($Singular, 'taxonomy singular name', 'nici-theme'),
			'search_items'               => __('Search ' . $Plural, 'nici-theme'),
			'popular_items'              => __('Popular ' . $Plural, 'nici-theme'),
			'all_items'                  => __('All ' . $Plural, 'nici-theme'),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __('Edit ' . $Singular, 'nici-theme'),
			'update_item'                => __('Update ' . $Singular, 'nici-theme'),
			'add_new_item'               => __('Add New ' . $Singular, 'nici-theme'),
			'new_item_name'              => __('New ' . $Singular . ' Name', 'nici-theme'),
			'separate_items_with_commas' => __('Separate ' . $plural . ' with commas', 'nici-theme'),
			'add_or_remove_items'        => __('Add or remove ' . $plural, 'nici-theme'),
			'choose_from_most_used'      => __('Choose from the most used ' . $plural, 'nici-theme'),
			'not_found'                  => __('No ' . $plural . ' found.', 'nici-theme'),
			'menu_name'                  => __($Plural, 'nici-theme'),
		);

		$args = [
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => ['slug' => static::TAXONOMY],
		];

		return $args;
	}

	/**
	 * Get the Post Type arguments
	 *
	 * @return Array ary of post type arguments
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the related post types
	 *
	 * @return Array array of post type names
	 */
	protected function getPostTypes()
	{
		return [];
	}
}
