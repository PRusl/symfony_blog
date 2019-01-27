<?php

namespace App\Tests\Advanced\Fixtures;

interface IFixture
{
    /**
     * @return string Class name
     */
    public function getEntityClass(): string;

    /**
     * @return string Base page name
     */
    public function getBasePageName(): string;

    /**
     * @return array
     */
    public function getRequiredFields(): array;

    /**
     * @return array
     */
    public function getOptionalFields(): array;

    /**
     * @return Pages
     */
    public function getRequiredPages(): Pages;
}