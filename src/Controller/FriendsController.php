<?php

namespace App\Controller;

use App\Roigle\ApiHelper\ResponseJson;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/friends", name="friends_")
 */
class FriendsController extends AbstractController
{
    
    /**
     * Get collection of user.
     *
     * @Route("", name="users", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $repository
     *
     * @return JsonResponse
     */
    public function getAll(ResponseJson $responseJson, UserRepository $repository): JsonResponse
    {
        $users = $repository->findAll();
        return $responseJson->responseJson($users);
    }

    /**
     * Get collection of user.
     *
     * @Route("/followers/{id}", name="followers", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     * 
     *
     * @return JsonResponse
     */
    public function getAllFollowers(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getFollowers());
    }

    /**
     * Get collection of user.
     *
     * @Route("/friends-send/{id}", name="friends-send", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     *
     * @return JsonResponse
     */
    public function getAllFriendsSend(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getFriendsSend());
    }

    /**
     * Get collection of user.
     *
     * @Route("/friends-received/{id}", name="friends-received", methods={"GET"})
     *
     * @param User $user
     * @param ResponseJson $responseFactory
     *
     * @return JsonResponse
     */
    public function getAllFriendsReceived(User $user, ResponseJson $responseJson): JsonResponse
    {
        return $responseJson->responseJson($user->getFriendsReceived());
    }
}
