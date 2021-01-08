<?php

namespace Svil\PostType;

use Svil\Util\Inflect;
use Svil\PostType\PostTypeException;

/**
 * 
 */
abstract class AbstractPostType
{

    const POST_TYPE = self::POST_TYPE;


    protected $name = '';

	public function __construct()
	{
        $this->setup();
		if( !defined( 'static::POST_TYPE' ) ){
			throw new PostTypeException( PostTypeException::MISSING_POST_TYPE );
		}
		add_action( 'init', [$this, 'register'], 3 );
		$this->init();
    }
    
    protected function setup(){}

	protected function init(){}

	public function register()
	{
		$defaults = $this->getDefaultArguments();
		$args = $this->getArguments();

		if( isset( $args['labels'] ) ){
			$args['labels'] = array_merge( $defaults['labels'], $args['labels'] );
		}

		$arguments = array_merge( $defaults, $args );

		register_post_type( static::POST_TYPE, $arguments );

	}

	protected function getDefaultArguments()
	{
		$Plural = $this->name;
		$Singular = Inflect::singularize( $Plural );
		$plural = lcfirst( $Plural );
		$singular = lcfirst( $Singular );


		$labels = array(
			'name'               => _x( $Plural, 'post type general name', 'nici-theme' ),
			'singular_name'      => _x( $Singular, 'post type singular name', 'nici-theme' ),
			'menu_name'          => _x( $Plural, 'admin menu', 'nici-theme' ),
			'name_admin_bar'     => _x( $Singular, 'add new on admin bar', 'nici-theme' ),
			'add_new'            => _x( 'Add New', 'book', 'nici-theme' ),
			'add_new_item'       => __( 'Add New '.$Singular, 'nici-theme' ),
			'new_item'           => __( 'New '.$Singular, 'nici-theme' ),
			'edit_item'          => __( 'Edit '.$Singular, 'nici-theme' ),
			'view_item'          => __( 'View '.$Singular, 'nici-theme' ),
			'all_items'          => __( 'All '.$Plural, 'nici-theme' ),
			'search_items'       => __( 'Search '.$Plural, 'nici-theme' ),
			'parent_item_colon'  => __( 'Parent '.$Singular.':', 'nici-theme' ),
			'not_found'          => __( 'No '.$plural.' found.', 'nici-theme' ),
			'not_found_in_trash' => __( 'No '.$plural.' found in Trash.', 'nici-theme' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( $Plural, 'nici-theme' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => static::POST_TYPE ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [
				'title', 'editor', 'author', 'thumbnail', 'excerpt'
			]
		);

		return $args;
	}

	/**
	 * Get the Post Type arguments
	 *
	 * @return Array ary of post type arguments
	 */
	protected function getArguments(){
		return [];
	}
}
