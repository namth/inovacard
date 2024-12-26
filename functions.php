<?php
// function
register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'inovacards')));
add_theme_support('title-tag');
add_theme_support( 'post-thumbnails' );

add_action('wp_enqueue_scripts', 'inovacards_load_scripts');
function inovacards_load_scripts()
{
    /* Css */
    wp_enqueue_style('main-style', get_template_directory_uri() . '/style.css');

    /* Js */
    wp_enqueue_script('jquery');

    /** Call design-cards enqueue */
    if (is_page('design-cards')) {
        /* CSS */
        wp_enqueue_style( 'toastr', get_template_directory_uri() . '/css/toastr.min.css' );
        wp_enqueue_style( 'grapes', get_template_directory_uri() . '/css/grapes.min.css' );
        wp_enqueue_style( 'grapesjs-preset-webpage', get_template_directory_uri() . '/css/grapesjs-preset-webpage.min.css' );
        wp_enqueue_style( 'grapesjs-plugin-filestack', get_template_directory_uri() . '/css/grapesjs-plugin-filestack.css' );
        wp_enqueue_style( 'demos', get_template_directory_uri() . '/css/demos.css' );
        wp_enqueue_style( 'grapick', 'https://unpkg.com/grapick/dist/grapick.min.css' );
        /* JS */
        wp_enqueue_script( 'toastr', get_template_directory_uri() . '/js/toastr.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'grapes', get_template_directory_uri() . '/js/grapes.min.js', array('jquery'), 'v0.17.19', true );
        wp_enqueue_script( 'grapesjs-preset-webpage', get_template_directory_uri() . '/js/grapesjs-preset-webpage.min.js', array('jquery'), 'v0.1.11', true );
        wp_enqueue_script( 'grapesjs-lory-slider', get_template_directory_uri() . '/js/grapesjs-lory-slider.min.js', array('jquery'), '0.1.5', true );
        wp_enqueue_script( 'grapesjs-custom-code', get_template_directory_uri() . '/js/grapesjs-custom-code.min.js', array('jquery'), '0.1.2', true );
        wp_enqueue_script( 'grapesjs-touch', get_template_directory_uri() . '/js/grapesjs-touch.min.js', array('jquery'), '0.1.1', true );
        wp_enqueue_script( 'grapesjs-parser-postcss', get_template_directory_uri() . '/js/grapesjs-parser-postcss.min.js', array('jquery'), '0.1.1', true );
        wp_enqueue_script( 'grapesjs-tui-image-editor', get_template_directory_uri() . '/js/grapesjs-tui-image-editor.min.js', array('jquery'), '0.1.2', true );
        wp_enqueue_script( 'grapesjs-style-bg', get_template_directory_uri() . '/js/grapesjs-style-bg.min.js', array('jquery'), '1.0.1', true );
        wp_enqueue_script( 'grapesjs-plugin-ckeditor', get_template_directory_uri() . '/js/grapesjs-plugin-ckeditor.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'ckeditor', get_template_directory_uri() . '/js/ckeditor/ckeditor.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true );
        wp_localize_script( 'custom', 'AJAX', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ));
    } else {
        wp_enqueue_style( 'mui', get_template_directory_uri() . '/css/mui.min.css' );
        wp_enqueue_style( 'jquery-linedtextarea', get_template_directory_uri() . '/css/jquery-linedtextarea.css' );
        wp_enqueue_style( 'inova', get_template_directory_uri() . '/css/inova.css' );

        wp_enqueue_script( 'mui', get_template_directory_uri() . '/js/mui.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'jquery-linedtextarea', get_template_directory_uri() . '/js/jquery-linedtextarea.js', array('jquery', 'mui'), '1.0', true );
        wp_enqueue_script( 'inova', get_template_directory_uri() . '/js/inova.js', array('jquery', 'mui'), '1.0', true );
        wp_enqueue_script( 'font-awesome', 'https://kit.fontawesome.com/dfe5b27416.js', array( ), '4.0', true );
        wp_localize_script( 'inova', 'AJAX', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ));
    }
}

/* Update plain text */
function update_plain_field($field_key = '', $field_name = '', $plain = '', $postid = '')
{
    global $wpdb;
    $table = $wpdb->prefix . 'postmeta';
    $field = $wpdb->get_results("SELECT * FROM $table WHERE post_id = '$postid' AND meta_key = '$field_name'");
    if ($field) {
        $wpdb->update(
            $table,
            array(
                'meta_value' => serialize($plain)
            ),
            array(
                'post_id' => $postid,
                'meta_key'=> $field_name
            ),
            array('%s')
        );
    } else {
        $wpdb->insert(
            $table,
            array(
                'post_id' => $postid,
                'meta_key' => $field_name,
                'meta_value' => serialize($plain),
            ),
            array(
                '%d',
                '%s',
                '%s',
            )
        );
        $wpdb->insert(
            $table,
            array(
                'post_id' => $postid,
                'meta_key' => '_' . $field_name,
                'meta_value' => serialize($plain)
            ),
            array(
                '%d',
                '%s',
                '%s',
            )
        );

    }
}

/* Redirect after logout */
add_action('wp_logout','ps_redirect_after_logout');
function ps_redirect_after_logout(){
    wp_redirect( get_bloginfo('url') );
    exit();
}

add_action('init','all_my_hooks');
function all_my_hooks(){
    $dir = dirname( __FILE__ );
    require_once( $dir . '/inc/custom_post.php');
    require_once( $dir . '/inc/custom_field.php');
    
    # AJAX function library
    require_once( $dir . '/inc/ajax_function.php');

    # API function library
    require_once( $dir . '/api_inovacard.php');
}

add_filter('upload_mimes', 'add_custom_upload_mimes');
function add_custom_upload_mimes($existing_mimes) {
    $existing_mimes['ttf'] = 'application/x-font-ttf';
    return $existing_mimes;
}

# replace content by any template
function replace_content($arr_replace, $content) {
    if (is_array($arr_replace)) {
        foreach ($arr_replace as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        return $content;
    }
}

# Không hiển thị chữ riêng tư lên tiêu đề bài post
add_filter( 'private_title_format', function ( $format ) {
    return '%s';
} );

# set secure for wp_login
add_action( 'set_auth_cookie', function ( $cookie ) {
    $cookie_name = is_ssl() ? SECURE_AUTH_KEY : AUTH_KEY;
    $_COOKIE[ $cookie_name ] = $cookie;
} );

add_action( 'set_logged_in_cookie', function ( $cookie ) {
    $_COOKIE[ LOGGED_IN_KEY ] = $cookie;
} );


# tìm kiếm và thay thế các thành phần thiệp theo component
# loại dữ liệu truyền vào có thể là ID hoặc slug của component
# mẫu component trên thiệp <#ID#> hoặc <#category-slug#>
function component_replace($html) {
    # định nghĩa pattern tìm kiếm
    $beforetag  = '<#';
    $aftertag   = '#>';
    $pattern    = '/'. $beforetag .'(.*?)' . $aftertag . '/';

    # tìm kiếm các thành phần component trong thiệp, nếu có thì thay thế
    if (preg_match_all($pattern, $html, $matches)) {
        # khởi tạo biến lưu trữ
        $css = $js = "";
        foreach ($matches[1] as $component_element) {
            # với mỗi loại, lấy ra một bài viết tương ứng và gán vào mảng thay thế
            # nếu $component_element là số thì sẽ lấy content của bài viết tương ứng với ID đó
            # nếu là chữ thì sẽ lấy bài viết đầu tiên theo phân loại đó.
            if (is_numeric($component_element)) {
                # lấy content của bài viết
                $content_post = get_post($component_element);
                $replace_content = $content_post->post_content;
            } else {
                $args = array(
                    'post_type' => 'component',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'card_element',
                            'field' => 'slug',
                            'terms' => $component_element
                        )
                    ),
                    'orderby'   => 'ID',
                    'order'     => 'ASC',
                    'posts_per_page' => 1
                );

                $query = new WP_Query( $args );

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();

                        $replace_content = get_the_content();

                        # đọc css và js để gắn vào cuối file
                        $css    .= get_field('css');
                        $js     .= get_field('components');
                    }
                    wp_reset_postdata();
                }
            }

            $card_replace[$beforetag . $component_element . $aftertag] = $replace_content;
        }

        $add_footer = '
            <style type="text/css">'
                . $css .
            '</style>
            <script>
                jQuery(document).ready(function ($) {
                    ' . $js . '
                });
            </script>';

        $card_replace['{html_footer}'] = '{html_footer}' . $add_footer;

        return replace_content($card_replace, $html);
    } else {
        return $html;
    }
}