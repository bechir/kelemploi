<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Resume;
use App\Util\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @IsGranted("ROLE_EMPLOYER")
 */
class CartController extends AbstractController
{
    public function list(UserInterface $user = null): Response
    {
        return $this->render('company/cart.html.twig', [
            'user' => $user,
            'active' => 'cart',
        ]);
    }

    public function add(Request $request, EntityManagerInterface $em, UserInterface $user = null): JsonResponse
    {
        $cartToken = TokenGenerator::generateCartToken($request);
        $requestToken = $request->request->get('token');

        if ($cartToken == $requestToken) {
            $resume = $em->getRepository(Resume::class)->findOneById($request->request->get('id'));

            if ($resume) {
                $cart = $user->getCart();

                if (!$cart) {
                    $cart = new Cart();
                    $cart->setCustomer($user);
                }

                $cart->addResume($resume);
                $em->persist($cart);
                $em->flush();

                return new JsonResponse('success');
            }
        }

        return new JsonResponse('error');
    }

    public function removeItem(Request $request, EntityManagerInterface $em, UserInterface $user = null): JsonResponse
    {
        $resume = $em->getRepository(Resume::class)->findOneById($request->request->get('id'));

        if ($resume) {
            $cart = $user->getCart();

            if ($cart) {
                $cart->removeResume($resume);
                $em->flush();

                return new JsonResponse('success');
            }
        }

        return new JsonResponse('error');
    }
}
