<?php
namespace Modules\ModHairWorld\Listeners\Theme;


use App\Events\ThemeIndexViewData;
use App\Http\Requests\FileUpload;
use App\UploadedFile;
use Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho;
use Modules\ModHairWorld\Entities\PostTypes\News;
use Modules\ModHairWorld\Entities\Salon;

class HomePageIndexData
{
    private function processHomeSlider($slider_configs){
        $image_ids = collect($slider_configs['theme_home_slider'])->map(function($item){
            return $item['image'];
        });
        $images = UploadedFile::whereIn('id', $image_ids)->get();
        $image_data = [];
        foreach ($images as $image){
            $image_data[$image->id] = $image->getUrl();
        }
        $rs = collect($slider_configs['theme_home_slider'])->map(function($item) use ($image_data){
            if(array_key_exists($item['image'], $image_data)){
                return [
                  'image' =>   $image_data[$item['image']],
                    'link' => $item['link']
                ];
            }
            else{
                return [
                    'image' =>   null,
                    'link' => $item['link']
                ];
            }
        })->filter(function ($item){
            return $item['image'];
        });
        return $rs;
    }

    function handle(ThemeIndexViewData $event){
        $configs = getSettingsFromPage('theme_config_home');
        $slider_configs = getSettingsFromPage('theme_config_home_slider');
        $slides = $this->processHomeSlider($slider_configs);
        $group_slides = [];
        if ($slider_configs['theme_home_slider_multi_display']) {
            $current_item = [];
            $key = -1;
            foreach ($slides as $slide) {
                $key++;
                if ($key % 3 === 0) {
                    $current_item[0][] = $slide;
                } else {
                    $current_item[1][] = $slide;
                }

                if ($key % 3 == 2 && $key + 1 < sizeof($slides)) {
                    $group_slides[] = $current_item;
                    $current_item = [];
                }
                if ($key + 1 == sizeof($slides)) {
                    $group_slides[] = $current_item;
                }
            }
        } else {
            foreach ($slides as $key => $slide) {
                $group_slides[] = [[$slide]];
            }
        }
        $event->data['home_slides'] = $group_slides;
        $event->data['home_slider_configs'] = [
            'timeout' => $slider_configs['theme_home_slider_speed'],
            'nav_speed' => $slider_configs['theme_home_slider_nav_speed'],
            'multi_display' => $slider_configs['theme_home_slider_multi_display']
        ];
        $configs = collect($configs);
        $event->data['page_configs'] = $configs;

        $banner_1_link = $configs->get('theme_home_banner_grid_1_link', '#');
        $event->data['banner_grid_1_link'] = $banner_1_link;


        $banner_2_link = $configs->get('theme_home_banner_grid_2_link', '#');
        $event->data['banner_grid_2_link'] = $banner_2_link;

        $banner_3_link = $configs->get('theme_home_banner_grid_3_link', '#');
        $event->data['banner_grid_3_link'] = $banner_3_link;

        $banner_4_link = $configs->get('theme_home_banner_grid_4_link', '#');
        $event->data['banner_grid_4_link'] = $banner_4_link;

        $banner_1 = $configs->get('theme_home_banner_grid_1', 0);
        $banner_2 = $configs->get('theme_home_banner_grid_2', 0);
        $banner_3 = $configs->get('theme_home_banner_grid_3', 0);
        $banner_4 = $configs->get('theme_home_banner_grid_4', 0);

        $banner_top_text_1 = $configs->get('theme_home_banner_grid_1_top_text', 0);
        $event->data['banner_top_text_1'] = $banner_top_text_1;
        $banner_top_text_2 = $configs->get('theme_home_banner_grid_2_top_text', 0);
        $event->data['banner_top_text_2'] = $banner_top_text_2;
        $banner_top_text_3 = $configs->get('theme_home_banner_grid_3_top_text', 0);
        $event->data['banner_top_text_3'] = $banner_top_text_3;
        $banner_top_text_4 = $configs->get('theme_home_banner_grid_4_top_text', 0);
        $event->data['banner_top_text_4'] = $banner_top_text_4;

        $banner_top_title_1 = $configs->get('theme_home_banner_grid_1_title', 0);
        $event->data['banner_top_title_1'] = $banner_top_title_1;
        $banner_top_title_2 = $configs->get('theme_home_banner_grid_2_title', 0);
        $event->data['banner_top_title_2'] = $banner_top_title_2;
        $banner_top_title_3 = $configs->get('theme_home_banner_grid_3_title', 0);
        $event->data['banner_top_title_3'] = $banner_top_title_3;
        $banner_top_title_4 = $configs->get('theme_home_banner_grid_4_title', 0);
        $event->data['banner_top_title_4'] = $banner_top_title_4;

        $banner_top_sub_title_1 = $configs->get('theme_home_banner_grid_1_sub_title', 0);
        $event->data['banner_top_sub_title_1'] = $banner_top_sub_title_1;
        $banner_top_sub_title_2 = $configs->get('theme_home_banner_grid_2_sub_title', 0);
        $event->data['banner_top_sub_title_2'] = $banner_top_sub_title_2;
        $banner_top_sub_title_3 = $configs->get('theme_home_banner_grid_3_sub_title', 0);
        $event->data['banner_top_sub_title_3'] = $banner_top_sub_title_3;
        $banner_top_sub_title_4 = $configs->get('theme_home_banner_grid_4_sub_title', 0);
        $event->data['banner_top_sub_title_4'] = $banner_top_sub_title_4;

        $banners = [
            '1' => $banner_1,
            '2' => $banner_2,
            '3' => $banner_3,
            '4' => $banner_4,
        ];
        $banner_images = [];
        /** @var UploadedFile[] $banner_rs */
        $banner_rs = UploadedFile::whereIn('id', array_values($banners))->get();
        foreach ($banner_rs as $banner_r){
            $url = $banner_r->getUrl();
            if(!$url){
                $url = getNoThumbnailUrl();
            }
            $banner_images[$banner_r->id] = $url;
        }

        if(isset($banner_images[$banner_1])){
            $banner_1 = $banner_images[$banner_1];
        }

        $event->data['banner_grid_1'] = $banner_1;

        if(isset($banner_images[$banner_2])){
            $banner_2 = $banner_images[$banner_2];
        }

        $event->data['banner_grid_2'] = $banner_2;

        if(isset($banner_images[$banner_3])){
            $banner_3 = $banner_images[$banner_3];
        }

        $event->data['banner_grid_3'] = $banner_3;

        if(isset($banner_images[$banner_4])){
            $banner_4 = $banner_images[$banner_4];
        }

        $event->data['banner_grid_4'] = $banner_4;


        $popular_cities_data = [];
        $popular_cities = $configs->get('theme_home_popular_cities');
        $popular_city_raw = [];
        foreach ($popular_cities as $city){
            $popular_city_raw[$city['city_id']] = $city['img'];
        }
        $popular_city_ids = array_keys($popular_city_raw);
        /** @var DiaPhuongTinhThanhPho[] $popular_cities_rs */
        $popular_cities_rs = DiaPhuongTinhThanhPho::select(['id', 'name'])->whereIn('id', $popular_city_ids)->get();
        $popular_cities_data_raw = [];
        foreach ($popular_cities_rs as $cities_r){
            $popular_cities_data_raw[$cities_r->id] = $cities_r->name;
        }
        /** @var UploadedFile[] $popular_city_img_rs */
        $popular_city_img_rs = UploadedFile::whereIn('id',array_values($popular_city_raw))->get();
        $popular_city_img_raw = [];
        foreach ($popular_city_img_rs as $city_img_r){
            $popular_city_img_raw[$city_img_r->id] = $city_img_r->getThumbnailUrl('medium',getNoThumbnailUrl());
        }
        $popular_city_count_raw = [];
        $salon_in_cities = Salon::selectRaw('tinh_thanh_pho_id, count(id) as total')->whereIn('tinh_thanh_pho_id', $popular_city_ids)
            ->groupBy('tinh_thanh_pho_id')->get();
        foreach ($salon_in_cities as $salon_in_city){
            $popular_city_count_raw[$salon_in_city['tinh_thanh_pho_id']] = $salon_in_city['total'];
        }
        foreach ($popular_cities as $city){
            if(isset($popular_cities_data_raw[$city['city_id']])){
                $city_title = $popular_cities_data_raw[$city['city_id']];
                $data = [
                    'id' => $city['city_id'],
                    'name' => $city_title
                ];
                $img = getNoThumbnailUrl();
                if(isset($popular_city_img_raw[$city['img']])){
                    $img = $popular_city_img_raw[$city['img']];
                }
                $data['img'] = $img;
                $data['count'] = 0;
                if(isset($popular_city_count_raw[$city['city_id']])){
                   $data['count'] = $popular_city_count_raw[$city['city_id']];
                }
                $popular_cities_data[] = $data;
            }
        }
        ;
        $event->data['popular_cities'] = $popular_cities_data;

        $latest_news = News::getPublicIndexQuery()
            ->with(['cover'])
            ->where('listable', 1)
            ->limit($configs->get('theme_home_news_limit', 3))
            ->get();
        $event->data['latest_news'] = $latest_news;
    }
}