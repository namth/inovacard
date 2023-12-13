<?php 

add_action('rest_api_init', function (){
    # get access token
    register_rest_route('inova/v1', 'gettoken', array(
        'methods'   => 'POST',
        'callback'  => 'get_token',
    ));
    # check token
    register_rest_route('inova/v1', 'checktoken', array(
        'methods'   => 'POST',
        'callback'  => 'check_token',
        'permission_callback' => 'check_access',
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

    # get HTML each card
    register_rest_route('inova/v1', 'html/(?P<id>\d+)(?:/(?P<html>[\w]+))?', array(
        'methods'   => 'GET',
        'callback'  => 'api_html_card',
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

/* 
*  Hàm lấy mã token, lấy nonce của tên người dùng và pass đã mã hoá, sau đó sử dụng hash sha256
*/
function get_token(){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check = wp_authenticate_username_password( NULL, $username, $password );

    if($check->ID){
        $randomCode = wp_create_nonce($username .'|'. $check->data->user_pass);
        $data['token'] = hash('sha256', $randomCode);
        // $data['user'] = $check;
    } else {
        $data['error_code'] = 401;
        $data['message']    = "Không tạo được token.";
    }
    return $data;
}

/* Kiểm tra token, nếu qua bước check_access thì return thành công luôn
*  Hàm này sử dụng để kiểm tra nhanh token có sử dụng hợp lệ hay không, nếu không thì lấy lại token mới.
*/
function check_token() {
    return array(
        'code'      => 'success',
        'message'   => 'Kiểm tra token hợp lệ.',
    );
}

/* Hàm kiểm tra header truyền token vào có hợp lệ hay không */
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

/* Hiển thị danh sách cards */
function api_list_card() {
    $status = $_GET['status'];
    $paged = ($_GET['page']) ? absint($_GET['page']) : 1;

    $args   = array(
        'post_type'         => 'inovacard',
        'paged'             => $paged,
        'posts_per_page'    => 50,
    );

    if ($status) {
        $args['post_status'] = $status;
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

            $terms = get_the_terms(get_the_ID(), 'category');

            $image  = get_the_post_thumbnail_url();
            $price  = get_field('price');
            $liked  = get_field('liked');
            $used   = get_field('used');

            # basic content
            $data['ID']         = get_the_ID();
            $data['title']      = get_the_title();
            $data['thumbnail']  = $image;
            $data['content']    = get_the_content();
            $data['terms']      = $terms;

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

/* Chỉ lấy HTML ra khi cần thiết */
function api_html_card($params) {
    $cardid = $params['id'];
    $key_html = $params['html'];
    $args   = array(
        'post_type' => 'inovacard',
        'p'         => $cardid,
    );
    
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        if ($status) {
            $args['post_status'] = $status;
        }
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $content1   = get_field('content_1');
            $content2   = get_field('content_2');
            $content3   = get_field('content_3');
            $content4   = get_field('content_4');
            $type       = get_field('type');
            if ($key_html) {
                $html   = get_field($key_html);
            } else $html   = get_field('html');
            $print_card_temp   = get_field('print_card_temp');
            $css    = '';
            $js     = '';

            if ($type!='HTML') {
                $css = '<style type="text/css">' . get_field('css') . '</style>';
            } else {
                # nếu là loại HTML thì xử lý 
                # đọc tất cả file css và lấy link rồi gắn vào thành 1 chuỗi
                $css_arr = explode('|', get_field('css'));
                foreach ($css_arr as $value) {
                    if ($value) {
                        $filelink = wp_get_attachment_url($value);
                        $css .= '<link rel="stylesheet" href="' . $filelink . '" type="text/css" />';
                    }
                }

                # đọc tất cả file js trong thiệp và lưu vào thành 1 chuỗi
                $js_arr = explode('|', get_field('components'));
                foreach ($js_arr as $value) {
                    if ($value) {
                        $filelink = wp_get_attachment_url($value);
                        $js .= '<script src="' . $filelink . '"></script>';
                    }
                }

                # đọc file trong danh sách assets và lấy ra tất cả link ảnh vào mảng $assets
                $assets_arr = explode('|', get_field('assets'));
                foreach ($assets_arr as $value) {
                    if ($value) {
                        $filelink = wp_get_attachment_url($value);
                        $filename = '{' . basename($filelink) . '}';
                        $assets[$filename] = $filelink;
                    }
                }

                # tạo mảng thay thế để chèn css, js và ảnh vào thiệp
                if (isset($assets) && is_array($assets)) {
                    $data_replace = array_merge([
                        '{html_header}' => $css . '{wp_header}',
                        '{html_footer}' => $js . '{wp_footer}'
                    ], $assets);
                }
                $data_replace['{home_url}'] = get_bloginfo('url');
            }

            # basic content
            $data['html']   = replace_content($data_replace, $html);
            $data['content1'] = $content1;
            $data['content2'] = $content2;
            $data['content3'] = $content3;
            $data['content4'] = $content4;
            $data['keyhtml']  = $key_html;
        } wp_reset_postdata();
    } else {
        $data['error_code'] = "404";
    }

    return $data;
}


require_once( $dir . '/api/liked_used.php');