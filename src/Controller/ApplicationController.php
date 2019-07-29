<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Event\ApplicationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\ApplicationEvents;

class ApplicationController extends AbstractController
{
    public function list(): Response
    {
        return $this->render('application/listing.html.twig', [
            'list' => $this->getDoctrine()->getRepository(Application::class)->findAll(),
        ]);
    }

    public function listCategory(Request $request, $slug): Response
    {
        $list = [];

        return $this->render('application/listing.html.twig', [
            'list' => $list,
        ]);
    }

    public function listFilter(Request $request): Response
    {
        $list = [];

        return $this->render('application/listing.html.twig', [
            'list' => $list,
        ]);
    }

    /**
     * @IsGranted("ROLE_EMPLOYER")
     */
    public function create(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $em = $this->getDoctrine()->getManager();
        $app = new Application();
        $form = $this->createForm(ApplicationType::class, $app);

        $user = $this->getUser();

        if ($user->haveCompany()) {
            $form->remove('company');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $app->setInterlocutor($user);

            if ($user->haveCompany()) {
                $app->setCompany($user->getCompany());
            } else {
                $app->getCompany()->addOwner($user);
            }

            $em->persist($app);

            $em->flush();

            $event = new ApplicationEvent($app);
            $dispatcher->dispatch(ApplicationEvents::APPLICATION_CREATED, $event);

            $this->addFlash('success', 'Votre offre été créé !');

            return $this->redirectToRoute('application_show', [
                'slug' => $app->getSlug(),
            ]);
        }

        return $this->render('company/post-job.html.twig', [
            'form' => $form->createView(),
            'company' => $user->getCompany(),
            'active' => 'post-job',
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER").
     */
    public function edit(Request $request, Application $app): Response
    {
        $form = $this->createForm(ApplicationType::class, $app);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($app);
            $em->flush();

            $this->addFlash('success', 'job.edit_sucess');

            return $this->redirectToRoute('application_show', ['slug' => $app->getSlug()]);
        }

        return $this->render('application/edit.html.twig', [
            'form' => $form->createView(),
            '_app' => $app,
        ]);
    }

    public function show(Application $app, EventDispatcherInterface $dispatcher): Response
    {
        if (!$app) {
            $this->addFlash('danger', "L'offre' n'existe pas.");

            return $this->redirectToRoute('index');
        }

        $event = new ApplicationEvent($app);
        $dispatcher->dispatch(ApplicationEvents::APPLICATION_VIEWED, $event);

        return $this->render('application/show.html.twig', [
            '_app' => $app,
            'company' => $app->getCompany(),
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER").
     */
    public function delete(Request $request, Application $app, EventDispatcherInterface $dispatcher): Response
    {
        if($app) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($app);
            $em->flush();

            $event = new ApplicationEvent($app);
            $dispatcher->dispatch(ApplicationEvents::APPLICATION_DELETED, $event);

            $this->addFlash('success', 'job.delete_success');
        }

        return $this->render('application/delete.html.twig');
    }

    public function traineeship(Request $request): Response
    {
        return $this->render('application/traineeship.html.twig');
    }

    public function alternance(Request $request): Response
    {
        return $this->render('application/alternance.html.twig');
    }

    public function ftc(Request $request): Response
    {
        return $this->render('application/ftc.html.twig');
    }

    public function oec(Request $request): Response
    {
        return $this->render('application/oec.html.twig');
    }
}
