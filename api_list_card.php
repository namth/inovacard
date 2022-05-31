<?php
/* 
    Template Name: API List Cards
*/
header("Content-Type:application/json");

$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

$args   = array(
    'post_type'         => 'inova_card',
    'paged'             => $paged,
    'posts_per_page'    => 50,
);

if (isset($_GET['id']) && ($_GET['id']!="")) {
    $args['p'] = $_GET['id'];
}

$query = new WP_Query($args);

$data   = array();
$i      = 0;

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        $image = get_the_post_thumbnail_url();
        $heart = get_field('heart');
        
        $data[$i]['ID']         = get_the_ID();
        $data[$i]['title']      = get_the_title();
        $data[$i]['thumbnail']  = $image;
        $data[$i]['heart']      = $heart;
        
        $i++;
    } wp_reset_postdata();
}

$json_response = json_encode($data);
echo $json_response;
