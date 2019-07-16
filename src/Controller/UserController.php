<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Newsletter;

class UserController extends Controller
{
    public function newsletter(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('regsiter_newsletter', $request->request->get('register_newsletter_token'))) {
                $this->addFlash('danger', 'user.session_expired');
                return $this->redirectToRoute('index');
            }

            $email = $request->request->get('email');
            $locale = $request->request->get('locale');
            $regUrl = $request->request->get('regUrl');

            $newsletter = new Newsletter();
            $newsletter
                ->setEmail($email)
                ->setLocale($locale)
                ->setRegistrationUrl($regUrl);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletter);
            $em->flush();

            $this->addFlash('success', 'newsletter.registrated_successfully');
        }

        return $this->redirectToRoute('index');
    }
}
