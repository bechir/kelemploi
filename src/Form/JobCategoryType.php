<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form;

use App\Entity\JobCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobCategory::class,
        ]);
    }
}
