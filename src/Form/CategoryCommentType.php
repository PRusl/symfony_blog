<?php

namespace App\Form;

use App\Entity\CategoryComment;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryCommentType extends ACommentType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryComment::class,
        ]);
    }
}
