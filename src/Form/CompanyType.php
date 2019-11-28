<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form;

use App\Entity\Company;
use App\Entity\Industry;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'form.about_company.placeholder',
                ],
                'translation_domain' => 'company',
            ])
            ->add('address', TextType::class, ['required' => false])
            ->add('email', TextType::class, ['required' => false])
            ->add('website', TextType::class, ['required' => false])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'form.job.region',
            ])->add('industry', EntityType::class, [
                'class' => Industry::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Secteur d\'activité',
            ])
            ->add('photo', CompanyPhotoType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
