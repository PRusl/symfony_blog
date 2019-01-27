<?php

namespace App\Tests\Advanced\Fixtures;


class Pages
{
    protected $list = '';
    protected $new = '';
    protected $show = '';
    protected $edit = '';
    protected $delete = '';

    /**
     * @return string
     */
    public function getList(): string
    {
        return $this->list;
    }

    /**
     * @param string $list
     * @return Pages
     */
    public function setList(string $list): Pages
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return string
     */
    public function getNew(): string
    {
        return $this->new;
    }

    /**
     * @param string $new
     * @return Pages
     */
    public function setNew(string $new): Pages
    {
        $this->new = $new;
        return $this;
    }

    /**
     * @return string
     */
    public function getShow(): string
    {
        return $this->show;
    }

    /**
     * @param string $show
     * @return Pages
     */
    public function setShow(string $show): Pages
    {
        $this->show = $show;
        return $this;
    }

    /**
     * @return string
     */
    public function getEdit(): string
    {
        return $this->edit;
    }

    /**
     * @param string $edit
     * @return Pages
     */
    public function setEdit(string $edit): Pages
    {
        $this->edit = $edit;
        return $this;
    }

    /**
     * @return string
     */
    public function getDelete(): string
    {
        return $this->delete;
    }

    /**
     * @param string $delete
     * @return Pages
     */
    public function setDelete(string $delete): Pages
    {
        $this->delete = $delete;
        return $this;
    }
}