<?php

namespace App\Controller;

use App\Entity\Immobilier;
use App\Entity\User;
use App\Repository\ImmobilierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Roigle\ApiHelper\ResponseJson;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/immobiliers")
 */
class ImmobilierController extends AbstractController
{
    /**
     * Get collection of cars.
     *
     * @Route("", name="all_immo", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param ImmobilierRepository $repository
     *
     * @return JsonResponse
     */
    public function getAll(ResponseJson $responseJson, ImmobilierRepository $repository): JsonResponse
    {
        $cars = $repository->findAll();
        return $responseJson->responseJson($cars);
    }

    /**
     * Get single resource of Immobilier.
     *
     * @Route("/{id}", name="one_immobilier", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param Immobilier $car
     *
     * @return JsonResponse
     */
    public function getOne(ResponseJson $responseJson, Immobilier $immobilier): JsonResponse
    {
        return $responseJson->responseJson($immobilier);
    }

    /**
     * @Route("/immobilier/{id}", name="add_immobilier", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function post(ResponseJson $responseJson, EntityManagerInterface $manager, Request $request, User $user): JsonResponse
    {
        $data = json_decode($request->getContent());

        $immobilier = new Immobilier();

        $immobilier->setName($data->name)
            ->setType([$data->type])
            ->setPrice($data->price);
            // ->setPicture($data->picture);

        $manager->persist($immobilier);

        $user->getCompte()->setSolde($user->getCompte()->getSolde() - $data->price);
        $user->setReputation($user->getReputation() + 50);
        $compte = $user->getCompte();
        $user->addImmobilier($immobilier);
        $manager->persist($user);
        $manager->persist($compte);

        $manager->flush();

        return $responseJson->responseJson($immobilier);
    }

    /**
     * @Route("/addImmo/{id}/{email}", name="add_Immo", methods={"POST"})
     *
     * @param Immobilier $immobilier
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param string $email
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function addImmobilier(Immobilier $immobilier, ResponseJson $responseJson, UserRepository $userRepository, string $email, EntityManagerInterface $manager): JsonResponse
    {

        $user = $userRepository->findOneBy(['email' => $email]);

        $user->addImmobilier($immobilier);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * Delete one resource of immo
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'etes pas autorisé à éffectuer cette action.")
     *
     * @Route("/{id}", name="delete_car", methods={"DELETE"})
     *
     * @param ResponseJson $responseJson
     * @param Immobilier $immobilier
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function delete(ResponseJson $responseJson, Immobilier $immobilier, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($immobilier);
        $manager->flush();
        return $responseJson->emptyJson();
    }
}
