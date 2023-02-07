<?php 
if( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        $html = get_field('html');
        $css_arr = explode('|', get_field('css'));
        foreach ($css_arr as $value) {
            if ($value) {
                $filelink = wp_get_attachment_url($value);
                $css .= '<link rel="stylesheet" href="' . $filelink . '" type="text/css" />';
            }
        }
        $data_replace['{html_header}'] = $css;

        $js_arr = explode('|', get_field('components'));
        foreach ($js_arr as $value) {
            if ($value) {
                $filelink = wp_get_attachment_url($value);
                $js .= '<script src="' . $filelink . '"></script>';
            }
        }
        $data_replace['{html_footer}'] = $js;

        $assets_arr = explode('|', get_field('assets'));
        foreach ($assets_arr as $value) {
            if ($value) {
                $filelink = wp_get_attachment_url($value);
                $filename = '{' . basename($filelink) . '}';
                $data_replace[$filename] = $filelink;
            }
        }

        $data_replace['{home_url}'] = get_bloginfo('url');

        # basic content
        $html   = replace_content($data_replace, $html);
    } 
}
print_r($html);

