<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    public function contact(Request $request, EntityManagerInterface $em, \Swift_Mailer $mailer, ContainerInterface $container): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = [$container->getParameter('website.email') => $container->getParameter('website.name')];
            $message = (new \Swift_Message($contact->getSubject()))
                ->setFrom($address)
                ->setTo($address)
                ->setSubject($contact->getSubject())
                ->setContentType('text/html')
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        ['contact' => $contact]
                    ),
                    'text/html'
                )
                ->addPart(
                    $this->renderView(
                        'emails/contact.txt.twig',
                        ['contact' => $contact]
                    ),
                    'text/plain'
                );
            $mailer->send($message);

            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Votre message a été envoyé, merci!');

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
