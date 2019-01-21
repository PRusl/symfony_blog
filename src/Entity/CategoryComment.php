<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryCommentRepository")
 */
class CategoryComment extends AEntityComment
{
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="comments")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;
}