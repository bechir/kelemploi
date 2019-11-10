<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function about(Request $request): Response
    {
        return $this->render('default/about.html.twig', [
            'stats' => $this->getStats(),
        ]);
    }

    public function privacy(Request $request): Response
    {
        return $this->render('default/privacy.html.twig');
    }

    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $message = (new \Swift_Message($this->getParameter('website.name') . ' - [Contact]'))
                ->setFrom($contact->getEmail())
                ->setTo($this->getParameter('website.email'))
                ->setSubject($contact->getSubject())
                ->setContentType('text/html')
                ->setBody(
                    $this->renderView(
                          'emails/contact.html.twig',
                          ['user' => $contact]
                    ),
                    'text/html'
                )
                ->addPart(
                    $this->renderView(
                        'emails/contact.txt.twig',
                        ['user' => $contact]
                    ),
                    'text/plain'
                );
            $mailer->send($message);

            $this->addFlash('success', 'contact.message_sent');

            return $this->redirectToRoute('index');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function helpFAQ(Request $request): Response
    {
        return $this->render('default/helpFAQ.html.twig');
    }

    public function sellFast(Request $request): Response
    {
        return $this->render('default/sellFast.html.twig');
    }

    public function bannerAds(Request $request): Response
    {
        return $this->render('default/bannerAds.html.twig');
    }

    public function promoteAds(Request $request): Response
    {
        return $this->render('default/promoteAds.html.twig');
    }

    public function termsCondtions(Request $request): Response
    {
        return $this->render('default/termsCondtions.html.twig');
    }

    public function howItWork(): Response
    {
        return $this->render('default/how-it-work.html.twig');
    }

    public function getStats()
    {
        return [
            'estabCount' => 33,
            'purchaseCount' => 2104,
        ];
    }

    public function pricing(): Response
    {
        return $this->render('default/pricing.html.twig');
    }

    public function newsletter(Request $request): JsonResponse
    {
        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('regsiter_newsletter', $request->request->get('register_newsletter_token'))) {
                $this->addFlash('danger', 'user.session_expired');

                return $this->redirectToRoute('index');
            }

            $email = $request->request->get('email');

            $newsletter = new Newsletter();
            $newsletter->setEmail($email);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletter);
            $em->flush();
        }

        return new JsonResponse('success');
    }
}
