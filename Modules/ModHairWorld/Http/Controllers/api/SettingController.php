<?php
/**
 * Created by PhpStorm.
 * User: hung
 * Date: 4/14/20
 * Time: 16:31
 */
namespace Modules\ModHairWorld\Http\Controllers\api;

use App\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function becomeSalonManagerConfig()
    {
        $settings = [
            'theme_config_manager_intro_text_1' => 'Phát triển đặt chỗ và quản lý salon<br/>của bạn với iSalon',
            'theme_config_manager_intro_text_2' => 'Nền tảng đặt lịch làm tóc đầu tiên tại Việt Nam',
            'theme_config_manager_intro_text_3' => 'Tự do tham gia, không cần cam kết hoặc yêu cầu thẻ tin dụng',
            'theme_config_manager_feature_headline' => 'Khám phá cách chúng tôi có thể giúp bạn <strong>phát triển salon của mình</strong>',
            'theme_config_manager_feature_headline_sub' => 'iSalon là một nền tảng đặt phòng làm đẹp giúp salon tóc của bạn kết nối với khách hàng mới và hiện tại. iSalon là phần mềm quản lý đặt chỗ hiện đại, được thiết kế để đơn giản hóa việc quản lý các hoạt động hàng ngày của bạn. Người dùng quản lý salon của chúng tôi yêu thích tính năng quản lý nhóm và lập lịch, lời nhắc cuộc hẹn tự động.',
            'theme_config_manager_feature_list' => [],
            'theme_config_manager_app_link_title' => 'Tiếp thị mạnh mẽ để tăng khả năng hiển thị trực tuyến và đặt chỗ của bạn',
            'theme_config_manager_app_link_desc' => 'Tăng phạm vi tiếp cận của bạn bằng cách liệt kê salon của bạn trên nền tảng trực tuyến và ứng dụng dành cho người tiêu dùng của chúng tôi. Chúng tôi đảm bảo cho tiệm của bạn khả năng hiển thị tối ưu và tỷ lệ chuyển đổi cao nhờ nội dung có liên quan như mô tả về tiệm biên tập và mô tả dịch vụ, đánh giá và đưa khách hàng có liên quan vào bản tin',
            'theme_config_manager_app_link_image' => false,
            'theme_config_manager_app_manager_title' => 'Phần mềm quản lý salon tiện lợi và đơn giản',
            'theme_config_manager_app_manager_content' => 'Tối ưu hóa khả năng của thẩm mỹ viện và quản lý doanh số hàng ngày của bạn với Hệ thống quản lý Salon toàn diện và dễ sử dụng của chúng tôi. Với các tính năng dễ như lời nhắc cuộc hẹn, phân bổ nhân viên và quản lý thời gian và báo cáo, iSalon giúp quản lý tiệm làm đẹp hoặc spa của bạn dễ dàng. Khả dụng trên trình duyệt web của bạn, iOS và Android',
            'theme_config_manager_app_manager_image' => false,
            'theme_config_manager_app_manager_features_image' => false,
            'theme_config_manager_app_manager_features' => [],
            'theme_config_manager_logos' => [],
            'theme_config_manager_tes_title' => 'Khách hàng đối tác đang nói về chúng tôi',
            'theme_config_manager_tes_content' => 'iSalon đã giúp chúng tôi quản lý các đặt phòng hiệu quả hơn và mang lại nhận thức theo cấp số nhân cho cửa hàng mới khai trương của chúng tôi tại Capitol Piazza.
Được liệt kê trên iSalon cho phép chúng tôi có được nhiều thời gian đặt chỗ không cao điểm, đó là một điều tuyệt vời cho bất kỳ doanh nghiệp nào.
Nhóm nghiên cứu cũng là một niềm vui để làm việc cùng, và nền tảng là một tài sản cho chuỗi cửa hàng của chúng tôi.',
            'theme_config_manager_tes_cus_name' => 'Hoàng Yến',
            'theme_config_manager_tes_cus_job' => 'Quản lý salon Eles',
            'theme_config_manager_tes_cus_image' => false,
            'theme_config_manager_form_title' => 'Hãy liên lạc và bắt đầu phát triển doanh nghiệp của bạn',
            'theme_config_manager_form_desc' => 'Dù mục tiêu cho doanh nghiệp của bạn là gì, iSalon có thể giúp bạn đạt được mục tiêu đó.
Điền chi tiết của bạn bên dưới và Nhóm Đối tác của chúng tôi sẽ liên hệ với bạn.',

            'theme_master_mobile_app_android' => '',
            'theme_master_mobile_app_ios' => ''
        ];
        $settings = getSettings($settings);
        $featureList = $settings['theme_config_manager_feature_list'];
        if ($featureList) {
            $keys = array_keys($featureList);
            foreach ($keys as $key) {
                $feature = $featureList[$key];
                $feature["image"] = $this->getFileUrl($feature["image"]);
                $featureList[$key] = $feature;
            }
            $settings['theme_config_manager_feature_list'] = $featureList;
        }
        $logoList = $settings['theme_config_manager_logos'];
        if ($logoList) {
            $keys = array_keys($logoList);
            foreach ($keys as $key) {
                $logo = $logoList[$key];
                $logo["logo"] = $this->getFileUrl($logo["logo"]);
                $logoList[$key] = $logo;
            }
            $settings['theme_config_manager_logos'] = $logoList;
        }
        $appFeatureList = $settings['theme_config_manager_app_manager_features'];
        if ($appFeatureList) {
            $keys = array_keys($appFeatureList);
            foreach ($keys as $key) {
                $feature = $appFeatureList[$key];
                $feature["icon"] = $this->getFileUrl($feature["icon"]);
                $appFeatureList[$key] = $feature;
            }
            $settings['theme_config_manager_app_manager_features'] = $appFeatureList;
        }
        if ($settings["theme_config_manager_app_link_image"]) {
            $settings["theme_config_manager_app_link_image"] = $this->getFileUrl($settings["theme_config_manager_app_link_image"]);
        }
        if ($settings["theme_config_manager_app_manager_features_image"]) {
            $settings["theme_config_manager_app_manager_features_image"] = $this->getFileUrl($settings["theme_config_manager_app_manager_features_image"]);
        }
        if ($settings["theme_config_manager_app_manager_image"]) {
            $settings["theme_config_manager_app_manager_image"] = $this->getFileUrl($settings["theme_config_manager_app_manager_image"]);
        }
        if ($settings["theme_config_manager_tes_cus_image"]) {
            $settings["theme_config_manager_tes_cus_image"] = $this->getFileUrl($settings["theme_config_manager_tes_cus_image"]);
        }
        return response()->json($settings);
    }

    public function getContactConfig() {
        $contact_configs = getSettingsFromPage('contact');
        $contact_configs = collect($contact_configs);
        if ($contact_configs["theme_contact_headline_bg"]) {
            $contact_configs["theme_contact_headline_bg"] = $this->getFileUrl($contact_configs["theme_contact_headline_bg"]);
        }
        return response()->json($contact_configs);
    }

    public function getPageConfig()
    {
        $settings = [
            'theme_master_site_title' => 'Thế Giới Tóc',
            'theme_master_site_desc' => 'Thế Giới Tóc',
            'theme_master_quy_dinh' => false,
            'theme_master_chinh_sach' => false,
            'theme_master_copyright' => '© 2018 isalon.vn',
            'theme_master_mobile_app_android' => '#',
            'theme_master_mobile_app_ios' => '#',
            'theme_master_social_links' => [],
            'theme_home_popup_image' => '',
            'theme_home_popup_url' => '',
            'theme_home_popup_enabled' => false,
        ];
        $settings = getSettings($settings);

        $t = $settings['theme_master_chinh_sach'];
        if ($t) {
            if (isset($t['posts'])) {
                $tt = \Modules\ModHairWorld\Entities\PostTypes\News::find($t['posts']);
                if($tt){
                    $settings['theme_master_chinh_sach'] = $tt->getUrl();
                }
            }
        }
        $t = $settings['theme_master_quy_dinh'];
        if ($t) {
            if (isset($t['posts'])) {
                $tt = \Modules\ModHairWorld\Entities\PostTypes\News::find($t['posts']);
                if($tt){
                    $settings['theme_master_quy_dinh'] = $tt->getUrl();
                }
            }
        }
        $t = $settings['theme_home_popup_image'];
        if ($t) {
            $settings['theme_home_popup_image'] = $this->getFileUrl($settings['theme_home_popup_image']);
        }
        return response()->json($settings);
    }

    private function getFileUrl($id) {
        $ret = UploadedFile::find($id);
        if ($ret) {
            return $ret->getUrl();
        }
        return '';
    }
}