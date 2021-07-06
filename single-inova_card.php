<?php 
    get_header();

    if( have_posts() ) {
        while ( have_posts() ) {
            the_post();

            $html = get_field('html');
            $css = '<style type="text/css">' . get_field('css') . '</style>';
        } 
    }
    print_r($css);
?>
    <div class="mui-container-fluid">
        <div class="mui-row">
            <div class="mui-col-md-2">
                <button class="mui-btn mui-btn--danger" onclick="activateModal()">Tạo thiệp mới</button>
            </div>
            <div class="mui-col-md-7">
                <div class="mui-panel">
                    <?php the_title(); ?>
                    <div class="mui-divider"></div>
                    <div class="mui-row">
                        <div class="mui-col-md-12">
                        <?php 
                            print_r($html);
                        ?>
                        </div>
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
    get_footer();
?>