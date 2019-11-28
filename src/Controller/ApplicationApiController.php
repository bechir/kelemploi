<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Application;
use App\Entity\DateInterval;
use App\Entity\JobCategory;
use App\Entity\StudyLevel;
use App\Form\ApplicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/app/api")
 */
class ApplicationApiController extends AbstractController
{
    /**
     * @Route("/list/{page}", name="app_list", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function list(Request $request, int $page): JsonResponse
    {
        if ($list = $this->getDoctrine()->getRepository(Application::class)->findAll()) {
            return $this->buildResponse(200, $list);
        }

        return $this->buildResponse();
    }

    /**
     * @Route("/create", name="app_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $appData = $request->request->get('application');
        $app = (new Application())
            ->setCompany($appData['company'])
            ->setNbCandidatesToRecruit($appData['nbCandidatesToRecruit'])
            ->setCompanyDescription($appData['companyDescription'])
            ->setJobDescription($appData['jobDescription'])
            ->setJobTitle($appData['jobTitle'])
            ->setDates((new DateInterval())
                ->setStart($appData['dates']['start'])
                ->setEnd($appData['dates']['end']))
            ->setWorkTime($appData['workTime'])
            ->setProfile($appData['profile'])
            ->setComment($appData['comment'])
            ->setSalary($appData['salary'])
        ;

        $postCategory = $em->find(JobCategory::class, $appData['postCategory']);
        $minStudyLevel = $em->find(StudyLevel::class, $appData['minStudyLevel']);

        $app->setPostCategory($postCategory)
            ->setMinStudyLevel($minStudyLevel);

        $form = $this->createForm(ApplicationType::class, $app);

        if ($form->isValid()) {
            $em->persist($app);
            $em->flush();

            return $this->buildResponse(200, 'success');
        }

        return $this->buildResponse();
    }

    /**
     * @Route("/details/{slug}", name="app_details")
     */
    public function show(Request $request, Application $app): JsonResponse
    {
        if ($app) {
            return $this->buildResponse(200, $app);
        }

        return $this->buildResponse();
    }

    /**
     * @Route("/edit/{slug}", name="app_edit")
     */
    public function edit(Application $app): JsonResponse
    {
        return $this->buildResponse();
    }

    /**
     * @Route("/delete/{slug}", name="app_delete")
     */
    public function delete(Application $app): JsonResponse
    {
        return $this->buildResponse();
    }

    public function buildResponse($code = 404, $data = null): JsonResponse
    {
        $response = [
            'code' => $code,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return new JsonResponse($data);
    }
}
