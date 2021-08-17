<?php

namespace App\Controller;

use App\Form\UserType;
use DateTime;
use App\Entity\Car;
use App\Entity\Compte;
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
 * @Route("/users", name="users_")
 */
class UserController
{
    /**
     * Get collection of Users.
     *
     * @Route("", name="get_collection", methods={"GET"})
     *
     * @param Request $request
     * @param ResponseJson $responseFactory
     * @param UserRepository $repository
     *
     * @return JsonResponse
     */
    public function getCollection(
        Request $request,
        ResponseJson $responseFactory,
        UserRepository $repository
    ): JsonResponse {
        return $responseFactory->responseJson($repository->findByCriteria($request->query->all()));
    }

    /**
     * Get single resource of User.
     *
     * @Route("/{id}", name="get", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param User $user
     *
     * @return JsonResponse
     */
    public function get(
        ResponseJson $responseJson,
        User $user
    ): JsonResponse
    {
        return $responseJson->responseJson($user);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function post(
        ResponseJson $responseJson,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $date = new DateTime($data->birthDate);
        $user = new User();

        $compte = new Compte();       $user->setEmail($data->email)
            ->setFirstName($data->firstName)
            ->setLastName($data->lastName)
            ->setBirthDate($date)
            ->setPassword($passwordEncoder->encodePassword($user, $data->password));

        $manager->persist($user);
        $compte->setUser($user);
        $manager->persist($compte);
        $user->setCompte($compte);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function put(
        User $user,
        ResponseJson $responseJson,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());

        $date = new DateTime($data->birthDate);

        $user->setEmail($data->email)
            ->setFirstName($data->firstName)
            ->setLastName($data->lastName)
            ->setBirthDate($date);

        if($data->maxMise){
            $user->setMaxMise($data->maxMise);
        }

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/open/{id}", name="update_open", methods={"PUT"})
     *
     * @param User $user
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function putOpen(
        User $user,
        ResponseJson $responseJson,
        EntityManagerInterface $manager,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());

        $user->setIsOpen($data->isOpen);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * Delete one resource of User
     *
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @param ResponseJson $responseJson
     * @param User $user
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function delete(
        ResponseJson $responseJson,
        User $user,
        EntityManagerInterface $manager
    ): JsonResponse
    {
        $manager->remove($user);
        $manager->flush();
        return $responseJson->emptyJson();
    }
}
