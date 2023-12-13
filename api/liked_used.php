<?php 
add_action('rest_api_init', function(){
    register_rest_route('inova/v1', 'update_card', array(
        'methods'   => 'POST',
        'callback'  => 'update_card',
        'permission_callback' => 'check_access',
    ));
});

/* 
    Dữ liệu gửi về gồm 2 trường dữ liệu
        postID  => ID của thiệp
        type    => ['like', 'dislike', 'used'] là loại cập nhật, sẽ chỉ định cập nhật vào trường dữ liệu nào
*/
function update_card(WP_REST_Request $request){
    $data  = $request->get_body_params();
    $postid = $data['postID'];
    $type   = $data['type'];
    
    if (in_array($type, ['like', 'dislike', 'used'])) {
        $key_update = $type=='used'?'field_6295e78ac88fe':'field_6295e77bc88fd';
        $key_name   = $type;
    
        if ($postid && (get_post_status($postid) == 'publish')) {
            # lấy số liệu
            $temp = get_field($key_update, $postid);
            # tính toán tăng giảm số lượng
            if (($type == 'dislike') && ($temp > 0)) {
                $temp--;
                $key_name = 'like';
            } else $temp++;
            # update số liệu
            update_field($key_update, $temp, $postid);
    
            return array(
                'error_code'    => 0,
                'message'       => 'Update thành công.',
                $key_name       => $temp
            );
        } else {
            return array(
                'error_code'    => 404,
                'message'       => 'Dữ liệu không có hoặc chưa public.',
            );
        }
    } else {
        return array(
            'error_code'    => 400,
            'message'       => "Phân loại '$type' không đúng.",
        );
    }
}