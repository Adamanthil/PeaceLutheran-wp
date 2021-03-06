<?php
	// enables wigitized sidebars
	if ( function_exists('register_sidebar') )

	// Sidebar Widget
	// Location: the sidebar
	register_sidebar(array('name'=>'Sidebar',
		'before_widget' => '<div class="widget-area widget-sidebar"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	// Header Widget
	// Location: right after the navigation
	register_sidebar(array('name'=>'Header',
		'before_widget' => '<div class="widget-area widget-header"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	// Footer Widget
	// Location: at the top of the footer, above the copyright
	register_sidebar(array('name'=>'Footer',
		'before_widget' => '<div class="widget-area widget-footer"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	// The Alert Widget
	// Location: displayed on the top of the home page, right after the header, right before the loop, within the content area
	register_sidebar(array('name'=>'Alert',
		'before_widget' => '<div class="widget-area widget-alert"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));

	// post thumbnail support
	add_theme_support( 'post-thumbnails' );

	// custom menu support
	add_theme_support( 'menus' );
	if ( function_exists( 'register_nav_menus' ) ) {
	  	register_nav_menus(
	  		array(
	  		  'header-menu' => 'Header Menu',
	  		  'sidebar-menu' => 'Sidebar Menu',
	  		  'footer-menu' => 'Footer Menu',
	  		  'logged-in-menu' => 'Logged In Menu'
	  		)
	  	);
	}

	// custom background support
	add_custom_background();

	// custom header image support
	define('NO_HEADER_TEXT', true );
	define('HEADER_IMAGE', '%s/images/default-header.png'); // %s is the template dir uri
	define('HEADER_IMAGE_WIDTH', 1068); // use width and height appropriate for your theme
	define('HEADER_IMAGE_HEIGHT', 300);
	// gets included in the admin header
	function admin_header_style() {
	    ?><style type="text/css">
	        #headimg {
	            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	        }
	    </style><?php
	}
	add_custom_image_header( '', 'admin_header_style' );

	// adds Post Format support
	// learn more: http://codex.wordpress.org/Post_Formats
	add_theme_support( 'post-formats', array( 'aside', 'gallery','link','image','quote','status','video','audio','chat' ) );

	// removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;"));
	
	// Removes Trackbacks from the comment cout
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}

	// invite rss subscribers to comment
	function rss_comment_footer($content) {
		if (is_feed()) {
			if (comments_open()) {
				$content .= 'Comments are open! <a href="'.get_permalink().'">Add yours!</a>';
			}
		}
		return $content;
	}

	// custom excerpt ellipses for 2.9+
	function custom_excerpt_more($more) {
		return 'Read More &raquo;';
	}
	add_filter('excerpt_more', 'custom_excerpt_more');
	// no more jumping for read more link
	function no_more_jumping($post) {
		return '<a href="'.get_permalink($post->ID).'" class="read-more">'.'&nbsp; Continue Reading &raquo;'.'</a>';
	}
	add_filter('excerpt_more', 'no_more_jumping');
	
	// category id in body and post class
	function category_id_class($classes) {
		global $post;
		foreach((get_the_category($post->ID)) as $category)
			$classes [] = 'cat-' . $category->cat_ID . '-id';
			return $classes;
	}
	add_filter('post_class', 'category_id_class');
	add_filter('body_class', 'category_id_class');
	
	// Custom Post Types
	add_action( 'init', 'create_post_types' );
	function create_post_types() {
		register_post_type( 'peace_accents',
			array(
				'labels' => array(
					'name' => __( 'Academy Accents' ),
					'singular_name' => __( 'Academy Accents' )
				),
			'public' => true,
			'rewrite' => array(
						'slug' => 'accents',
						'with_front' => false
						),
			'has_archive' => true,
			'capability_type' => array('accents', 'accents')
			)
		);
		register_post_type( 'peace_news',
			array(
				'labels' => array(
					'name' => __( 'News' ),
					'singular_name' => __( 'News Story' )
				),
			'public' => true,
			'rewrite' => array(
						'slug' => 'news',
						'with_front' => false
						),
			'has_archive' => true,
			'capability_type' => array('news', 'news')
			)
		);
	register_post_type( 'peace_featuredquote',
		array(
			'labels' => array(
				'name' => __( 'Timeless Quote' ),
				'singular_name' => __( 'Timeless Quote' )
			),
		'public' => true,
		'rewrite' => array(
					'slug' => 'featured-quote',
					'with_front' => false
					),
		'supports' => array('custom-fields'),
		'has_archive' => false,
		'capability_type' => array('featuredquote', 'featuredquote')
		)
	);
    register_post_type( 'peace_handbook',
    	array(
    		'labels' => array(
    			'name' => __( 'Handbook' ),
    			'singular_name' => __( 'Handbook' )
    		),
    	'public' => true,
    	'has_archive' => false,
		'capability_type' => array('handbook', 'handbook')
    	)
    );
    register_post_type( 'peace_enrollform',
    	array(
    		'labels' => array(
    			'name' => __( 'Enrollment Forms' ),
    			'singular_name' => __( 'Enrollment Form' )
    		),
    	'public' => true,
    	'has_archive' => false,
		'capability_type' => array('enrollform', 'enrollform')
    	)
    );
	register_post_type( 'peace_resource',
		array(
			'labels' => array(
				'name' => __( 'Resources' ),
				'singular_name' => __( 'Resource' )
			),
		'public' => true,
		'rewrite' => array(
					'slug' => 'downloadable-resource',
					'with_front' => false
					),
		'has_archive' => false,
		'capability_type' => array('resource', 'resource')
		)
	);
	}
	
	// Remove the powerpress podcast fields from all but sermon posts
	add_action('admin_menu', 'remove_post_podcast_fields');
	function remove_post_podcast_fields() {
		remove_meta_box('powerpress-podcast', 'post', 'normal' );
		remove_meta_box('powerpress-podcast', 'peace_accents', 'normal' );
		remove_meta_box('powerpress-podcast', 'peace_featuredquote', 'normal' );
		remove_meta_box('powerpress-podcast', 'peace_news', 'normal' );
		remove_meta_box('powerpress-podcast', 'peace_handbook', 'normal' );
		remove_meta_box('powerpress-podcast', 'peace_enrollform', 'normal' );
	}
	
	// add_action( 'admin_init', 'theme_options_init' );
	// add_action( 'admin_menu', 'theme_options_add_page' );
	
	// Init plugin options to white list our options
	// function theme_options_init(){
	// 	register_setting( 'tat_options', 'tat_theme_options', 'theme_options_validate' );
	// }
	
	// Load up the menu page
	// function theme_options_add_page() {
	// 	add_theme_page( __( 'Theme Options', 'tat_theme' ), __( 'Theme Options', 'tat_theme' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
	// }
?>