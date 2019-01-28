<?php

namespace App\Form;

use App\Entity\ArticleComment;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCommentType extends ACommentType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleComment::class,
        ]);
    }
}
