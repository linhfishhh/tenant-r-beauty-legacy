<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 21-Jan-18
 * Time: 09:34
 */

namespace App\Classes;


class BackendMenuItem {
    public $id;
    public $title;
    public $parent;
    public $route;
    public $icon;
    public $permissions;
    public $has_one_permission;
    public $order;
    
    /**
     * BackendMenuItem constructor.
     *
     * @param string       $id
     * @param string       $title
     * @param string|false $parent
     * @param string|false|array $route
     * @param string       $icon
     * @param array|false  $permissions
     * @param bool         $has_one_permission
     * @param int          $order
     */
    public function __construct(
        $id,
        $title,
        $parent,
        $route,
        $icon,
        $permissions = [],
        $has_one_permission = false,
        $order = 0
    ) {
        $this->id                 = $id;
        $this->title              = $title;
        $this->parent             = $parent;
        $this->route              = $route;
        $this->icon               = $icon;
        $this->permissions        = $permissions;
        $this->has_one_permission = $has_one_permission;
        $this->order              = $order;
    }
    
}