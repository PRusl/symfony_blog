<?php

namespace App\Tests\Advanced\Fixtures;

abstract class AFixture implements IFixture
{
    /**
     * @return Pages
     */
    public function getRequiredPages(): Pages
    {
        $pages = new Pages();

        $pages
            ->setList($this->getListPage())
            ->setNew($this->getNewPage())
            ->setShow($this->getShowPage())
            ->setEdit($this->getEditPage())
            ->setDelete($this->getDeletePage())
        ;

        return $pages;
    }

    protected function getListPage(): string
    {
        return $this->getBasePageName()
            ? '/' . $this->getBasePageName() . '/'
            : '';
    }

    protected function getNewPage(): string
    {
        return $this->getBasePageName()
            ? '/' . $this->getBasePageName() . '/new/'
            : '';
    }

    protected function getShowPage(): string
    {
        return $this->getBasePageName()
            ? '/' . $this->getBasePageName() . '/{id}/'
            : '';
    }

    protected function getEditPage(): string
    {
        return $this->getBasePageName()
            ? '/' . $this->getBasePageName() . '/{id}/edit/'
            : '';
    }

    protected function getDeletePage(): string
    {
        return $this->getBasePageName()
            ? '/' . $this->getBasePageName() . '/{id}/edit/'
            : '';
    }
}