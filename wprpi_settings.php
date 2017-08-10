<?php
/**
*   wprpi options panel settings
*/

/* Add default WordPress color picker support */
function wprpi_opt_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );
         
        // Add wprpi_init_js file to initialize color picker
        wp_enqueue_script( 'wprpi_init_js', plugins_url( 'js/wprpi_init.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}
add_action( 'admin_enqueue_scripts', 'wprpi_opt_color_picker' );

/* Create wprpi plugin settings menu */
function wprpi_options_nav() {

	//create a top-level menu
	add_options_page( 'WP-random-post-inside control panel', 'Wp random post inside Settings', 'manage_options', __FILE__, 'wprpi_settings', __FILE__ );

	//call register settings function
	add_action( 'admin_init', 'register_wprpi_settings_opt' );
}
add_action( 'admin_menu', 'wprpi_options_nav' );

/* Register field options */
function register_wprpi_settings_opt() {
	//register wprpi settings
    register_setting( 'wprpi-settings-opt', 'wprpi_related_by_cat' );
	register_setting( 'wprpi-settings-opt', 'wprpi_related_by_tag' );
    register_setting( 'wprpi-settings-opt', 'wprpi_show_icon' );
	register_setting( 'wprpi-settings-opt', 'wprpi_icon' );
    register_setting( 'wprpi-settings-opt', 'wprpi_link_color' );
    register_setting( 'wprpi-settings-opt', 'wprpi_hover_color' );
    register_setting( 'wprpi-settings-opt', 'wprpi_bg_color' );
    register_setting( 'wprpi-settings-opt', 'wprpi_title_color' );
}

/* Option panel display */
function wprpi_settings() {
?>
<div class="wrap">
    <h2><?php esc_html_e('WP Random Post Inside Settings', 'wp-random-post-inside'); ?></h2>

    <form class="wprpi_form_area" method="post" action="options.php">
        <?php settings_fields( 'wprpi-settings-opt' ); ?>
        <?php do_settings_sections( 'wprpi-settings-opt' ); ?>
        <table class="form-table wprpi-form">
            <tr valign="top">
                <th scope="row">
                    <?php esc_html_e( 'Show related posts by', 'wp-random-post-inside' ); ?>
                </th>
                <td>
                    <label class="checkbox" for="wprpi_related_by_cat">
                        <input id="wprpi_related_by_cat" type="checkbox" value="1" <?php checked(1, esc_attr(get_option('wprpi_related_by_cat')), true); ?> name="wprpi_related_by_cat">
                        <?php esc_html_e( 'Category', 'wp-random-post-inside' ); ?>
                    </label>
                    
                    <label class="checkbox" for="wprpi_related_by_tag">
                        <input id="wprpi_related_by_tag" type="checkbox" value="1" <?php checked(1, esc_attr(get_option('wprpi_related_by_tag')), true); ?> name="wprpi_related_by_tag">
                        <?php esc_html_e( 'Tag', 'wp-random-post-inside' ); ?>
                    </label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Show icon with link', 'wp-random-post-inside' ); ?></th>
                <td>
                    <label class="checkbox" for="wprpi_show_icon" id="set_wprpi_icon">
                        <input id="wprpi_show_icon" type="checkbox" value="1" <?php checked(1, esc_attr(get_option('wprpi_show_icon')), true); ?> name="wprpi_show_icon">
                        <?php esc_html_e( 'Show', 'wp-random-post-inside' ); ?>
                    </label>
                </td>
            </tr>
            
            <?php $hideclass = (get_option('wprpi_show_icon')) ? '' : 'class="wprpi_hide"'; ?>
            <tr valign="top" id="show_wprpi_icon" <?php echo $hideclass; ?>>
                <th scope="row"><?php esc_html_e( 'Choose an icon', 'wp-random-post-inside' ); ?></th>
                <td>
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="awards" <?php checked('awards', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-awards"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="admin-links" <?php checked('admin-links', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-admin-links"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="admin-post" <?php checked('admin-post', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-admin-post"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="arrow-right-alt2" <?php checked('arrow-right-alt2', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-arrow-right-alt2"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="external" <?php checked('external', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-external"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="megaphone" <?php checked('megaphone', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-megaphone"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="star-filled" <?php checked('star-filled', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-star-filled"></i>
                    </label>
                    
                    <label class="radiobtn">
                        <input type="radio" name="wprpi_icon" value="paperclip" <?php checked('paperclip', get_option('wprpi_icon'), true); ?>><i class="dashicons dashicons-paperclip"></i>
                    </label>
                </td>
            </tr>
             
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Link color', 'wp-random-post-inside' ); ?></th>
                <td><input class="color-field" data-default-color="#e80f00" type="text" name="wprpi_link_color" value="<?php echo esc_attr( get_option('wprpi_link_color') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Link hover color', 'wp-random-post-inside' ); ?></th>
                <td><input class="color-field" data-default-color="#e80f00" type="text" name="wprpi_hover_color" value="<?php echo esc_attr( get_option('wprpi_hover_color') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th class="wprpi_heading" scope="row">
                    <?php esc_html_e( '* Following settings are applicable for shortcode only', 'wp-random-post-inside' ); ?>
                </th>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Background color', 'wp-random-post-inside' ); ?></th>
                <td><input class="color-field" data-default-color="transparent" type="text" name="wprpi_bg_color" value="<?php echo esc_attr( get_option('wprpi_bg_color') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Title color', 'wp-random-post-inside' ); ?></th>
                <td><input class="color-field" data-default-color="#444" type="text" name="wprpi_title_color" value="<?php echo esc_attr( get_option('wprpi_title_color') ); ?>" /></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>

    </form>

    <div class="wprpi_opt_content">
        <header>
            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/wprpi_faq.png" alt="wp-related-plugin-inside logo">
        </header> <!-- header -->

        <hr>

        <span class="wprpi_info">
            <h3>
                <span class="dashicons dashicons-awards"></span>
                <?php esc_html_e('Using Shortcode', 'wp-random-post-inside'); ?>
            </h3>
            <p>
                <?php printf(
                    /* translators: %s: Name of a shortcode: [wprpi] */
                    __(
                        'To using shortcode add %s in your post where you want to show related post.',
                        'wp-random-post-inside'
                    ),
                    '<span class="code">[wprpi]</span>'
                ); ?>
                
            </p>
        </span> <!-- /.wprpi_info -->

        <span class="wprpi_info">
            <h3>
                <span class="dashicons dashicons-awards"></span>
                <?php esc_html_e('Using Shortcode with parameter', 'wp-random-post-inside'); ?>
            </h3>
            
            <p><strong>title:</strong>
                <?php esc_html_e('allow a title you want to show with related posts.', 'wp-random-post-inside'); ?>
            </p>
            <p><strong>by:</strong>
                <?php printf(
                    /* translators: %s: parameter name */
                    __(
                        'allow ( %s ) value for this parameter',
                        'wp-random-post-inside'
                    ),
                    'category / tag / both'
                ); ?>
            </p>
            <p><strong>post:</strong>
                <?php esc_html_e('allow total number of posts you want to show.', 'wp-random-post-inside'); ?>
            </p>
            <p><strong>icon:</strong>
                 <?php printf(
                    /* translators: %s: parameter name */
                    __(
                        'allow ( %s ) value for this parameter.',
                        'wp-random-post-inside'
                    ),
                    'show / none'
                ); ?>
            </p>
            <p><strong>post_id:</strong>
                <?php printf(
                    /* translators: %s: post id */
                    __(
                        'allow posts id separated by comma (Ex: %s).',
                        'wp-random-post-inside'
                    ),
                    'post_id="1,12,18"'
                ); ?>
            </p>
            <p><strong>cat_id:</strong>
                <?php printf(
                    /* translators: %s: category id */
                    __(
                        'allow category id separated by comma (Ex: %s).',
                        'wp-random-post-inside'
                    ),
                    'cat_id="1,5,11"'
                ); ?>
            </p>
            <p><strong>tag_slug:</strong>
                <?php esc_html_e('allow tag slugs separated by comma (Ex: hello,world).', 'wp-random-post-inside'); ?>
            </p>
            <p><strong>thumb_excerpt:</strong>
                <?php printf(
                    /* translators: %s: parameter name */
                    __(
                        'Show random post thumbnail and excerpt. Allow ( %s ) parameter. default value is false.',
                        'wp-random-post-inside'
                    ),
                    'true / false'
                ); ?>
            </p>
            <p><strong>excerpt_length:</strong>
                <?php printf(
                    /* translators: %d: number */
                    __(
                        'Limit random post excerpt length. (Ex: %d)',
                        'wp-random-post-inside'
                    ),
                    '55'
                ); ?>
            </p>

            <p>
                <?php esc_html_e('Example:', 'wp-random-post-inside'); ?> <br>
                <span class="code">
                    [wprpi title="Related Post" by="category" post="2" icon="show" thumb_excerpt="true" excerpt_length="55"]
                </span>
            </p>
        </span> <!-- /.wprpi_info -->

        <hr>

        <span class="wprpi_info">
            <h3>
                <span class="dashicons dashicons-awards"></span>
                <?php esc_html_e('Need more help?', 'wp-random-post-inside'); ?>
            </h3>
            <p>
                <?php printf(
                    /* translators: 1: plugin page url 2: wordpress repository url */
                    __(
                        'Visit <a href="%1$s">this plugin page</a> or <a href="%2$s">WordPress Support</a> and leave a comment with your question, I will try to help you find out the solution. Thank you for using this plugin and do not forget to rate it :)',
                        'wp-random-post-inside'
                    ),
                    'http://anisbd.com/update-informations-of-wp-random-post-inside-wordpress-plugin/',
                    'https://wordpress.org/support/plugin/wp-random-post-inside'
                ); ?>
            </p>
        </span> <!-- /.wprpi_info -->
    </div> <!-- /.wprpi_opt_content -->
</div>
<?php }