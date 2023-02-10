<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/30/18
 * Time: 17:39
 */

namespace Modules\ModHairWorld\Entities;


use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\PostTypeWithFieldInput;
use App\UploadedFile;


/**
 * Modules\ModHairWorld\Entities\Badge
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property int $image_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereUpdatedAt($value)
 * @property-read UploadedFile|null $image
 */
class Badge extends PostTypeWithFieldInput
{

    function image(){
        return $this->hasOne(UploadedFile::class,'id', 'image_id');
    }

    public static function isPublic()
    {
        return false;
    }

    public static function getFileCatIDS()
    {
        return [
            static::getFileCatID().'_badge_image' => __('Ảnh lời khen'),
        ];
    }

    public static function taxonomies(): array
    {
        return [];
    }


    public static function fieldGroups($model)
    {
        $limit_owner = false;
        if(!me()->hasPermission(static::getManageGlobalPermissionID())){
            $limit_owner = true;
        }
        /** @var Badge $model */
        return [
            new FieldGroup(
                __('Chi tiết lời khen'),
                [
                    new FieldInputFile(
                        'image_id',
                        $model ? $model->image_id : '',
                        __('Ảnh đại diện'),
                        '',
                        false,
                        FieldInputFile::buildConfigs(
                            __('CHỌN ẢNH ĐẠI DIỆN'),
                            'Chọn ảnh đại diện',
                            [static::getFileCatID().'_badge_image' ],
                            ['image'],
                            $limit_owner?me()->id:0
                        )
                    ),
                ]
            ),
        ];
    }

    public static function menuTitle(): string
    {
        return __('Lời khen');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh sách lời khen');
    }

    public static function typeSlug(): string
    {
        return 'loi-khen';
    }

    public static function singular(): string
    {
        return 'Lời khen';
    }

    public static function plural(): string
    {
        return 'Các lời khen';
    }

    public static function menuIcon(): string
    {
        return 'icon-medal';
    }

    public static function menuIndexIcon(): string
    {
        return 'icon-list';
    }

    public static function menuOrder(): int
    {
        return -1;
    }

    static function dbTableName(): string
    {
        return 'badges';
    }

    public static function commentType(): string
    {
        return '';
    }

    public static function attachmentType(): string
    {
        return '';
    }
}