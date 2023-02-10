<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Modules\ModFAQ\Entities\FAQ;

class FAQController extends Controller
{
    function faqs(){
        $rs = FAQ::all()->map(function (FAQ $faq){
            return [
                'content' => $faq->title,
                'answer' => $faq->answer
            ];
        });
        return \Response::json($rs);
    }
}