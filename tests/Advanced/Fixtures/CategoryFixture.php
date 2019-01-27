<?php

namespace App\Tests\Advanced\Fixtures;

use App\Entity\Category;

class CategoryFixture extends AFixture implements IFixture
{
    /**
     * @return string Class name
     */
    public function getEntityClass(): string
    {
        return Category::class;
    }

    /**
     * @return string
     */
    public function getBasePageName(): string
    {
        return 'category';
    }

    /**
     * @return array
     */
    public function getRequiredFields(): array
    {
        return [
            'name',
        ];
    }

    /**
     * @return array
     */
    public function getOptionalFields(): array
    {
        return [
            'description',
        ];
    }
}