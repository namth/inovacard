<?php 

add_action('rest_api_init', function (){
    # get access token
    register_rest_route('inova/v1/', 'gettoken', array(
        'methods'   => 'POST',
        'callback'  => 'get_token',
    ));
    # get list cards
    register_rest_route('inova/v1', 'cards', array(
        'methods'   => 'GET',
        'callback'  => 'api_list_card',
        'permission_callback' => 'check_access',
    ));

    # get detail each card
    register_rest_route('inova/v1', 'card/(?P<id>\d+)', array(
        'methods'   => 'GET',
        'callback'  => 'api_detail_card',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric( $param );
                }
            ),
        ),
        'permission_callback' => 'check_access',
    ));


});

function get_token(){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check = wp_authenticate_username_password( NULL, $username, $password );
    $user_obj = get_user_by('login', $username);

    if($check){
        $randomCode = wp_create_nonce($username .'|'. $check->data->user_pass);
        $data['token'] = hash('sha256', $randomCode);
    } else {
        $data['error_code'] = 401;
        $data['message']    = "Không tạo được token.";
    }
    return $data;
}

function check_access(WP_REST_Request $request){
    $token  = $request->get_header('Authorization');
    $username = 'inovacard';
    $user_obj = get_user_by('login', $username);
    $randomCode = wp_create_nonce($username.'|'.$user_obj->data->user_pass);
    $datatoken = hash('sha256', $randomCode);

    if ($token === $datatoken) {
        return true;
    }
    return false;
}

function api_list_card() {
    $paged = ($_GET['page']) ? absint($_GET['page']) : 1;

    $args   = array(
        'post_type'         => 'inovacard',
        'paged'             => $paged,
        'posts_per_page'    => 50,
    );

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

            # more content
            $data[$i]['price']      = $price;
            $data[$i]['liked']      = $liked;
            $data[$i]['used']       = $used;
            
            $i++;
        } wp_reset_postdata();
    } else {
        $data['error_code'] = "404";
    }

    return $data;
}

function api_detail_card($params) {
    $cardid = $params['id'];
    $args   = array(
        'post_type' => 'inovacard',
        'p'         => $cardid,
    );

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $image  = get_the_post_thumbnail_url();
            $price  = get_field('price');
            $liked  = get_field('liked');
            $used   = get_field('used');

            # basic content
            $data['ID']         = get_the_ID();
            $data['title']      = get_the_title();
            $data['thumbnail']  = $image;
            $data['content']    = get_the_content();

            # more content
            $data['price']      = $price;
            $data['liked']      = $liked;
            $data['used']       = $used;
        } wp_reset_postdata();
    } else {
        $data['error_code'] = "404";
    }

    return $data;
}