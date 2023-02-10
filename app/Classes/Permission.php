<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 21-Jan-18
 * Time: 09:58
 */

namespace App\Classes;


class Permission {
    public $id;
    public $title;
    public $group;
    public $order;
    
    /**
     * Permission constructor.
     *
     * @param string $id
     * @param string $title
     * @param string $group
     * @param int    $order
     */
    public function __construct(
        $id,
        $title,
        $group,
        $order = 0
    ) {
        $this->id    = $id;
        $this->title = $title;
        $this->group = $group;
        $this->order = $order;
    }
    
}