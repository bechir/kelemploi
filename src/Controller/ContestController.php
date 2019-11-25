<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Contest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContestController extends AbstractController
{

    public function index(Request $request, EntityManagerInterface $em, int $page = 1): Response
    {
        $contests = $em->getRepository(Contest::class)->getContests($page);

        return $this->render('contest/index.html.twig', [
            'contests' => $contests,
        ]);
    }

    public function show(Contest $contest): Response
    {
        return $this->render('contest/details.html.twig', [
            'contest' => $contest
        ]);
    }
}
