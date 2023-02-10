<?php

namespace Modules\ModHairWorld\Http\Controllers\api;

use App\Events\ThemeIndexViewData;
use App\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $slider_configs = getSettingsFromPage('theme_config_home_slider');
        $slides = $this->processHomeSlider($slider_configs);
        $banners = [];
        foreach ($slides as $slide) {
            $banners[] = $slide;

        }
        return response()->json($banners);
    }

    public function getBannerGird()
    {
        // $rs = array();
        // $configs = getSettingsFromPage('theme_config_home');
        // $configs = collect($configs);

        // $banner_1_link = $configs->get('theme_home_banner_grid_1_link', '#');
        // $theme_home_banner_grid_1_sub_title = $configs->get('theme_home_banner_grid_1_sub_title', '#');
        // $theme_home_banner_grid_1_title = $configs->get('theme_home_banner_grid_1_title', '#');
        // $theme_home_banner_grid_1_top_text = $configs->get('theme_home_banner_grid_1_top_text', '#');

        // $data['banner1']['link'] = $banner_1_link;
        // $data['banner1']['text'] = $theme_home_banner_grid_1_top_text; 
        // $data['banner1']['title'] = $theme_home_banner_grid_1_title;
        // $data['banner1']['sub_title'] = $theme_home_banner_grid_1_sub_title;
        // array_push($rs, $data['banner1']);


        // $banner_2_link = $configs->get('theme_home_banner_grid_2_link', '#');
        // $theme_home_banner_grid_2_top_text = $configs->get('theme_home_banner_grid_2_top_text', '#');
        // $data['banner2']['link'] = $banner_2_link;
        // $data['banner2']['text'] = $theme_home_banner_grid_2_top_text;
        // $theme_home_banner_grid_2_title = $configs->get('theme_home_banner_grid_2_title', '#');
        // $data['banner2']['title'] = $theme_home_banner_grid_2_title;
        // $theme_home_banner_grid_2_sub_title = $configs->get('theme_home_banner_grid_2_sub_title', '#');
        // $data['banner2']['sub_title'] = $theme_home_banner_grid_2_sub_title;

        // array_push($rs, $data['banner2']);

        // $banner_3_link = $configs->get('theme_home_banner_grid_3_link', '#');
        // $theme_home_banner_grid_3_top_text = $configs->get('theme_home_banner_grid_3_top_text', '#');
        // $data['banner3']['link'] = $banner_3_link;
        // $data['banner3']['text'] = $theme_home_banner_grid_3_top_text;
        // $theme_home_banner_grid_3_title = $configs->get('theme_home_banner_grid_3_title', '#');
        // $data['banner3']['title'] = $theme_home_banner_grid_3_title;
        // $theme_home_banner_grid_3_sub_title = $configs->get('theme_home_banner_grid_3_sub_title', '#');
        // $data['banner3']['sub_title'] = $theme_home_banner_grid_3_sub_title;

        // array_push($rs, $data['banner3']);

        // $banner_4_link = $configs->get('theme_home_banner_grid_4_link', '#');
        // $theme_home_banner_grid_4_top_text = $configs->get('theme_home_banner_grid_4_top_text', '#');
        // $data['banner4']['link'] = $banner_4_link;
        // $data['banner4']['text'] = $theme_home_banner_grid_4_top_text;
        // $theme_home_banner_grid_4_title = $configs->get('theme_home_banner_grid_4_title', '#');
        // $data['banner4']['title'] = $theme_home_banner_grid_4_title;
        // $theme_home_banner_grid_4_sub_title = $configs->get('theme_home_banner_grid_4_sub_title', '#');
        // $data['banner4']['sub_title'] = $theme_home_banner_grid_4_sub_title;
        // array_push($rs, $data['banner4']);
        // $banner_1 = $configs->get('theme_home_banner_grid_1', 0);
        // $banner_2 = $configs->get('theme_home_banner_grid_2', 0);
        // $banner_3 = $configs->get('theme_home_banner_grid_3', 0);
        // $banner_4 = $configs->get('theme_home_banner_grid_4', 0);

        // $banners = [
        //     '1' => $banner_1,
        //     '2' => $banner_2,
        //     '3' => $banner_3,
        //     '4' => $banner_4,
        // ];


        // $banner_rs = UploadedFile::whereIn('id', array_values($banners))->get();
        // foreach ($banner_rs as $key => $banner_r){
        //     $url = $banner_r->getUrl();
        //     if(!$url){
        //         $url = getNoThumbnailUrl();
        //     }
        //     $rs[($key)]['image'] = $url;
        // }
     

    
        $settings = [
            'theme_home_slider_mobile' => [],
        ];
        $settings = getSettings($settings);
        $banners = array();
        foreach ($settings['theme_home_slider_mobile'] as $key => $value) {
            $images = UploadedFile::where('id', $value['image'])->first();
            array_push($banners, [ 
                'image' => $images->getUrl(),
                'text' => null,
                'title' => null,
                'sub_title' => null,
                'link' => $value['link']
            ]);
        }
        return response()->json($banners);
}


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('modhairworld::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('modhairworld::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('modhairworld::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    private function processHomeSlider($slider_configs)
    {
        $image_ids = collect($slider_configs['theme_home_slider'])->map(function ($item) {
            return $item['image'];
        });
        $images = UploadedFile::whereIn('id', $image_ids)->get();
        $image_data = [];
        foreach ($images as $image) {
            $image_data[$image->id] = $image->getUrl();
        }
        $rs = collect($slider_configs['theme_home_slider'])->map(function ($item) use ($image_data) {
            if (array_key_exists($item['image'], $image_data)) {
                return [
                    'image' => $image_data[$item['image']],
                    'link' => $item['link'],
                    'banner_type' => $item['banner_type']
                ];
            } else {
                return [
                    'image' => null,
                    'link' => $item['link'],
                    'banner_type' => $item['banner_type']
                ];
            }
        })->filter(function ($item) {
            return $item['image'];
        });
        return $rs;
    }
}
