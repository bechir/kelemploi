<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form;

use App\Entity\DateInterval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateIntervalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('end', TextType::class, [
                'required' => false,
            ])
        ;

        $builder->get('end')
            ->addModelTransformer(new CallbackTransformer(
                  function ($endAsDate) {
                      return $endAsDate ? $endAsDate->format('Y/m/d') : '';
                  },

                  function ($endAsString) {
                      if (empty($endAsString)) {
                          return null;
                      }

                      return \DateTime::createFromFormat('Y/m/d', $endAsString);
                  }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DateInterval::class,
        ]);
    }
}
