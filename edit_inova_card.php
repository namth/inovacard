<?php
/* 
    Template Name: Edit Cards
*/
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

get_header();

if (isset($_GET['id']) && $_GET['id']) {
    $postid = $_GET['id'];

    if (
        is_user_logged_in() &&
        isset($_POST['post_nonce_field']) &&
        wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')
    ) {
        // print_r($_FILES);
        if (isset($_POST['card_title']) && ($_POST['card_title']!="")) {
            $update = wp_update_post(array(
                'ID'            => $postid,
                'post_title'    => $_POST['card_title'],
            ));
        }

        if (isset($_FILES['file_upload'])) {
            $attach_id = media_handle_upload('file_upload', $postid );
            //On sauvegarde la photo dans le média library
            // $attach_id = wp_insert_attachment($attachment, $movefile['file']);

            set_post_thumbnail($postid, $attach_id);
            wp_redirect(get_bloginfo('url'));
        }
    }
?>
    <div class="mui-container-fluid">
        <div class="mui-row">
            <div class="mui-col-md-2">
                <a class="mui-btn mui-btn--danger" href="javascript:history.go(-1)">Quay lại</a>
            </div>
            <div class="mui-col-md-7">
                <div class="mui-panel">
                    <h3>Sửa thiệp</h3>
                    <form class="mui-form" method="POST" enctype="multipart/form-data">
                        <div class="mui-textfield">
                            <label for="html">Tên thiệp</label>
                            <input type="text" name="card_title" value="<?php echo get_the_title($postid); ?>">
                        </div>
                        <div class="mui-textfield">
                            <label for="css">Ảnh thumbnail</label>
                            <input type="file" name="file_upload">
                        </div>
                        <?php
                        wp_nonce_field('post_nonce', 'post_nonce_field');
                        ?>
                        <button type="submit" class="mui-btn mui-btn--raised">Submit</button>
                    </form>
                </div>
            </div>
            <div class="mui-col-md-3"></div>
        </div>
    </div>
<?php
}
get_footer();
?>