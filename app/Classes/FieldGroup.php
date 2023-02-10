<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 15:43
 */

namespace App\Classes;


class FieldGroup
{
    private $title;
    private $fields;
    private $horizontal;
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array|FieldInput[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return bool
     */
    public function getHorizontal()
    {
        return $this->horizontal;
    }

    /**
     * FieldGroup constructor.
     * @param string $title
     * @param array $fields
     * @param bool $horizontal
     */
    public function __construct($title, $fields = [], $horizontal = true)
    {
        $this->title = $title;
        $this->fields = $fields;
        $this->horizontal = $horizontal;
    }
}