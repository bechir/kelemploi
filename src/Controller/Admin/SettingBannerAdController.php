<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\BannerAd;
use App\Form\Admin\BannerAdType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/settings/banner-ad")
 */
class SettingBannerAdController extends AbstractController
{
    /**
     * @Route("", name="admin_settings_banner_ad")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/settings/banner-ad/index.html.twig', [
            'active' => 'banner-ad',
            'banners' => $em->getRepository(BannerAd::class)->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_settings_banner_ad_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $banner = new BannerAd();
        $form = $this->createForm(BannerAdType::class, $banner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($banner);
            $em->flush();

            $this->addFlash('success', 'La banière a été ajoouté avec succès.');

            return $this->redirectToRoute('admin_settings_banner_ad');
        }

        return $this->render('admin/settings/banner-ad/new.html.twig', [
            'active' => 'banner-ad',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_settings_banner_ad_delete")
     */
    public function delete(BannerAd $banner, EntityManagerInterface $em): Response
    {
        $em->remove($banner);
        $em->flush();
        $this->addFlash('success', 'La bannière a été supprimée.');

        return $this->redirectToRoute('admin_settings_banner_ad');
    }

    /**
     * @Route("/disable", name="admin_settings_banner_ad_disable", methods={"POST"})
     */
    public function disable(Request $request): JsonResponse
    {
        return $this->setEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_settings_banner_ad_enable", methods={"POST"})
     */
    public function enable(Request $request): JsonResponse
    {
        return $this->setEnabled($request, true);
    }

    private function setEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $bannerAd = $em->find(BannerAd::class, $request->request->get('id'));

        if ($bannerAd) {
            $bannerAd->setIsEnabled($enabled);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}
