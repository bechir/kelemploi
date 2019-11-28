<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Apply;
use App\Entity\Comment;
use App\Entity\ResumeOfWeek;
use App\Entity\User;
use App\Form\Admin\AdminUserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller that manage security part of the backend.
 *
 * @IsGranted("ROLE_ADMIN")
 * @Route("/fr/admin/security")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/fr/user/disable", name="admin_security_user_disable", methods={"POST"})
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
    public function deleteUser(User $user, EntityManagerInterface $em)
    {
        if (!$user) {
            $this->addFlash('danger', "L'utilisateur est introuvable.");
        } else {
            if ($user->hasRole('ROLE_ADMIN')) {
                $this->addFlash('danger', 'Impossible de supprimer cet administrateur.');
            } else {
                $em = $this->getDoctrine()->getManager();

                // $resumeOfWeeek = $em->getRepository(ResumeOfWeek::class)->get();
                // if ($resumeOfWeeek->getResume() == $user->getResume()) {
                //     $this->addFlash('info', "Vous devez supprimer cet utilisateur du CV à la une d'abord.");

                //     return $this->redirectToRoute('admin_settings_resume_of_week');
                // }
                $applies = $em->getRepository(Apply::class)->findByCandidate($user);
                foreach ($applies as $apply) {
                    $em->remove($apply);
                }

                $comments = $em->getRepository(Comment::class)->findByAuthor($user);
                foreach ($comments as $comment) {
                    $em->remove($comment);
                }

                $em->remove($user);
                $em->flush();
                $this->addFlash('success', "L'utilisateur a été supprimé.");
            }
        }

        return $this->redirectToRoute('admin_users');
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

    /**
     * @Route("/new/user", name="admin_user_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$user->getRoles()->getType()]);
            $em->persist($user);

            $em->flush();

            $this->addFlash('success', "L'utilisateur a été créé avec succès.");

            return $this->redirectToRoute('admin_user_show', [
                'user' => $user->getId(),
            ]);
        }

        return $this->render('admin/user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
