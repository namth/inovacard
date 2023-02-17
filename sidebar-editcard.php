<div class="mui-col-md-3" id="file_sidebar">
    <h3>CSS Files</h3>
    <?php
    $postid = $_GET['id'];

    $current_css_ids = get_field('css', $postid);
    $file_array = explode('|', $current_css_ids);
    // $folder_name = get_field('folder_name', $postid);

    foreach ($file_array as $key => $fileid) {
        $filelink = wp_get_attachment_url($fileid);
        $filename = basename($filelink);

        echo '<div class="file_item">
                <div>
                    <a target="_blank" href="' . $filelink . '"><i class="fa fa-css3" aria-hidden="true"></i></a> 
                    {' . $filename . '}
                </div>
                <div>
                    <a href="{' . $filename . '}" class="copy"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                    <a href="' . get_permalink(170) . '?f=' . $fileid . '&id=' . $postid . '"><i class="fa fa-edit" aria-hidden="true"></i></a> 
                    <a href="#" data-fields="field_60e16c8bab332" data-fileid="' . $fileid . '" data-postid="' . $postid . '" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>';
    }
    ?>

    <h3>JS Files</h3>
    <?php
    $current_file_ids = get_field('components', $postid);
    $file_array = explode('|', $current_file_ids);

    foreach ($file_array as $key => $fileid) {
        $filelink = wp_get_attachment_url($fileid);
        $filename = basename($filelink);

        echo '<div class="file_item">
                <div>
                    <a target="_blank" href="' . $filelink . '"><i class="fa fa-file-code-o" aria-hidden="true"></i></a> 
                    {' . $filename . '}
                </div>
                <div>
                    <a href="{' . $filename . '}" class="copy"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                    <a href="' . get_permalink(170) . '?f=' . $fileid . '&id=' . $postid . '"><i class="fa fa-edit" aria-hidden="true"></i></a>
                    <a href="#" data-fields="field_60e2a2eda1482" data-fileid="' . $fileid . '" data-postid="' . $postid . '" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>';
    }
    ?>

    <h3>Assets</h3>
    <?php
    $current_file_ids = get_field('assets', $postid);
    $file_array = explode('|', $current_file_ids);

    foreach ($file_array as $key => $fileid) {
        $filelink = wp_get_attachment_url($fileid);
        $filename = basename($filelink);

        echo '<div class="file_item">
                <div>
                    <a target="_blank" href="' . $filelink . '"><i class="fa fa-file-image-o" aria-hidden="true"></i></a> 
                    {' . $filename . '}
                </div>
                <div>
                    <a href="{' . $filename . '}" class="copy"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                    <a href="#" data-fields="field_60e2a2e0a1481" data-fileid="' . $fileid . '" data-postid="' . $postid . '" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>';
    }
    ?>

    <h3>Fonts</h3>
    <?php 
    use FileBird\Model\Folder as FolderModel;
    use FileBird\Classes\Helpers as Helpers;

    # Lấy folder id theo tên folder
    $folder_id = FolderModel::newOrGet( 'fonts', 0 );
    # Lấy danh sách file theo folder id đã lấy trước đó
    $ids = Helpers::getAttachmentIdsByFolderId( $folder_id );

    # Hiển thị file theo danh sách ID 
    foreach ($ids as $key => $id) {
        $filelink = wp_get_attachment_url($id);
        $filename = basename($filelink);

        echo '<div class="file_item">
                <div>
                    <a target="_blank" href="' . $filelink . '"><i class="fa fa-file-image-o" aria-hidden="true"></i></a> 
                    {' . $filename . '}
                </div>
                <div>
                    <a href="{' . $filename . '}" class="copy"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                    <a href="#" data-fields="field_60e2a2e0a1481" data-fileid="' . $id . '" data-postid="' . $postid . '" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>';
    }
    ?>
</div>