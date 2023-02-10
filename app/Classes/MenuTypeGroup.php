<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 26-Feb-18
 * Time: 14:45
 */

namespace App\Classes;


class MenuTypeGroup
{
    public $id;
    public $title;
    public $order;

    /**
     * MenuTypeGroup constructor.
     * @param $id
     * @param $title
     * @param $order
     */
    public function __construct($id, $title, $order = 0)
    {
        $this->id = $id;
        $this->title = $title;
        $this->order = $order;
    }
}