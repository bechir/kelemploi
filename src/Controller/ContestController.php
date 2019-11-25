<?php

namespace App\Controller;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContestController extends AbstractController
{

    public function index(Request $request, EntityManagerInterface $em, int $page = 1): Response
    {
        $contests = $em->getRepository(Application::class)->getApps($page);

        return $this->render('contest/index.html.twig', [
            'contests' => $contests,
        ]);
    }
}
