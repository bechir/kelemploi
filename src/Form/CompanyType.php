<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Region;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('address', TextType::class, ['required' => false])
            ->add('zip', TextType::class, ['required' => false])
            ->add('website', TextType::class, ['required' => false])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'form.job.region'
            ])
            // ->add('photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
