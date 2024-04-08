<?php
function cptui_register_my_cpts()
{

	/**
	 * Post Type: Mẫu thiệp.
	 */

	$labels = [
		"name" => esc_html__("Mẫu thiệp", "custom-post-type-ui"),
		"singular_name" => esc_html__("Mẫu thiệp", "custom-post-type-ui"),
	];

	$args = [
		"label" => esc_html__("Mẫu thiệp", "custom-post-type-ui"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => ["slug" => "inovacard", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "editor", "thumbnail", "author"],
		"taxonomies" => ["category", "post_tag"],
		"show_in_graphql" => false,
	];

	register_post_type("inovacard", $args);

	/**
	 * Post Type: Nội dung thiệp.
	 */

	$labels = [
		"name" => esc_html__("Nội dung thiệp", "custom-post-type-ui"),
		"singular_name" => esc_html__("Nội dung thiệp", "custom-post-type-ui"),
	];

	$args = [
		"label" => esc_html__("Nội dung thiệp", "custom-post-type-ui"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => ["slug" => "component", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"show_in_graphql" => false,
	];

	register_post_type("component", $args);
}

add_action('init', 'cptui_register_my_cpts');

function cptui_register_my_taxes()
{

	/**
	 * Taxonomy: Phân loại element.
	 */

	$labels = [
		"name" => esc_html__("Phân loại element", "custom-post-type-ui"),
		"singular_name" => esc_html__("Phân loại element", "custom-post-type-ui"),
	];


	$args = [
		"label" => esc_html__("Phân loại element", "custom-post-type-ui"),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => ['slug' => 'card_element', 'with_front' => true,],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "card_element",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy("card_element", ["component"], $args);
}
add_action('init', 'cptui_register_my_taxes');
