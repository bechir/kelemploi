<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Application;
use App\Form\ApplicationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Company;

class ApplicationController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $app = new Application();
        $form = $this->createForm(ApplicationType::class, $app);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($app);

            $em->flush();
            $this->addFlash('success', 'Votre offre été créé !');

            return $this->redirectToRoute('application_show', [
                'slug' => $app->getSlug()
            ]);
        }

        return $this->render('employer/post-job.html.twig', [
            'form' => $form->createView(),
            'company' => null,
            'active' => 'post-job'
        ]);
    }

    public function edit(Request $request, Application $app): Response
    {
        $form = $this->createForm(ApplicationType::class, $app);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($app);
            $em->flush();

            $this->addFlash('success', "L'offre été modifié!");

            return $this->redirectToRoute('application_show', ['slug' => $app->getSlug()]);
        }

        return $this->render('application/edit.html.twig', [
            'form' => $form->createView(),
            '_app' => $app,
        ]);
    }

    public function show(Application $app): Response
    {
        if(!$app) {
            $this->addFlash('danger', "L'offre' n'existe pas.");
            return $this->redirectToRoute('index');
        }

        return $this->render('application/show.html.twig', [
            '_app' => $app,
            'company' => $app->getCompany()
        ]);
    }

    public function list(): Response
    {
        return $this->render('application/listing.html.twig', [
            'list' => $this->getDoctrine()->getRepository(Application::class)->findAll()
        ]);
    }

    public function delete(Request $request): Response
    {
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
