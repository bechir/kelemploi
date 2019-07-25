<?php

namespace App\Form;

use App\Entity\ {
    Application,
    StudyLevel,
    ContractType,
    JobCategory,
    Region,
    Language,
Experience
};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbCandidatesToRecruit', TextType::class, [
                'required' => false
            ])
            ->add('jobTitle', TextType::class)
            ->add('jobDescription', TextareaType::class)
            ->add('company', CompanyType::class)
            ->add('salary', TextType::class, ['required' => false])
            // ->add('workTime', TextType::class, ['required' => false])
            ->add('postCategory', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'form.job.category'
            ])
            ->add('dates', DateIntervalType::class, ['required' => false])
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'name',
                'multiple' => false,
            ])
            ->add('minStudyLevel', EntityType::class, [
                'class' => StudyLevel::class,
                'choice_label' => 'level',
                'required' => false,
                'placeholder' => 'form.job.optionnal_level'
            ])
            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'name',
                'required' => false,
                'choice_translation_domain' => true,
                'placeholder' => 'form.job.optionnal_xp'
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
