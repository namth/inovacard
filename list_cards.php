<?php
get_header();
get_template_part('header', 'topbar');

$current_user_id = get_current_user_id();

# xóa vào thùng rác một thiệp
if (isset($_GET['delete'])) {
    // wp_delete_post($_GET['delete']);
    wp_trash_post($_GET['delete']);
    wp_redirect(get_bloginfo('url'));
}

# chỉnh sửa trạng thái một thiệp
if (isset($_GET['status'])) {
    if (in_array($_GET['status'], ['publish', 'private'])) {
        $args = array(
            'ID'            => $_GET['id'],
            'post_status'   => $_GET['status'],
        );
        wp_update_post($args);
    }
    wp_redirect(get_bloginfo('url'));
}

# Tạo một thiệp mới
if (isset($_POST['card_name'])) {
    $args = array(
        'post_title'    => $_POST['card_name'],
        'post_status'   => 'private',
        'post_type'     => 'inovacard',
    );

    $inserted = wp_insert_post($args, $error);
}

if (have_posts()) {
    while (have_posts()) {
        the_post();
?>
        <div class="mui-container-fluid">
            <div class="mui-row">
                <div class="mui-col-md-2">
                    <button class="mui-btn mui-btn--danger" onclick="activateModal()">Tạo thiệp mới</button>
                </div>
                <div class="mui-col-md-7">
                    <div class="mui-panel">
                        <h3>Danh sách thiệp của tôi</h3>
                        <div class="mui-divider"></div>
                        <div class="mui-row">
                            <?php
                            $current_user = wp_get_current_user();

                            // xử lý phân trang
                            $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

                            $args   = array(
                                'post_type'     => 'inovacard',
                                'paged'         => $paged,
                                'posts_per_page' => 20,
                                'author'        => $current_user_id,
                            );

                            $query = new WP_Query($args);

                            if ($query->have_posts()) {
                                while ($query->have_posts()) {
                                    $query->the_post();

                                    $image = get_the_post_thumbnail_url();

                                    $status = get_post_status();
                                    if ($status == 'publish') {
                                        $icon = '<i class="fa fa-lock" aria-hidden="true"></i>';
                                        $action = 'private';
                                    } else if ($status == 'private') {
                                        $icon = '<i class="fa fa-unlock" aria-hidden="true"></i>';
                                        $action = 'publish';
                                    }
                            ?>
                                    <div class="mui-col-md-4 inovacard">
                                        <a href="<?php echo get_permalink(37) . '?id=' . get_the_ID(); ?>">
                                            <div class="mui-panel">
                                                <img src="<?php echo $image; ?>" alt="">
                                                <?php 
                                                    if ($status == 'private') {
                                                        echo '<i class="fa fa-lock" aria-hidden="true"></i> ';
                                                    }
                                                    the_title(); 
                                                ?>
                                            </div>
                                        </a>
                                        <div class="function_icon">
                                            <ul>
                                                <?php 
                                                    if ($action) {
                                                        echo '<li><a href="?status=' . $action . '&id=' . get_the_ID() . '">' . $icon . '</a></li>';
                                                    }
                                                ?>
                                                <!-- <li>
                                                    <a href="<?php echo get_permalink(28) . '?id=' . get_the_ID(); ?>"><i class="fas fa-code"></i></a>
                                                </li> -->
                                                <li>
                                                    <a href="<?php echo get_permalink(); ?>" target="_blank"><i class="fas fa-eye"></i></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo get_permalink(37) . '?id=' . get_the_ID(); ?>"><i class="fas fa-pencil-alt"></i></a>
                                                </li>
                                                <li>
                                                    <a href="?delete=<?php echo get_the_ID(); ?>"><i class="far fa-trash-alt"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                            <?php
                                }
                                wp_reset_postdata();
                            } else {
                                echo "<div class='mui-col-md-4'>Hiện tại bạn chưa có mẫu thiệp nào.</div>";
                            }
                            ?>
                        </div>
                        <div class="pagination justify-content-center">
                            <?php
                            $big = 999999999; // need an unlikely integer

                            echo paginate_links(array(
                                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format'    => '?paged=%#%',
                                'current'   => max(1, get_query_var('paged')),
                                'total'     => $query->max_num_pages,
                                'type'      => 'list',
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="mui-col-md-3"></div>
            </div>
        </div>
        <div class="mui-col-md-4" id="create_card_form">
            <form class="mui-form" method="POST">
                <legend>Tạo thiệp mới</legend>
                <div class="mui-textfield">
                    <input type="text" placeholder="Tên mẫu" name="card_name">
                </div>
                <button type="submit" class="mui-btn mui-btn--primary">Submit</button>
            </form>
        </div>
<?php
    }
}
get_footer();
?>