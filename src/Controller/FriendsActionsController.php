<?php

namespace App\Controller;

use DateTime;
use App\Entity\Car;
use App\Entity\Immobilier;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Roigle\ApiHelper\FormUtil;
use Doctrine\ORM\EntityManagerInterface;
use App\Roigle\ApiHelper\ResponseJson;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;


/**
 * @Route("/friends-actions", name="friends-actions_")
 */
class FriendsActionsController
{
    /**
     * @Route("/follow", name="follow", methods={"PUT"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function follow(
        ResponseJson $responseJson,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $me = $userRepository->findOneBy(['email' => $data->user->email]);
        $user= $userRepository->findOneBy(['id' => $data->id]);

        $me->addFriendsSend($user);
        $user->addFriendsReceived($me);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/accept", name="accept", methods={"PUT"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function accept(
        ResponseJson $responseJson,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $me = $userRepository->findOneBy(['email' => $data->user->email]);
        $user= $userRepository->findOneBy(['id' => $data->id]);

        $user->removeFriendsSend($me);
        $me->removeFriendsReceived($user);
        $me->addFollower($user);
        $user->addFollower($me);

        $manager->persist($user);
        $manager->persist($me);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/CancelAsk", name="CancelAsk", methods={"PUT"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function CancelAsk(
        ResponseJson $responseJson,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $me = $userRepository->findOneBy(['email' => $data->user->email]);
        $user= $userRepository->findOneBy(['id' => $data->id]);

        $me->removeFriendsSend($user);
        $user->removeFriendsReceived($me);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/CancelReceived", name="CancelReceived", methods={"PUT"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function CancelReceived(
        ResponseJson $responseJson,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $me = $userRepository->findOneBy(['email' => $data->user->email]);
        $user= $userRepository->findOneBy(['id' => $data->id]);

        $me->removeFriendsReceived($user);
        $user->removeFriendsSend($me);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/unfollow", name="unfollow", methods={"PUT"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unfollow(
        ResponseJson $responseJson,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $me = $userRepository->findOneBy(['email' => $data->user->email]);
        $user= $userRepository->findOneBy(['id' => $data->id]);

        $me->removeFollower($user);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }
}
