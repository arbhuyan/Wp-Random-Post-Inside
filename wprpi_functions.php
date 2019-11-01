<?php
/**
*	Process related post data
*/
/* wprpi version */
if( !function_exists('wprpi_info') ) {
	function wprpi_info() {
		$wprpi_opt 		= get_option( 'wprpi_version' );
		$wprpi_version	= WPRPI_VERSION;

		// update necessary information
		if( ! $wprpi_opt || $wprpi_opt != $wprpi_version ) {
			update_option('wprpi_version', $wprpi_version);
		}

		delete_option( 'wprpi_version' );
	}
	add_action( 'wp_loaded', 'wprpi_info' );
}

/* wprpi notice manager */
if( !function_exists('wprpi_admin_notice') ) {
	function wprpi_admin_notice(){
		global $pagenow;

		$class = 'notice notice-success is-dismissible wprpi-notice';
		$message = __( 'Plugin updated successfully. Please check %s', 'wp-random-post-inside' );

		if( $pagenow == "index.php" && false == get_option('wprpi_notice_dismiss') ) {
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), sprintf( $message, '<strong><a href="'.admin_url('options-general.php?page=wp-random-post-inside/wprpi_settings.php').'">our new options</a></strong>' ) );
		}
	}
	add_action( 'admin_notices', 'wprpi_admin_notice' );
}

if( !function_exists('wprpi_admin_notice_hide') ) {
	function wprpi_admin_notice_hide() {
		if( isset($_GET['wprpi_notice_dismiss']) && 1 == $_GET['wprpi_notice_dismiss'] ) {
			update_option('wprpi_notice_dismiss', 1);
		}
	}
	add_action('admin_init', 'wprpi_admin_notice_hide');
}

/* related posts icon/color settings */
if( !function_exists('wprpi_related_color_settings') ) {
	function wprpi_related_color_settings(){
		$related_link_color 	= esc_attr( get_option( 'wprpi_link_color' ) );
		$related_hover_color 	= esc_attr( get_option( 'wprpi_hover_color' ) );
		$related_bg_color 		= esc_attr( get_option( 'wprpi_bg_color' ) );
		$related_title_color 	= esc_attr( get_option( 'wprpi_title_color' ) );

		// link color settings
		if( $related_link_color || $related_hover_color ){
			echo'<style type="text/css">';
			if(isset($related_link_color)){
			echo'
				.wp_random_inside span, .wp_random_inside a {
					color: '.$related_link_color.' !important;
				}';
			}
			if(isset($related_hover_color)){
			echo'
				.wp_random_inside a:hover {
					color: '.$related_hover_color.' !important;
				}';
			}
			echo'</style>';
		}

		/* background settings */
		if($related_bg_color != "transparent"){
			echo'<style type="text/css">
				.wprpi_post_box {
				    background: '.$related_bg_color.';
					padding: 7px;
					border-radius: 3px;
				}
				.wprpi_title {
					border-bottom: 1px solid;
				}
			</style>';
		}

		/* title color settings */
		if($related_title_color){
			echo'<style type="text/css">
				.wprpi_title {
				    color: '.$related_title_color.';
					margin: 5px;
					padding-bottom: 5px;
				}
			</style>';
		}
	}
	add_action('wp_head', 'wprpi_related_color_settings');
}

/* get related data */
if( !function_exists('wprpi_get_related_data') ) {
	function wprpi_get_related_data( $post_id ){
		/* Get total random post */
		$random_show = 2;

		/* Get Settings Informations */
		$related_by_cat 	= esc_attr( get_option( 'wprpi_related_by_cat' ) );
		$related_by_tag 	= esc_attr( get_option( 'wprpi_related_by_tag' ) );

		/* finding related post */
		$arg = array(
			'numberposts'	=> $random_show,
			'post__not_in'	=> array( $post_id ),
			'orderby'		=> 'rand',
			'post_status'	=> 'publish'
		);

		// if category is set
		if( $related_by_cat ){
			$arg['category__in'] = wp_get_post_categories( $post_id );
		}

		// if tag is set
		if( $related_by_tag ){
			$tags = wp_get_post_tags( $post_id );

		    if( $tags ) {
			    foreach( $tags as $tag ) {
			        $tag_arr [] = $tag->slug . ',';
			    }

			    // include tag to find related post
			    $arg['tag'] = $tag_arr;
			}
		}

		/* get related data */
		$related = get_posts( $arg );

		if( $related ){
		
			foreach ( $related as $post ) {
				$related_id[] = $post->ID;
			}

			// reset post data
			wp_reset_postdata();
			
			// return related post id
			return $related_id;
		} else{
			// otherwise false
			return false;
		}
	}
}

/* filter content data */
if( !function_exists('wprpi_related_content') ) {
	function wprpi_related_content( $content ) {

		// supported post types
		$supported_post_options = get_option( 'wprpi_supported_cpt' );

		if( false === $supported_post_options ) {
			$supported_post_types = array('post');
		} else {
			$supported_post_types = ( is_array( get_option( 'wprpi_supported_cpt' ) ) ) ? get_option( 'wprpi_supported_cpt' ) : array();
		}
		
		if( ! in_array( get_post_type( ), $supported_post_types ) ) return $content;

		/* Get total random post */
		$random_show = 2;

		/* General settings */
		$related_link_icon 		= esc_attr( get_option( 'wprpi_show_icon' ) );
		$related_icon 			= esc_attr( get_option( 'wprpi_icon' ) );

		// icon settings
		if( $related_link_icon ){
			$icon_val = '<span class="dashicons dashicons-'.$related_icon.'"></span>';
		} else {
			$icon_val = '';
		}

		/* remove paragraph if empty */
		$content = str_replace( '<p>&nbsp;</p>', '', $content );

		$shortcode_exist = strpos( $content, '[wprpi' );

		if( is_singular() && is_main_query() && !$shortcode_exist ) {
			
			/* related data */
			$related = wprpi_get_related_data( get_the_ID() );

			/* getting paragraph */
			$paragraph 	= explode( '</p>',$content );
			$count_para = count( $paragraph );

			if( $count_para > $random_show ){

				/* finding middle point of content */
				$slice_para = ceil( $count_para/$random_show );
			    
				/* extending content */
				$content = implode( '</p>', array_splice($paragraph, 0, $slice_para) );
				if( $related ){
					$content .= '<div class="wp_random_inside">'.$icon_val.' <a href="'.esc_url(get_page_link( $related[0] )).'">'.get_the_title( $related[0] ).'</a></div>';
				}
				
				$content .= implode( '</p>', $paragraph );
				if( count($related) > 1 ){
					$content .= '<div class="wp_random_inside">'.$icon_val.' <a href="'.esc_url(get_page_link( $related[1] )).'">'.get_the_title( $related[1] ).'</a></div>';
				}
				
			} else {
				
				/* content total word count */
				$word_count = str_word_count( $content );
				$slice_para = ceil( $word_count/$random_show );

				/* getting content */
				$content_main = explode( ' ',$content );

				/* extending content */
				$content = implode( ' ', array_splice($content_main, 0, $slice_para) );
				
				if( $related ){
					$content .= '<div class="wp_random_inside">'.$icon_val.' <a href="'.esc_url(get_page_link( $related[0] )).'">'.get_the_title( $related[0] ).'</a></div><p>';
				}
				
				$content .= implode( ' ', $content_main );
				
				if( count($related) > 1 ){
					$content .= '<div class="wp_random_inside">'.$icon_val.' <a href="'.esc_url(get_page_link( $related[1] )).'">'.get_the_title( $related[1] ).'</a></div><p>';
				}
			}

		}
		
		/* clear blank paragraphs */
		$content = str_replace( '<p> </p>', '', $content );

		/* return content */
		return $content;
	}
	add_filter( 'the_content', 'wprpi_related_content' );
}

/**
*	Shortcode Support for wp-random-post-inside plugin
*	
*	[wprpi by="category" post="5" title="Related Post" thumbnail="true" excerpt="true" icon="show" cat_id="1,2,3" post_id="1,2,3" tag_slug="hello,world"]
*/

if( !function_exists('wprpi_short_code_func') ) {
	function wprpi_short_code_func( $attr, $content = null ) {
		$wprpi_default = array(
			'title' 	=> '',
			'by' 		=> '',
			'post' 		=> 1,
			'icon'		=> '',
			'cat_id'	=> '',
			'tag_slug'	=> '',
			'post_id'	=> '',
			'thumb_excerpt' => false,
			'excerpt_length'=> 55
		);

		// get the parameters value
		$wprpi_value = shortcode_atts( $wprpi_default, $attr );

		//general settings
		$related_link_icon 		= esc_attr( get_option( 'wprpi_show_icon' ) );
		$related_icon 			= esc_attr( get_option( 'wprpi_icon' ) );
		$related_bg_color 		= esc_attr( get_option( 'wprpi_bg_color' ) );
		$related_title_color 	= esc_attr( get_option( 'wprpi_title_color' ) );

		// get icon
		if(strcmp($wprpi_value['icon'], "show") == 0){
			$icon_val = '<span class="dashicons dashicons-'.$related_icon.'"></span>';
		} elseif(strcmp($wprpi_value['icon'], "none") == 0){
			$icon_val = '';
		} elseif($related_link_icon) {
			$icon_val = '<span class="dashicons dashicons-'.$related_icon.'"></span>';
		} else {
			$icon_val = '';
		}

		/* finding related post parameters */
		$wprpi_arg = array(
			'numberposts'	=> $wprpi_value['post'],
			'post__not_in'	=> array(get_the_ID()),
			'orderby'		=> 'rand',
			'post_status'	=> 'publish',
		);

		/* Validation of related posts by */
		if($wprpi_value['by'] == "tag"){
			// find post tags
			$tags = wp_get_post_tags( get_the_ID() );

		    if($tags) {
			    foreach( $tags as $tag ) {
			        $tag_arr []= $tag->slug . ',';
			    }

			    // include tag to find related post
			    $wprpi_arg['tag'] = $tag_arr;
			}
		} elseif($wprpi_value['by'] == "category") {
			
			// add argument by category
			$wprpi_arg['category__in']	= wp_get_post_categories( get_the_ID() );
			
		} elseif($wprpi_value['by'] == "both"){
			// find post tags
			$tags = wp_get_post_tags( get_the_ID() );

		    if($tags) {
			    foreach( $tags as $tag ) {
			        $tag_arr []= $tag->slug . ',';
			    }

			    // include tag to find related post
			    $wprpi_arg['tag'] = $tag_arr;
			}

			// get related post by category
			$wprpi_arg['category__in']	= wp_get_post_categories( get_the_ID() );

		}

		/* Add new array to the argument by category id and tags */
		if($wprpi_value['post_id'] != ""){
			// add new array to argument
			$wprpi_arg['post__in'] = explode(',', $wprpi_value['post_id']);
		}

		if($wprpi_value['cat_id'] != ""){
			// add new array to argument
			$wprpi_arg['cat'] = $wprpi_value['cat_id'];
		}

		if($wprpi_value['tag_slug'] != ""){
			// add new array to argument
			$wprpi_arg['tag'] = explode(',', $wprpi_value['tag_slug']);
		}

		/* get related data */
		$related = get_posts($wprpi_arg);

		// get related values
		if($related){
			$wprpi_val = '<div class="wprpi_post_box">';	
		} else {
			$wprpi_val = '<div class="wprpi_posts">';
		}
		
		if($wprpi_value['title']){		
			$wprpi_val .= '<span class="wprpi_title">'.$wprpi_value['title'].'</span>';
		}
		
		foreach ($related as $post) {
			// display random posts
			$wprpi_val .= '<div class="wp_random_inside">';
			if( $wprpi_value['thumb_excerpt'] ){
				if(has_post_thumbnail($post->ID)){
					$thumbnail = '<div class="wprpi-thumbnail">'.get_the_post_thumbnail($post->ID).'</div>';
					$thumbexcerpt = ' thumbexcerpt-true';
				} else {
					$thumbexcerpt = $thumbnail = '';
				}

				$wprpi_val .= '<div class="wprpi-content'.$thumbexcerpt.'">';
				$wprpi_val .= '<h4 class="wprpi-post-title">
									'.$icon_val.' 
									<a href="'.esc_url(get_page_link( $post->ID )).'">'.get_the_title( $post->ID ).'</a>
							   </h4>';
				$wprpi_val .= $thumbnail;
				$wprpi_val .= '<p>'.wp_trim_words( $post->post_content, $wprpi_value['excerpt_length'] ).'</p>';
				$wprpi_val .= '</div>';

			} else {
				$wprpi_val .= $icon_val;
				$wprpi_val .= '<a href="'.esc_url(get_page_link( $post->ID )).'">'.get_the_title( $post->ID ).'</a>';
			}
			$wprpi_val .= '</div>';
		}

		// reset post data
		wp_reset_postdata();

		// Closing extra div's if have any
		$wprpi_val .= '</div>';

		// return related post
		return $wprpi_val;
	}
	add_shortcode( 'wprpi', 'wprpi_short_code_func' );
}