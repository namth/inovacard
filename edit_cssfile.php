<?php
/* 
    Template Name: Edit Files e.g CSS JS
*/
use FileBird\Classes\Tree;
use FileBird\Model\Folder as FolderModel;

if (isset($_GET['f']) && $_GET['f'] && $_GET['id']) {
    $postid = $_GET['id'];
    $fileid = $_GET['f'];
    $folder_name = get_field('folder_name', $postid);

    $filepath = get_attached_file($fileid);

    if (
        is_user_logged_in() &&
        isset($_POST['post_nonce_field']) &&
        wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')
    ) {
        if (isset($_POST['html']) && ($_POST['html'] != "")) {
            $assets_arr = explode('|', get_field('assets', $postid));
            foreach ($assets_arr as $value) {
                if ($value) {
                    $filelink = wp_get_attachment_url($value);
                    $filename = '{' . basename($filelink) . '}';
                    $data_replace[$filename] = $filelink;
                }
            }

            $html   = replace_content($data_replace, $_POST['html']);

            file_put_contents($filepath, stripslashes($html));
        }
        // wp_redirect(get_bloginfo('url'));
    }

    get_header();
?>
    <div class="mui-container-fluid">
        <div class="mui-row">
            <div class="mui-col-md-2">
                <a class="mui-btn mui-btn--danger" href="<?php echo get_permalink(37) . '?id=' . $postid; ?>">Quay lại</a>
            </div>
            <div class="mui-col-md-7">
                <div class="mui-panel">
                    <h3>Sửa file</h3>
                    <form class="mui-form" method="POST" enctype="multipart/form-data">
                        <div class="mui-textfield">
                            <label for="jsfiles"><?php echo basename($filepath); ?></label>
                            <textarea class="html" name="html" cols="30" rows="20"><?php echo file_get_contents($filepath); ?></textarea>
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