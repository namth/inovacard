<?php 
$function_div = "";
# dữ liệu tuỳ chỉnh
$data_replace = array(
    '{noi_dung_1}'  => 'Trân trọng kính mời',
    '{noi_dung_2}'  => 'Tới dự bữa tiệc thân mật <br>mừng lễ thành hôn cùng gia đình chúng tôi',
    '{noi_dung_3}'  => 'Tiệc cưới sẽ được tổ chức vào lúc: ',
    '{loi_moi}'     => wpautop('Sự hiện diện của bạn là niềm vui của gia đình chúng tôi. Chúng tôi xin chân thành cảm ơn và kính mong bạn sẽ có mặt trong ngày trọng đại của chúng tôi.'),
    '{khach_moi}'   => 'Bạn Mai',
    '{chu_re}'      => 'Đăng Khoa',
    '{co_dau}'      => 'Hạnh Quyên',
    '{G}'           => 'K',
    '{B}'           => 'Q',
    '{ngay_duong_lich_dam_cuoi}' => '11/11/2022',
    '{ngay_am_lich_dam_cuoi}'    => 'ngày 23 tháng 9 năm Nhâm Dần',
    '{gio_dam_cuoi}' => '12 giờ 30',
    '{wedding_location}'        => 'Tư gia',
    '{dia_diem_dam_cuoi}'       => '885 Tam Trinh, Quận Hoàng Mai, Thành phố Hà Nội',
    '{google_maps_dam_cuoi}'    => $button_maps_dam_cuoi,
    '{ngay_duong_lich_an_co}'   => '11/11/2022',
    '{ngay_am_lich_an_co}'      => 'ngày 23 tháng 9 năm Nhâm Dần',
    '{gio_an_co}'   => '12 giờ 30',
    '{party_location}'          => 'Trung tâm tiệc cưới',
    '{dia_diem_an_co}'          => '885 Tam Trinh, Quận Hoàng Mai, Thành phố Hà Nội',
    '{google_maps_an_co}'       => $button_maps_an_co,
    '{function_button}'         => $function_div,
    '{wp_header}'   => $wp_head,
    '{wp_footer}'   => $wp_footer,
    '{2}'           => 'bạn',
    '{1}'           => 'chúng tôi',
    '{ten}'         => 'Mai',
    '{wedding_dayname}'     => 'Chủ nhật',
    '{wedding_day}'         => '11',
    '{wedding_month}'       => '11',
    '{wedding_year}'        => '2022',
    '{wedding_moon_day}'        => '23',
    '{wedding_moon_month}'      => '9',
    '{wedding_moon_year}'       => '2022',
    '{wedding_moon_year_text}'  => 'Nhâm Dần',
    '{party_dayname}'     => 'Chủ nhật',
    '{party_day}'         => '11',
    '{party_month}'       => '11',
    '{party_year}'        => '2022',
    '{party_moon_day}'        => '23',
    '{party_moon_month}'      => '9',
    '{party_moon_year}'       => '2022',
    '{party_moon_year_text}'  => 'Nhâm Dần',
    '{bo_co_dau}'   => 'Nguyễn Văn Cao',
    '{me_co_dau}'   => 'Phạm Thị Tuyết',
    '{bo_chu_re}'   => 'Đinh Văn Linh',
    '{me_chu_re}'   => 'Vũ Mai Tính',
);

if( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        $html = get_field('html');
        #replace các component vào thiệp
        $html = component_replace($html);
        $css_arr = explode('|', get_field('css'));
        foreach ($css_arr as $value) {
            if ($value) {
                $filelink = wp_get_attachment_url($value);
                $css .= '<link rel="stylesheet" href="' . $filelink . '" type="text/css" />';
            }
        }
        $data_replace['{html_header}'] = $css;

        $js_arr = explode('|', get_field('components'));
        foreach ($js_arr as $value) {
            if ($value) {
                $filelink = wp_get_attachment_url($value);
                $js .= '<script src="' . $filelink . '"></script>';
            }
        }
        $data_replace['{html_footer}'] = $js;

        $assets_arr = explode('|', get_field('assets'));
        foreach ($assets_arr as $value) {
            if ($value) {
                $filelink = wp_get_attachment_url($value);
                $filename = '{' . basename($filelink) . '}';
                $data_replace[$filename] = $filelink;
            }
        }

        $data_replace['{home_url}'] = get_bloginfo('url');

        # basic content
        $html   = replace_content($data_replace, $html);
    } 
}
print_r($html);

