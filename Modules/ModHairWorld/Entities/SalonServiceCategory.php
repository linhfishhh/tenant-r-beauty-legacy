<?php

namespace Modules\ModHairWorld\Entities;

use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\PostType;
use App\Classes\PostTypeWithFieldInput;
use App\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonServiceCatDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonServiceCategory
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $title
 * @property int $cover_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceCategory whereUpdatedAt($value)
 * @property-read UploadedFile|null $cover
 * @property-read SalonService[]|Collection $services
 */
class SalonServiceCategory extends PostTypeWithFieldInput
{
//    protected $fillable = [];
//    protected $table = 'service_categories';
//    protected $dates = [
//        'created_at',
//        'updated_at'
//    ];
    public static function getIndexView($view_data = [])
    {
        return view(
        getThemeViewName('backend.pages.service_cats.index'),
            $view_data
        );
    }


    function getUrl()
    {
        return url('tim-kiem?cat[]='.$this->id);
    }

    function cover(){
        return $this->hasOne(UploadedFile::class,'id', 'cover_id');
    }

    public static function getFileCatIDS()
    {
        return [
            static::getFileCatID().'_cover' => __('Ảnh danh mục'),
        ];
    }

    protected $dispatchesEvents = [
        'deleted' => SalonServiceCatDeleted::class
    ];

    function services(){
        return $this->hasMany(SalonService::class,'category_id', 'id');
    }

    function salons(){
        return $this->hasManyThrough(Salon::class, SalonService::class,
            'category_id',
            'id',
            'id',
        'salon_id'
            )->groupBy('salons.id');
    }

    public static function taxonomies(): array
    {
        return [];
    }

    public static function menuTitle(): string
    {
        return 'Danh mục dịch vụ';
    }

    public static function menuIndexTitle(): string
    {
        return 'Danh mục dịch vụ';
    }

    public static function typeSlug(): string
    {
        return 'service-cat';
    }

    public static function singular(): string
    {
        return 'Danh mục';
    }

    public static function plural(): string
    {
        return 'Các danh mục';
    }

    public static function menuIcon(): string
    {
        return 'icon-folder';
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
        return 'service_categories';
    }

    public static function commentType(): string
    {
        return '';
    }

    public static function attachmentType(): string
    {
        return '';
    }

    public static function fieldGroups($model)
    {
        return [
            new FieldGroup('Thông tin mở rộng', [
                new FieldInputFile(
                    'cover_id',
                    $model?$model->cover_id:null,
                    'Ảnh đại diện',
                    '',
                    false,
                    FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh', [static::getFileCatID().'_cover'], ['image'])
                )
            ])
        ];
    }


}
