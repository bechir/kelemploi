<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResumeTemplateController extends AbstractController
{
    /**
     * @Route("/free-resume-templates", name="resume_templates")
     */
    public function index()
    {
        return $this->render('resume_template/index.html.twig', [
            'controller_name' => 'ResumeTemplateController',
        ]);
    }
}
