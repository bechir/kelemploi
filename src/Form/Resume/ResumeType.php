<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form\Resume;

use App\Entity\Experience;
use App\Entity\JobCategory;
use App\Entity\Resume;
use App\Entity\StudyLevel;
use App\Form\CVFileType;
use App\Form\Type\SkillsInputType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResumeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
            ])
            ->add('fullName', TextType::class, [
                'required' => false,
            ])
            ->add('about', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'form.about_user.placeholder',
                ],
                'required' => false,
                'translation_domain' => 'user',
            ])
            ->add('jobCategory', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'form.job.category',
            ])
            ->add('studyLevel', EntityType::class, [
                'class' => StudyLevel::class,
                'choice_label' => 'level',
                'required' => false,
                'choice_translation_domain' => true,
                'placeholder' => 'form.job.optionnal_level',
            ])
            ->add('experienceLevel', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'name',
                'required' => false,
                'choice_translation_domain' => true,
                'placeholder' => 'form.job.optionnal_xp',
            ])
            ->add('skills', SkillsInputType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'form.job.skills'],
            ])
            ->add('cv', CVFileType::class, ['required' => false])
            ->add('educations', CollectionType::class, [
                'entry_type' => EducationType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
            ->add('workExperiences', CollectionType::class, [
                'entry_type' => WorkExperienceType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
            ->add('proSkills', CollectionType::class, [
                'entry_type' => ProfessionalSkillType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
            ])
            // ->add('portfolio')
            // ->add('socialProfiles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
