<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 11-Apr-18
 * Time: 10:48
 */

namespace App\Classes;


class BackendDashBoardWidget
{
    private $id;
    private $view;
    private $view_data;
    private $permissions;
    private $has_one_permission;
    private $order;

    /**
     * BackendDashBoardWidget constructor.
     * @param string $id
     * @param string $view
     * @param array $view_data
     * @param array $permissions
     * @param bool $has_one_permission
     * @param int $order
     */
    public function __construct($id, $view, $view_data, $permissions, $has_one_permission, $order = 0)
    {
        $this->id = $id;
        $this->view = $view;
        $this->view_data = $view_data;
        $this->permissions = $permissions;
        $this->has_one_permission = $has_one_permission;
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return string
     */
    public function getViewData()
    {
        return $this->view_data;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @return bool
     */
    public function getHasOnePermission()
    {
        return $this->has_one_permission;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }


}