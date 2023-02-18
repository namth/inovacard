<?php
/* 
    Template Name: Edit Cards
*/
use FileBird\Classes\Tree;
use FileBird\Model\Folder as FolderModel;

function inova_import_media($folder_id, $files){
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $file = array( 
                'name' => $files['name'][$key],
                'type' => $files['type'][$key], 
                'tmp_name' => $files['tmp_name'][$key], 
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ); 
            $_FILES = array("upload_file" => $file);
            $attachment_id = media_handle_upload("upload_file", 0);

            if (is_wp_error($attachment_id)) {
                // There was an error uploading the image.
                return false;
            } else {
                // The image was uploaded successfully!
                $post_ids[] = $attachment_id;
            }
        }
    }

    # Add attachments to folder
    FolderModel::setFoldersForPosts( $post_ids, $folder_id );

    return $post_ids;
}

if (isset($_GET['id']) && $_GET['id']) {
    $postid = $_GET['id'];
    $current_folder = get_field('folder_name', $postid);

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

        if (isset($_POST['card_folder']) && ($_POST['card_folder'] != "")) {
            $folder_name = $_POST['card_folder'];
            $parent_id = 0;
            # get or create folder by name
            $folder_id = FolderModel::newOrGet( $folder_name, $parent_id );
            if ($current_folder != $folder_name) {
                # update folder_name to custom field
                update_field('field_63c602f42df66', $folder_name, $postid);
            }

            $cssfiles   = $_FILES['cssfiles'];
            $jsfiles    = $_FILES['jsfiles'];
            $assets     = $_FILES['assets'];
            $thumbnail  = $_FILES['thumbnail'];


            if ($cssfiles) {
                $file_ids = inova_import_media($folder_id, $cssfiles);
                # save css to customfield 
                if ($file_ids) {
                    # get current file    
                    $current_file = explode( '|', get_field('css', $postid));
                    $list_files = array_filter(array_merge($current_file, $file_ids));
                    update_field('field_60e16c8bab332', implode( '|', $list_files), $postid);
                }
            }
            if ($jsfiles) {
                $file_ids = inova_import_media($folder_id, $jsfiles);
                # save js to customfield 
                if ($file_ids) {
                    # get current file    
                    $current_file = explode( '|', get_field('components', $postid));
                    $list_files = array_filter(array_merge($current_file, $file_ids));
                    update_field('field_60e2a2eda1482', implode( '|', $list_files), $postid);
                }
            }
            if ($assets) {
                $file_ids = inova_import_media($folder_id, $assets);
                # save assets to customfield 
                if ($file_ids) {
                    # get current file    
                    $current_file = explode( '|', get_field('assets', $postid));
                    $list_files = array_filter(array_merge($current_file, $file_ids));
                    update_field('field_60e2a2e0a1481', implode( '|', $list_files), $postid);
                }
            }
            if ($thumbnail) {
                $file_ids = inova_import_media($folder_id, $thumbnail);
                set_post_thumbnail($postid, $file_ids[0]);
            }
        }

        if (isset($_POST['html']) && ($_POST['html'] != "")) {
            update_field('field_60e16c5bab331', $_POST['html'], $postid);
        }

        wp_redirect(get_bloginfo('url'));
    }

    get_header();
?>
    <div class="mui-container-fluid">
        <div class="mui-row">
            <div class="mui-col-md-2">
                <a class="mui-btn mui-btn--danger" href="<?php echo get_bloginfo('url') ?>">Quay lại</a>
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
                            <label for="html">Tên thư mục chứa file đã upload</label>
                            <input type="text" name="card_folder" value="<?php echo get_field('folder_name', $postid); ?>">
                        </div>
                        <div class="mui-textfield">
                            <label for="cssfiles">CSS Files</label>
                            <input type="file" name="cssfiles[]" multiple="multiple">
                        </div>
                        <div class="mui-textfield">
                            <label for="jsfiles">JS Files</label>
                            <input type="file" name="jsfiles[]" multiple="multiple">
                        </div>
                        <div class="mui-textfield">
                            <label for="jsfiles">Assets</label>
                            <input type="file" name="assets[]" multiple="multiple">
                        </div>
                        <div class="mui-textfield">
                            <label for="jsfiles">Ảnh thumbnail</label>
                            <input type="file" name="thumbnail[]" multiple="multiple">
                        </div>
                        <div class="mui-textfield">
                            <label for="jsfiles">HTML</label>
                            <textarea class="html" name="html" cols="30" rows="20"><?php echo get_field('html', $postid); ?></textarea>
                        </div>
                        <?php
                        wp_nonce_field('post_nonce', 'post_nonce_field');
                        ?>
                        <button type="submit" class="mui-btn mui-btn--raised">Submit</button>
                    </form>
                </div>
            </div>
            <?php get_sidebar('editcard'); ?>
        </div>
    </div>
<?php
}
get_footer();
?>