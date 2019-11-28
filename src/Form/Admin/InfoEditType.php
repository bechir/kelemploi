<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form\Admin;

use App\Entity\Company;
use App\Entity\Info;
use App\Form\InfoType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoEditType extends InfoType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('createdAt', TextType::class, ['required' => false]);

        $info = $options['info'];

        $builder->add('linkedTo', EntityType::class, [
                'class' => Info::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'n/a',
                'choice_translation_domain' => false,
                'query_builder' => function (EntityRepository $er) use ($info) {
                    $qb = $er->createQueryBuilder('i')
                        ->where('i.linkedTo is null')
                        ->orderBy('i.id', 'DESC');
                    if ($info) {
                        $qb->andWhere('i != :info')
                        ->setParameter('info', $info);
                    }

                    return $qb;
                },
            ]);

        $builder->get('createdAt')
            ->addModelTransformer(new CallbackTransformer(
                function ($createdAtAsDate) {
                    return $createdAtAsDate ? $createdAtAsDate->format('d/m/Y') : '';
                },

                function ($createdAtAsString) {
                    if (empty($createdAtAsString)) {
                        return null;
                    }

                    return \DateTime::createFromFormat('d/m/Y', $createdAtAsString);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Info::class,
            'info' => null,
        ]);
    }
}
