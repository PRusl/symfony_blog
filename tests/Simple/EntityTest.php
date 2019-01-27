<?php

namespace App\Tests\Simple;

use App\Entity\AEntityComment;
use App\Entity\Article;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    /**
     * @dataProvider provideAttributes
     */
    public function testEntityAttributes($attribute, $class)
    {
        $this->assertClassHasAttribute($attribute, $class);
    }

    public function provideAttributes()
    {
        return [
            ['name', Category::class],
            ['description', Category::class],
            ['name', Article::class],
            ['content', Article::class],
            ['file', Article::class],
            ['author', AEntityComment::class],
            ['content', AEntityComment::class],
            ['created_at', AEntityComment::class],
        ];
    }
}
