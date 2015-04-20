<?php
/**
 * Plugin Name: Project Management Plugin
 * Description: A Custom Project management plugin
 * Version: 1.0
 * Author: Ryan Hinkle
 * Author URI:
 */

// Register Custom Post Type
function create_mu_project() {

	$labels = array(
		'name'                => 'Projects',
		'singular_name'       => 'Project',
		'menu_name'           => 'Project',
		'parent_item_colon'   => 'Parent Project:',
		'all_items'           => 'All Projects',
		'view_item'           => 'View Project',
		'add_new_item'        => 'Add New Project',
		'add_new'             => 'Add New Project',
		'edit_item'           => 'Edit Project',
		'update_item'         => 'Update Project',
		'search_items'        => 'Search Project',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash',
	);
	$args = array(
		'label'               => 'mu_project',
		'description'         => 'Project Management ',
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite' 			  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'mu_project', $args );

}

// Hook into the 'init' action
add_action( 'init', 'create_mu_project', 0 );

add_image_size('mu_project_default_thumbnail_size',360,267,true);

/* Flush Rewrite Rules */

//theme switch
function mu_project_flush_rewrite_rules() {
     flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'mu_project_flush_rewrite_rules' );

//plugin
function mu_project_activate() {
	// register taxonomies/post types here
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mu_project_activate' );

function mu_project_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mu_project_deactivate' );
/* Flush Rewrite Rules */

function mu_list_projects($atts){
	 extract( shortcode_atts( array(
	      'layout' => 'grid',
	      'count' => '-1',
	      'order' => 'DESC',
	      'orderby' => 'post_date'
     ), $atts ) );

     $args = array(
		'posts_per_page'   => $count,
		'orderby'          => $orderby,
		'order'            => $order,
		'post_type'        => 'mu_project',
		'post_status'      => 'publish'
		);
     $posts_array = get_posts( $args );
     ob_start();
     ?>
     <div class="wp-list_projects <?php echo $layout; ?>">
	     <div class="inner_wrap">
		     <ul>
				 <?php
				 	global $post;
				 	$count = 1;
				 	foreach($posts_array as $post){
					 setup_postdata($post); ?>
					 <li class="project_item item_num-<?php echo $count; ?>">
					 	 <?php do_action('before_image', get_the_ID()); ?>

						 <?php echo get_the_post_thumbnail(get_the_ID(),apply_filters('mu_project_thumbnail_size','mu_project_default_thumbnail_size')); ?>

						 <div class="caption">
						 	<div class="text"><?php the_title(); ?><?php do_action( 'caption_after_title', get_the_ID()); ?></div>
						 </div>
						 <?php do_action( 'after_caption', get_the_ID()); ?>
					 </li>
					 <?php
					 $count++;
				 	}
				 	wp_reset_postdata();
				 ?>
		     </ul>
	     </div>
     </div>
     <?php
     $html = ob_get_contents();
     ob_end_clean();
     return $html;
}

add_shortcode('list projects','mu_list_projects');

?>