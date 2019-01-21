<?php

namespace App\Entity;


interface IComment
{
    public function getComments();

    public function setComment(?AEntityComment $comment): self;

    public function removeComment(?AEntityComment $comment): self;
}