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
use App\Entity\Apply;
use App\Form\ApplyType;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Resume;
use App\Repository\ApplicationRepository;

class ApplicationController extends AbstractController
{
    public function list(Request $request, $page = 1, ApplicationRepository $appRepository): Response
    {
        $queries = $request->query;
        $search = [];

        $search['category'] = $queries->get('c');
        $search['region'] = strtolower($queries->get('region'));

        foreach ($search as $key => $value) {
            if(empty($value))
                unset($search[$key]);
        }

        $list = null;
    
        if (!empty($search)) {
            $list = $appRepository->findBySearchQuery($search, $page);

        } else {
            $list = $appRepository->getApps($page);
        }

        return $this->render('application/listing.html.twig', [
            'list' => $list
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
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        // Acces limité aux employeurs
        if(!$this->isGranted('ROLE_EMPLOYER'))  {
            return $this->render('candidate/access-limited.html.twig');
        }

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
     * IsGranted("ROLE_USER").
     */
    public function edit(Request $request, Application $app): Response
    {
        // Acces limité aux employeurs
        if(!$this->isGranted('ROLE_EMPLOYER'))  {
            return $this->render('candidate/access-limited.html.twig');
        }

        $form = $this->createForm(ApplicationType::class, $app)->remove('company');

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
            'company' => $app->getCompany(),
            'active' => ''
        ]);
    }

    public function show(Request $request, Application $app, UserInterface $user = null, EventDispatcherInterface $dispatcher, EntityManagerInterface $em): Response
    {
        if (!$app) {
            $this->addFlash('danger', "L'offre' n'existe pas.");

            return $this->redirectToRoute('index');
        }

        $event = new ApplicationEvent($app);
        $dispatcher->dispatch(ApplicationEvents::APPLICATION_VIEWED, $event);

        $apply = new Apply();
        $form = $this->createForm(ApplyType::class, $apply);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $apply
                ->setApplication($app)
                ->setCandidate($user);
            
            // $cvFile = $apply->getCvFile();

            // if($cvFile) {
            //     $resume = (new Resume())
            //         ->setCv($cvFile)
            //         ->setFullName($apply->getFullname())
            //         ->setUser($user);

            //     $em->persist($cvFile);
            //     $em->persist($resume);
            // }
            
            $app->addApply($apply);
            $user->addApply($apply);

            $em->persist($apply);
            $em->flush();

            $this->addFlash('success', 'Votre candidature a été envoyé, bonne chance!');

            return $this->redirectToRoute('application_show', ['slug' => $app->getSlug()]);
        }

        return $this->render('application/show.html.twig', [
            '_app' => $app,
            'user' => $user,
            'form' => $form->createView(),
            'company' => $app->getCompany(),
        ]);
    }

    /**
     * IsGranted("ROLE_USER").
     */
    public function delete(Request $request, Application $app, EventDispatcherInterface $dispatcher): Response
    {
        // Acces limité aux employeurs
        if(!$this->isGranted('ROLE_EMPLOYER'))  {
            return $this->render('candidate/access-limited.html.twig');
        }

        if($app && $request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('app.delete', $request->request->get('app_delete_token'))) {
                $this->addFlash('danger', 'app.invalid_csrf_token');
                return $this->redirectToRoute('application_show', ['slug' => $app->getSlug()]);
            }

            $em = $this->getDoctrine()->getManager();

            $em->remove($app);
            $em->flush();

            $event = new ApplicationEvent($app);
            $dispatcher->dispatch(ApplicationEvents::APPLICATION_DELETED, $event);

            $this->addFlash('success', 'job.delete_success');
        }
        
        return $this->redirectToRoute('company_dashboard');
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

    public function recentJobs(): Response
    {
        $list = $this->getDoctrine()->getRepository(Application::class)->getLatest();

        return $this->render('application/jobs-items.html.twig', [
            'list' => $list,
            'class' => 'col'
        ]);
    }

    public function similarJobs($category): Response
    {
        $list = $this->getDoctrine()->getRepository(Application::class)->findByJobCategory($category);

        return $this->render('application/similar-jobs.html.twig', [
            'list' => $list
        ]);
    }
}
