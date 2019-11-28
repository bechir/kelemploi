<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/settings/testimonials")
 */
class SettingTestimonialController extends AbstractController
{
    /**
     * @Route("", name="admin_settings_testimonials")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/settings/testimonials/index.html.twig', [
            'active' => 'testimonials',
            'testimonials' => $em->getRepository(Testimonial::class)->findAll(),
        ]);
    }

    /**
     * @Route("/disable", name="admin_settings_testimonial_disable", methods={"POST"})
     */
    public function disable(Request $request): JsonResponse
    {
        return $this->setEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_settings_testimonial_enable", methods={"POST"})
     */
    public function enable(Request $request): JsonResponse
    {
        return $this->setEnabled($request, true);
    }

    /**
     * @Route("/delete/{id}", name="admin_settings_testimonial_delete")
     */
    public function delete(Testimonial $testimonial, EntityManagerInterface $em)
    {
        if (!$testimonial) {
            $this->addFlash('success', 'Le témoignage est introuvable.');
        } else {
            $em->remove($testimonial);
            $em->flush();
            $this->addFlash('success', 'Le témoignage a été supprimé.');
        }

        return $this->redirectToRoute('admin_settings_testimonials');
    }

    private function setEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $testimonial = $em->find(Testimonial::class, $request->request->get('id'));

        if ($testimonial) {
            $testimonial->setIsEnabled($enabled);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}
