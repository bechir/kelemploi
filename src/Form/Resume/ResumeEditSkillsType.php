<?php

namespace App\Form\Resume;

use App\Entity\Resume;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResumeEditSkillsType extends ResumeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('about')
            ->remove('cv')
            ->remove('title')
            ->remove('fullName')
            ->remove('proSkills')
            ->remove('studyLevel')
            ->remove('educations')
            ->remove('jobCategory')
            ->remove('workExperiences')
            ->remove('experienceLevel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }

    public function getName()
    {
        return 'resume_edit_skills';
    }
}
