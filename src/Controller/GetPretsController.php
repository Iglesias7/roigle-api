<?php

namespace App\Controller;

use App\Entity\User;
use App\Roigle\ApiHelper\ResponseJson;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/get-prets")
 */
class GetPretsController extends AbstractController
{

    /**
     * Get collection of prets.
     *
     * @Route("/ceux-qui-me-doivent/{id}", name="ceux-qui-me-doivent", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     *
     * @return JsonResponse
     */
    public function getAllPrets(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getCeuxQuiMeDoivent());
    }

    /**
     * Get collection of prets.
     *
     * @Route("/ceux-que-je-dois/{id}", name="ceux-que-je-dois", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     *
     * @return JsonResponse
     */
    public function getAllEmprunts(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getCeuxQueJeDois());
    }

    /**
     * Get collection of prets.
     *
     * @Route("/demande-envoyer/{id}", name="demande-envoyer", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     *
     * @return JsonResponse
     */
    public function getAllPretsSend(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getDemandesEnvoyer());
    }

    /**
     * Get collection of prets.
     *
     * @Route("/demande-recus/{id}", name="demande-recus", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     *
     * @return JsonResponse
     */
    public function getAllEmpruntsSend(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getDemandesRecus());
    }
}
