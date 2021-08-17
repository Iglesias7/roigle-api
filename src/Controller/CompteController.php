<?php

namespace App\Controller;

use DateTime;
use App\Entity\Compte;
use App\Entity\Transaction;
use App\Repository\CarRepository;
use App\Repository\ImmobilierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Roigle\ApiHelper\ResponseJson;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/comptes")
 */
class CompteController extends AbstractController
{

    /**
     * @Route("/acheter_immo/{id}/{name}", name="acheter_immo", methods={"PUT"})
     *
     * @param Compte $compte
     * @param string name
     *
     * @param ResponseJson $responseJson
     * @param ImmobilierRepository $immoRepository
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function achatImmo(Compte $compte, string $name, ResponseJson $responseJson, ImmobilierRepository $immoRepository, EntityManagerInterface $manager): JsonResponse
    {
        $date = new DateTime();

        $immo= $immoRepository->findOneBy(['name' => $name]);

        $transaction = new Transaction();
        $transaction->setAction("Achat de vehicule")
                    ->setCompte($compte)
                    ->setMontant($immo->getPrice())
                    ->setMessage("Achat d'un véhicule ".$immo->getName(). "de type ".$immo->getType())
                    ->setTime($date);
        $manager->persist($transaction);

        $compte->setSolde($compte->getSolde() - $immo->getPrice())
                ->addTransaction($transaction);
        $manager->persist($compte);

        $manager->flush();

        return $responseJson->responseJson($compte);
    }
    /**
     * @Route("/acheter_vehicule/{id}/{name}", name="acheter_vehicule", methods={"PUT"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param string name
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function achatVehicule(Compte $compte, string $name, ResponseJson $responseJson, CarRepository $carRepository, EntityManagerInterface $manager): JsonResponse
    {
        $date = new DateTime();

        $car= $carRepository->findOneBy(['name' => $name]);

        $transaction = new Transaction();
        $transaction->setAction("Achat de vehicule")
                    ->setCompte($compte)
                    ->setMontant($car->getPrice())
                    ->setMessage("Achat d'un véhicule ".$car->getName(). "de marque ".$car->getMark())
                    ->setTime($date);
        $manager->persist($transaction);

        $compte->setSolde($compte->getSolde() - $car->getPrice())
                ->addTransaction($transaction);
        $manager->persist($compte);

        $manager->flush();

        return $responseJson->responseJson($compte);
    }

    /**
     * @Route("/acheter/{id}", name="acheter", methods={"PUT"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param int price
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function acheter(Compte $compte, Request $request, UserRepository $userRepository, ResponseJson $responseJson, EntityManagerInterface $manager): JsonResponse
    {
        $date = new DateTime();
        $data = json_decode($request->getContent());
        $user= $userRepository->findOneBy(['id' => $data->id]);

        $user->setReputation($user->getReputation() + $data->rep);
        $manager->persist($user);

        $transaction = new Transaction();
        $transaction->setAction("Achat de vin")
                    ->setCompte($compte)
                    ->setMontant($data->price)
                    ->setMessage("Achat à la discothèque")
                    ->setTime($date);
        $manager->persist($transaction);

        $compte->setSolde($compte->getSolde() - $data->price)
                ->addTransaction($transaction);
        $manager->persist($compte);

        $manager->flush();

        return $responseJson->responseJson($compte);
    }
    
    
    
    
 
    
    
}