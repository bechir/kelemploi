<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form;

use App\Entity\Civility;
use App\Entity\Region;
use App\Entity\User;
use App\Entity\Gender;
use App\Entity\StudyLevel;
use App\Entity\Experience;
use App\Entity\JobCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => false])
            ->add('lastName', TextType::class, ['required' => false])
            ->add('phoneNumber', TextType::class, ['required' => false])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('jobCategory', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'required' => false,
                'choice_translation_domain' => true
            ])
            ->add('studyLevel', EntityType::class, [
                'class' => StudyLevel::class,
                'choice_label' => 'level',
                'required' => false,
                'choice_translation_domain' => true
            ])
            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'name',
                'required' => false,
                'choice_translation_domain' => true
            ])
            ->add('age', IntegerType::class, [
                'required' => false
            ])
            ->add('civility', EntityType::class, [
                'class' => Civility::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('about', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'form.about_user.placeholder',
                ],
                'required' => false,
                'translation_domain' => 'user',
            ])
            ->add('avatar', UserAvatarType::class, ['required' => false])
            // ->add('skills', CollectionType::class, [
            //
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
