<?php

namespace App\Tests\Advanced\Fixtures;

use App\Entity\Article;

class ArticleFixture extends AFixture implements IFixture
{
    /**
     * @return string Class name
     */
    public function getEntityClass(): string
    {
        return Article::class;
    }

    /**
     * @return string
     */
    public function getBasePageName(): string
    {
        return 'article';
    }

    /**
     * @return array
    */
    public function getRequiredFields(): array
    {
        return [
            'name',
            'content',
        ];
    }

    /**
     * @return array
     */
    public function getOptionalFields(): array
    {
        return [
            'file',
        ];
    }
}