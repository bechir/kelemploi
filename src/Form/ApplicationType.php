<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form;

use App\Entity\Application;
use App\Entity\ContractType;
use App\Entity\Experience;
use App\Entity\JobCategory;
use App\Entity\JobGender;
use App\Entity\Language;
use App\Entity\StudyLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbCandidatesToRecruit', TextType::class, ['required' => false])
            ->add('jobTitle', TextType::class)
            ->add('jobDescription', TextareaType::class)
            ->add('company', CompanyType::class, ['required' => false])
            ->add('salary', TextType::class, ['required' => false])
            // ->add('workTime', TextType::class, ['required' => false])
            ->add('postCategory', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'translation_domain' => 'jobs-categories',
                'placeholder' => 'form.job.category',
            ])
            ->add('dates', DateIntervalType::class, ['required' => false])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'form.job.contract_type',
                'choice_translation_domain' => true,
                'multiple' => false,
            ])
            ->add('minStudyLevel', EntityType::class, [
                'class' => StudyLevel::class,
                'choice_label' => 'level',
                'required' => false,
                'choice_translation_domain' => true,
                'placeholder' => 'form.job.optionnal_level',
            ])
            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'name',
                'required' => false,
                'choice_translation_domain' => true,
                'placeholder' => 'form.job.optionnal_xp',
            ])
            ->add('benefits', TextareaType::class, ['required' => false])
            ->add('tools', TextareaType::class, ['required' => false])
            ->add('responsibilities', TextareaType::class, ['required' => false])
            ->add('gender', EntityType::class, [
                'class' => JobGender::class,
                'choice_label' => 'name',
                'choice_translation_domain' => true,
            ])
            // ->add('requiredLanguages', EntityType::class, [
            //     'class' => Language::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => true,
            //     'required' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
