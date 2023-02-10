<?php

namespace Modules\ModHairWorld\Entities\AdminConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput;
use App\Classes\FieldInput\FieldInputBoolean;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTouchSpin;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonServiceCategory;

class PromoCampaignConfigs extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'promo_configs';
    }

    public function getParentMenuSlug()
    {
        return 'promo';
    }

    protected function menuTitle(): string
    {
        return 'Cấu hình campaign';
    }

    protected function menuIcon(): string
    {
        return 'icon-coins';
    }

    protected function menuOrder(): int
    {
       return 0;
    }

    protected function permissionTitle(): string
    {
        return __('Quản lý Cấu hình campaign');
    }

    protected function permissionOrder(): int
    {
        return 1;
    }

    protected function pageTitle(): string
    {
        return __('Cấu hình campaign');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $setting = [
            'promo_cats' => null,
            'promo_enable' => false,
            'promo_percent' => 10,
            'promo_date_range' => null,
            'promo_limit' => 20,
            'promo_days' => [],
            'promo_count_status' => [
                SalonOrder::_CHO_THUC_HIEN_.'',
            ],
        ];
        $setting = getSettings($setting);
        $cat_data = SalonServiceCategory::getQuery()->orderBy('ordering')->get(['id', 'title']);
        $cats = [];
        foreach ($cat_data as $cat){
            $cats[$cat->id] = $cat->title;
        }
        $days = [
            '1' => 'Thứ 2',
            '2' => 'Thứ 3',
            '3' => 'Thứ 4',
            '4' => 'Thứ 5',
            '5' => 'Thứ 6',
            '6' => 'Thứ 7',
            '7' => 'Chủ nhật',
        ];
        return [
            new FieldGroup(__('Lịch biểu'), [
                new FieldInputBoolean(
                    'promo_enable',
                    $setting['promo_enable'],
                    'Bật campaign',
                    '',
                    false
                ),
                new FieldInput\FieldInputDateRange(
                    'promo_date_range',
                    $setting['promo_date_range'],
                    'Thời gian khuyến mãi',
                    '',
                    false,
                    FieldInput\FieldInputDateRange::buildConfigs()
                ),
                new FieldInputSelect(
                    'promo_days',
                    $setting['promo_days'] ? $setting['promo_days'] : [],
                    'Ngày khuyến mãi',
                    '',
                    false,
                    FieldInputSelect::buildConfigs($days,  true)
                ),
            ]),
            new FieldGroup(__('Cấu hình campaign'), [
                new FieldInputSelect(
                    'promo_cats',
                     $setting['promo_cats'],
                    'Danh mục khuyến mãi',
                    '',
                    false,
                    FieldInputSelect::buildConfigs($cats,  false)
                ),
                new FieldInputTouchSpin(
                    'promo_percent',
                    $setting['promo_percent'],
                    'Phần trăm giảm',
                    '% trăm giảm giá này sẽ thay thế khuyến mãi hiện tại nếu có',
                    true,
                    [
                        'min' => 1,
                        'max' => 100,
                        'postfix'=> '%',
                        'step' => 1
                    ]
                ),
                new FieldInputTouchSpin(
                    'promo_limit',
                    $setting['promo_limit'],
                    'Giới hạn số đơn hàng',
                    '',
                    true,
                    [
                        'min' => 1,
                        'max' => 999999999999,
                        'postfix'=> 'Đơn hàng',
                        'step' => 1
                    ]
                ),
                new FieldInputSelect(
                    'promo_count_status',
                    $setting['promo_count_status'],
                    'Trạng thái tính',
                    'Chọn trạng thái được đếm khi giới hạn số lượng đơn hàng được khuyến mãi',
                    true,
                    FieldInputSelect::buildConfigs([
                        SalonOrder::_CHO_XU_LY_.'' => 'Chờ xử lý',
                        SalonOrder::_CHO_THANH_TOAN_.'' => 'Chờ thanh toán',
                        SalonOrder::_CHO_THUC_HIEN_.'' => 'Chờ thực hiện',
                        SalonOrder::_DA_HOAN_THANH_.'' => 'Đã hoàn thành',
                        SalonOrder::_HUY_DO_QUA_HAN_XU_LY.'' => 'Huỷ do quá hạn',
                        SalonOrder::_HUY_BOI_SALON_ .'' => 'Huỷ bởi salon',
                        SalonOrder::_HUY_BOI_KHACH_.'' => 'Huỷ bởi khách',
                        SalonOrder::_KHACH_KHONG_DEN_.'' => 'Huỷ do khách vắng',
                    ],  true)
                ),
            ]),
        ];
    }
}