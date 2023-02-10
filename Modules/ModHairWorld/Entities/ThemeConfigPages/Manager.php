<?php

namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFontAwesome;
use App\Classes\FieldInput\FieldInputPost;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTextArea;
use Modules\ModHairWorld\Entities\PostTypes\News;

class Manager extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_manager';
    }

    protected function menuTitle(): string
    {
        return 'Đăng ký chủ salon';
    }

    protected function menuIcon(): string
    {
        return 'icon-users';
    }

    protected function menuOrder(): int
    {
        return 0;
    }

    protected function permissionTitle(): string
    {
        return 'Cấu hình giao diện trang đăng ký chủ salon';
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    public function getParentMenuSlug()
    {
        return 'theme_config';
    }

    protected function pageTitle(): string
    {
        return 'Cấu hình giao diện trang đăng ký chủ salon';
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
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
        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(
                'Block giới thiệu',
                [
                    new FieldInputText(
                        'theme_config_manager_intro_text_1',
                        $settings['theme_config_manager_intro_text_1'],
                        'Text 1',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputText(
                        'theme_config_manager_intro_text_2',
                        $settings['theme_config_manager_intro_text_2'],
                        'Text 2',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputText(
                        'theme_config_manager_intro_text_3',
                        $settings['theme_config_manager_intro_text_3'],
                        'Text 3',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                ]
            ),
            new FieldGroup(
                'Block Feature',
                [
                    new FieldInputText(
                        'theme_config_manager_feature_headline',
                        $settings['theme_config_manager_feature_headline'],
                        'Headline',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputTextArea(
                        'theme_config_manager_feature_headline_sub',
                        $settings['theme_config_manager_feature_headline_sub'],
                        'Headline content',
                        '',
                        false,
                        FieldInputTextArea::buildConfigs()
                    ),
                    new FieldInputRepeater(
                        'theme_config_manager_feature_list',
                        $settings['theme_config_manager_feature_list'],
                        'Feature List',
                        '',
                        false,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputText(
                                'title',
                                '',
                                'Tiêu đề',
                                '',
                                true,
                                FieldInputText::buildConfigs()
                            ),
                            new FieldInputFile(
                                'image',
                                '',
                                'Ảnh',
                                '',
                                true,
                                FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                            ),
                            new FieldInputTextArea(
                                'content',
                                '',
                                'Nội dung',
                                '',
                                true,
                                FieldInputTextArea::buildConfigs()
                            ),
                        ])
                    ),
                ]
            ),
            new FieldGroup(
                'Block app manager link',
                [
                    new FieldInputText(
                        'theme_config_manager_app_link_title',
                        $settings['theme_config_manager_app_link_title'],
                        'Tiêu đề',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputTextArea(
                        'theme_config_manager_app_link_desc',
                        $settings['theme_config_manager_app_link_desc'],
                        'Nội dung',
                        '',
                        false,
                        FieldInputTextArea::buildConfigs()
                    ),
                    new FieldInputFile(
                        'theme_config_manager_app_link_image',
                        $settings['theme_config_manager_app_link_image'],
                        'Ảnh bên phải',
                        '',
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                    ),
                ]
            ),
            new FieldGroup(
                'Block app manager intro',
                [
                    new FieldInputText(
                        'theme_config_manager_app_manager_title',
                        $settings['theme_config_manager_app_manager_title'],
                        'Tiêu đề',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputTextArea(
                        'theme_config_manager_app_manager_content',
                        $settings['theme_config_manager_app_manager_content'],
                        'Nội dung',
                        '',
                        false,
                        FieldInputTextArea::buildConfigs()
                    ),
                    new FieldInputFile(
                        'theme_config_manager_app_manager_image',
                        $settings['theme_config_manager_app_manager_image'],
                        'Ảnh bên dưới',
                        '',
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                    ),
                ]
            ),
            new FieldGroup(
                'Block app manager feature',
                [
                    new FieldInputFile(
                        'theme_config_manager_app_manager_features_image',
                        $settings['theme_config_manager_app_manager_features_image'],
                        'Ảnh chính giữa',
                        '',
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                    ),
                    new FieldInputRepeater(
                        'theme_config_manager_app_manager_features',
                        $settings['theme_config_manager_app_manager_features'],
                        'Chức năng nổi bật',
                        '',
                        false,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputText(
                                'title',
                                '',
                                'Tiêu đề',
                                '',
                                true,
                                FieldInputText::buildConfigs()
                            ),
                            new FieldInputFile(
                                'icon',
                                '',
                                'Icon',
                                '',
                                true,
                                FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                            ),
                            new FieldInputTextArea(
                                'content',
                                '',
                                'Nội dung',
                                '',
                                true,
                                FieldInputTextArea::buildConfigs()
                            ),
                        ])
                    ),
                ]
            ),
            new FieldGroup(
                'Block logo',
                [
                    new FieldInputRepeater(
                        'theme_config_manager_logos',
                        $settings['theme_config_manager_logos'],
                        'Các logo đối tác',
                        '',
                        false,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputFile(
                                'logo',
                                '',
                                'Logo',
                                '',
                                true,
                                FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                            ),
                            new FieldInputText(
                                'link',
                                '#',
                                'Liên kết',
                                '',
                                true,
                                FieldInputText::buildConfigs()
                            ),
                        ])
                    ),
                ]
            ),
            new FieldGroup(
                'Block ý kiến khách hàng',
                [
                    new FieldInputText(
                        'theme_config_manager_tes_title',
                        $settings['theme_config_manager_tes_title'],
                        'Tiêu đề khối',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputTextArea(
                        'theme_config_manager_tes_content',
                        $settings['theme_config_manager_tes_content'],
                        'Ý kiến khách',
                        '',
                        false,
                        FieldInputTextArea::buildConfigs()
                    ),
                    new FieldInputText(
                        'theme_config_manager_tes_cus_name',
                        $settings['theme_config_manager_tes_cus_name'],
                        'Tên khách',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputText(
                        'theme_config_manager_tes_cus_job',
                        $settings['theme_config_manager_tes_cus_job'],
                        'Nghề nghiệp chức vụ',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputFile(
                        'theme_config_manager_tes_cus_image',
                        $settings['theme_config_manager_tes_cus_image'],
                        'Ảnh khách hàng',
                        '',
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', ['theme_files'], ['image'])
                    ),
                ]
            ),
            new FieldGroup(
                'Block form liên hệ',
                [
                    new FieldInputText(
                        'theme_config_manager_form_title',
                        $settings['theme_config_manager_form_title'],
                        'Tiêu đề',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputTextArea(
                        'theme_config_manager_form_desc',
                        $settings['theme_config_manager_form_desc'],
                        'Nội dung',
                        '',
                        false,
                        FieldInputTextArea::buildConfigs()
                    ),
                ]
            )
        ];
    }
}