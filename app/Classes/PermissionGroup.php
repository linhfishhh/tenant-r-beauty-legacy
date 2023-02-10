<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 21-Jan-18
 * Time: 10:22
 */

namespace App\Classes;


class PermissionGroup {
    public $id;
    public $title;
    public $icon;
    public $order;

    /**
     * PermissionGroup constructor.
     *
     * @param string $id
     * @param string $title
     * @param string $icon
     * @param int $order
     */
    public function __construct(
        $id,
        $title,
        $icon,
        $order = 0
    ) {
        $this->id    = $id;
        $this->title = $title;
        $this->icon = $icon;
        $this->order = $order;
    }
}