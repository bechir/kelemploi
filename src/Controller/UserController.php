<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\EditProfileType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @IsGranted("ROLE_USER")
     */
    public function resume(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/resume.html.twig', [
            'user' => $user,
            'active' => 'resume',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function editResume(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/edit-esume.html.twig', [
            'user' => $user,
            'active' => 'edit-resume',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function dashboard(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'active' => 'dashboard',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'user.edit_profile.success');
        }

        return $this->render('user/edit-profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'active' => 'edit-profile',
        ]);
    }
    
    /**
     * @IsGranted("ROLE_USER")
     */
    public function bookmarked(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/bookmarked.html.twig', [
            'user' => $user,
            'active' => 'bookmarked',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function applied(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/applied.html.twig', [
            'user' => $user,
            'active' => 'applied',
        ]);
    }
    
    /**
     * @IsGranted("ROLE_USER")
     */
    public function messages(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/messages.html.twig', [
            'user' => $user,
            'active' => 'messages',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function settings(): Response
    {
        $user = $this->getUser();
    
        return $this->render('user/settings.html.twig', [
            'user' => $user,
            'active' => 'settings',
        ]);
    }
}
