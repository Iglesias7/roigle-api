<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use App\Roigle\ApiHelper\FormUtil;
use Doctrine\ORM\EntityManagerInterface;
use App\Roigle\ApiHelper\ResponseJson;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cars")
 */
class CarController extends AbstractController
{
    /**
     * Get collection of cars.
     *
     * @Route("", name="all_cars", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param CarRepository $repository
     *
     * @return JsonResponse
     */
    public function getAll(ResponseJson $responseJson, CarRepository $repository): JsonResponse
    {
        $cars = $repository->findAll();
        return $responseJson->responseJson($cars);
    }

    /**
     * Get single resource of Car.
     *
     * @Route("/{id}", name="one_car", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param Car $car
     *
     * @return JsonResponse
     */
    public function getOne(ResponseJson $responseJson, Car $car): JsonResponse
    {
        return $responseJson->responseJson($car);
    }

    /**
     * @Route("/car/{id}", name="add-car", methods={"POST"})
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

        $car = new Car();

        $car->setName($data->name)
            ->setMark($data->mark)
            ->setPrice($data->price);
            // ->setPicture($data->picture);

        $manager->persist($car);

        $user->getCompte()->setSolde($user->getCompte()->getSolde() - $data->price);
        $user->setReputation($user->getReputation() + 10);
        $compte = $user->getCompte();
        $user->addCar($car);
        $manager->persist($user);
        $manager->persist($compte);

        $manager->flush();

        return $responseJson->responseJson($car);
    }

//$car = new Car();
//$form = $this->createForm(CarType::class, $car);
//$form->handleRequest($request);
//
//if ($form->isSubmitted() && $form->isValid()) {
//    /** @var UploadedFile $brochureFile */
//$brochureFile = $form['picture']->getData();
//if ($brochureFile) {
//$brochureFileName = $fileUploader->upload($brochureFile);
//$car->setPicture($brochureFileName);
//}
//}

    /**
     * @Route("/addCar/{id}/{email}", name="add_car", methods={"POST"})
     *
     * @param Car $car
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param string $email
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function addCar(Car $car, ResponseJson $responseJson, UserRepository $userRepository, string $email, EntityManagerInterface $manager): JsonResponse
    {

        $user = $userRepository->findOneBy(['email' => $email]);

        $user->addCar($car);

        $manager->persist($user);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * Delete one resource of car
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'etes pas autorisé à éffectuer cette action.")
     *
     * @Route("/{id}", name="delete_car", methods={"DELETE"})
     *
     * @param ResponseJson $responseJson
     * @param Car $car
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function delete(ResponseJson $responseJson, Car $car, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($car);
        $manager->flush();
        return $responseJson->emptyJson();
    }
}
