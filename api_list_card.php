<?php
/* 
    Template Name: API List Cards
*/
header("Content-Type:application/json");

$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

$args   = array(
    'post_type'         => 'inovacard',
    'paged'             => $paged,
    'posts_per_page'    => 50,
);

if (isset($_GET['id']) && ($_GET['id']!="")) {
    $args['p'] = $_GET['id'];
    $fullcontent = true;
} else {
    $fullcontent = false;
}

$query = new WP_Query($args);

$data   = array();
$i      = 0;

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        $image  = get_the_post_thumbnail_url();
        $price  = get_field('price');
        $liked  = get_field('liked');
        $used   = get_field('used');

        # basic content
        $data[$i]['ID']         = get_the_ID();
        $data[$i]['title']      = get_the_title();
        $data[$i]['thumbnail']  = $image;

        # full content
        if ($fullcontent) {
            $data[$i]['content'] = get_the_content();
        }
        
        # more content
        $data[$i]['price']      = $price;
        $data[$i]['liked']      = $liked;
        $data[$i]['used']       = $used;
        
        $i++;
    } wp_reset_postdata();
}

$json_response = json_encode($data);
echo $json_response;
