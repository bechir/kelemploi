<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CompanyController extends AbstractController
{
    /**
     * IsGranted("ROLE_EMPLOYER")
     */
    public function dashboard() : Response
    {
        return $this->render('company/dashboard.html.twig', [
            'user' => $this->getUser(),
            'active' => 'dashboard',
            'company' => $this->getUser()->getCompany()
        ]);
    }
}