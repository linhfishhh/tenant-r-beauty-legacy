<?php

namespace App\Trails;

use App\Classes\Meta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

trait ModelMeta
{
    public function metas(){
        if(!isset($this->meta_class)){
            return null;
        }
        $meta_class = $this->meta_class;
        return $this->hasMany($meta_class,'target_id', 'id');
    }

    public function getMetas(){
        $rs = [];
        /** @var Meta[]|Collection $metas */
        $metas = $this->metas;
        foreach ($metas as $meta){
            $rs[$meta->name] = json_decode($meta->value);
        }
        return $rs;
    }

    public function getMeta($meta_name, $default = null){
        if(!isset($this->meta_class)){
            return null;
        }
        /** @var Meta $meta_class */
        $meta_class = $this->meta_class;
        $meta = $meta_class::where('target_id', '=',$this->id)->where('name', '=', $meta_name)->first('value');
        if(!$meta){
            return $default;
        }

        return json_decode($meta->value);
    }

    public function setMeta($meta_name, $value){
        if(!isset($this->meta_class)){
            return false;
        }
        /** @var Meta $meta_class */
        $meta_class = $this->meta_class;
        /** @var Builder $meta */
        $meta = $this->metas();
        $meta = $meta_class::where('target_id', '=', $this->id)->where('name', '=', $meta_name)->first();
        /** @var Meta $meta */
        if(!$meta){
            $meta = new $meta_class();
            $meta->name = $meta_name;
            $meta->target_id = $this->id;
        }
        $meta->value = json_encode($value);
        $meta->save();
    }
}