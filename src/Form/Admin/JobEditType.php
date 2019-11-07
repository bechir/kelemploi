<?php

/*
 * This file is part of the Beta application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Form\Admin;

use App\Entity\Company;
use App\Entity\Job;
use App\Form\JobType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobEditType extends JobType
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

        $job = $options['job'];

        $builder->add('linkedTo', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'n/a',
                'choice_translation_domain' => false,
                'query_builder' => function (EntityRepository $er) use ($job) {
                    $qb = $er->createQueryBuilder('j')
                        ->where('j.linkedTo is null')
                        ->orderBy('j.id', 'DESC');
                    if ($job) {
                        $qb->andWhere('j != :job')
                        ->setParameter('job', $job);
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
            'data_class' => Job::class,
            'job' => null,
        ]);
    }
}
