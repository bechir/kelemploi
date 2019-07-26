<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Civility;
use App\Entity\Region;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('firstName', TextType::class, ['required' => false])
            ->add('lastName', TextType::class, ['required' => false])
            ->add('phoneNumber', TextType::class, ['required' => false])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name'
            ])
            ->add('civility', EntityType::class, [
                'class' => Civility::class,
                'choice_label' => 'name'
            ])
            ->add('about', TextareaType::class, ['required' => false])
            // ->add('photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
