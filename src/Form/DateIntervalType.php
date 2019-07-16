<?php

namespace App\Form;

use App\Entity\DateInterval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\CallbackTransformer;

class DateIntervalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', TextType::class, [
                'required' => false,
            ])
            ->add('end', TextType::class, [
                'required' => false,
            ])
        ;

        $builder->get('start')
            ->addModelTransformer(new CallbackTransformer(
                  function ($startAsDate) {
                      return $startAsDate ? $startAsDate->format('m/d/Y') : '';
                  },

                  function ($startAsString) {
                      if (empty($startAsString)) {
                          return null;
                      } else {
                          return \DateTime::createFromFormat('m/d/Y', $startAsString);
                      }
                  }
            ))
        ;

        $builder->get('end')
            ->addModelTransformer(new CallbackTransformer(
                  function ($endAsDate) {
                      return $endAsDate ? $endAsDate->format('m/d/Y') : '';
                  },

                  function ($endAsString) {
                      if (empty($endAsString)) {
                          return null;
                      } else {
                          return \DateTime::createFromFormat('m/d/Y', $endAsString);
                      }
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
