<?php
/**
 * Plugin Name: Project Management Plugin
 * Description: A Custom Project management plugin
 * Version: 1.0
 * Author: Ryan Hinkle
 * Author URI:
 */

if (! defined('PRODUCT_TEMPLATE_URL') ) {
    define('PRODUCT_TEMPLATE_URL', plugin_dir_path(__FILE__) . 'templates/');
}


//Check if this class name is taken
if (! class_exists('Mu_Products') ) {

	class Mu_Products{
    	// Class Variables
		var meta_template_url;

		/**
		 * initiates the class.
		 *
		 *
		 *
		 * @return
		 */
    	function __construct() {

    		// Declare template url.
    		$this->meta_template_url = PRODUCT_TEMPLATE_URL;

			// Single Hooks and Filters
			$this->hooks_and_filters();
		}

		/**
		 * Add all post type hooks and filters.
		 *
		 *
		 *
		 * @return VOID
		 */
		function hooks_and_filters(){

			// Register Project.
			add_action( 'init', array( $this, 'register_mu_project'));


			// Setup URLS on plugin activation.
			register_activation_hook( __FILE__, array($this, 'clear_permlinks') );

			// Reset URLS on plugin deactivation.
			register_deactivation_hook( __FILE__, array($this, 'clear_permlinks'));

			// Reset URLS on theme switch.
			add_action( 'after_switch_theme', array($this, 'clear_permlinks'));

			// Registering shortcode.
			add_shortcode('list projects','mu_list_projects');

			// Add custom image size support.
			add_image_size( 'project_thumbnail', 360, 267, true);
		}

		/**
		 * Register custom post type
		 *
		 *
		 *
		 * @return VOID
		 */
		function register_mu_project() {

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

		/**
		 * Reset/fix permlinks.
		 *
		 *
		 *
		 * @return VOID
		 */
		function clear_permlinks(){
			flush_rewrite_rules();
		}

		/**
		 * Load in partials
		 *
		 *
		 *
		 * @return VOID.
		 */
		function file_loader($file_name, $exe = ".php"){
			if(file_exists($this->meta_template_url.$file_name.$exe)){
				include($this->meta_template_url.$file_name.$exe);
			}
		}

		/*  ==========================================================================
			Display
		=========================================================================== */

		/**
		 * Display Projects.
		 *
		 * @param  (array) $atts  Mix variables form  shortcode.
		 *
		 * @return HTML  (MIXED)  html content.
		 */
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
								setup_postdata($post);
									$this->file_loader('loop-project');
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
	}
	new Mu_Products();
}
?>