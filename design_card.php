<?php
/* 
    Template Name: Design Cards Template
*/
get_header();
if (isset($_GET['postid'])) {
    $postid = $_GET['postid'];
}

if ($postid) {
    echo '<input id="postid" type="hidden" name="postid" value="' . $postid . '">';
    echo '<input id="savelink" type="hidden" name="savelink" value="' . get_permalink(26) . '">';
    echo '<input id="loadlink" type="hidden" name="loadlink" value="' . get_permalink(28) . '">';
}
?>
<div style="display: none">
    <div class="gjs-logo-cont">
        <a href="<?php echo get_bloginfo('home'); ?>"><img class="gjs-logo" src="<?php echo get_template_directory_uri(); ?>/img/grapesjs-logo-cl.png"></a>
        <div class="gjs-logo-version"></div>
    </div>
</div>

<div id="gjs" style="height:0px; overflow:hidden">
    
    
</div>

<?php
get_footer();
?>