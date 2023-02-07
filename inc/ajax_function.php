<?php
/* 
* Source: sidebar-editcard.php
* Xử lý khi bấm nút xoá file đính kèm của thiệp
*/
add_action('wp_ajax_deleteAttachmentByID', 'deleteAttachmentByID');
function deleteAttachmentByID()
{
    $postid = $_POST['postid'];
    $fileid = $_POST['fileid'];
    $fields = $_POST['fields'];

    $list_file = explode('|', get_field($fields, $postid));

    if (($key = array_search($fileid, $list_file)) !== false) {
        # neu co id trong danh sach thi xoa khoi danh sach
        unset($list_file[$key]);

        # update lai custom field
        update_field($fields, implode('|', $list_file), $postid);

        # xoa file theo attach id
        $return = wp_delete_attachment($fileid, true);
        print_r($return);
    }

    exit;
}