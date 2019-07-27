<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Company;
use App\Form\CompanyType;

class CompanyController extends AbstractController
{
    /**
     * IsGranted("ROLE_EMPLOYER")
     */
    public function dashboard() : Response
    {
        $user = $this->getUser();

        return $this->render('company/dashboard.html.twig', [
            'user' => $user,
            'active' => 'dashboard',
            'company' => $user->getCompany()
        ]);
    }

    /**
     * @IsGranted("ROLE_EMPLOYER")
     */
    public function editProfile(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $company = $this->getUser()->getCompany();

        if(!$company)
            $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $company->addOwner($this->getUser());
            $em->persist($company);
            $em->flush();

            $this->addFlash('success', 'company.edit_profile.success');
        }

        return $this->render('company/edit-profile.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'active' => 'edit-profile',
        ]);
    }
}