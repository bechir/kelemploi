<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserEditType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('accountType', AccountType::class, ['attr' => ['required' => false]])
            ->add('email', EmailType::class, ['attr' => ['required' => false]])
            ->add('city', CityType::class, ['attr' => ['required' => false]])
            ->add('country', CountryType::class, ['attr' => ['required' => false]])
            ->add('locale', ChoiceType::class, [
                'choices' => [
                    'app.locale.fr' => 'fr',
                    'app.locale.en' => 'en',
                    'app.locale.ar' => 'ar',
                ],
                'required' => false,
            ])

            ->remove('plainPassword');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
