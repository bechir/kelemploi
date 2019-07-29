<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller that manage security part of the backend.
 *
 * @IsGranted("ROLE_ADMIN")
 * @Route("/security")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/user/disable", name="admin_security_user_disable", methods={"POST"})
     */
    public function disableUser(Request $request): JsonResponse
    {
        return $this->setUserEnabled($request, false);
    }

    /**
     * @Route("/user/enable", name="admin_security_user_enable", methods={"POST"})
     */
    public function enableUser(Request $request): JsonResponse
    {
        return $this->setUserEnabled($request, true);
    }

    /**
     * @Route("/user/delete/{id}", name="admin_security_user_delete", methods={"POST"})
     */
    public function deleteUser(User $user)
    {
        if (!$user) {
            $this->addFlash('success', "L'utilisateur est introuvable.");
        } else {
            if ($user->hasRole('ROLE_ADMIN')) {
                $this->addFlash('success', 'Impossible de supprimer cet administrateur.');
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();
                $this->addFlash('success', "L'utilisateur a été supprimé.");
            }
        }

        return $this->redirectToRoute('admin_index');
    }

    private function setUserEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find(User::class, $request->request->get('id'));

        if ($user) {
            $user->setEnabled($enabled);

            $em->flush();
        }

        return new JsonResponse(true);
    }
}
