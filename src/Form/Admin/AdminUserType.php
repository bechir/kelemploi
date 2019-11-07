<?php

/*
 * This file is part of the Beta application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form\Admin;

use App\Entity\UserRole;
use App\Form\UserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('fullname', TextType::class)
            ->add('roles', EntityType::class, [
                'class' => UserRole::class,
                'choice_label' => 'type',
                'choice_translation_domain' => true,
            ])
        ;
    }
}
