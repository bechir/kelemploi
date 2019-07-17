<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends Controller
{
    public function index(): Response
    {
        return $this->render('rendered/statistics.html.twig', [

        ]);
    }
}