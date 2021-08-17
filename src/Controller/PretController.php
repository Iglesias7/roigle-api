<?php

namespace App\Controller;

use App\Roigle\ApiHelper\ResponseJson;
use DateTime;
use App\Entity\Car;
use App\Entity\Pret;
use App\Entity\Compte;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\ImmobilierRepository;
use App\Repository\PretRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/prets", name="prets_")
 */
class PretController extends AbstractController
{

    /**
     * @Route("/pret-bancaire/{id}", name="pret-bancaire", methods={"POST"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function pretBancaire(Compte $compte, ResponseJson $responseJson, EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $transaction = new Transaction();
        $transaction->setAction("Pret bancaire")
                    ->setCompte($compte)
                    ->setMontant($data->montant)
                    ->setMessage("pret bancaire demandé ce jour.")
                    ->setTime(new \DateTime($data->delai));
        $manager->persist($transaction);

        $pret = new Pret();

        $pret->setMontant($data->montant)
            ->setDelai(new \DateTime($data->delai))
            ->setMessage($data->message)
            ->setDemandeur($compte->getUser());
        $manager->persist($pret);

        $compte->setSolde($compte->getSolde() + $data->montant)
                ->addTransaction($transaction);
        $compte->getUser()->addCeuQueJeDois($pret);
        $manager->persist($compte);

        $manager->flush();

        return $responseJson->responseJson($compte->getUser());
    }
    
    /**
     * @Route("/demander-pret/{id}/{email}", name="demander-pret", methods={"POST"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function demanderUnPret(Compte $compte, string $email,UserRepository $userRepository, ResponseJson $responseJson, EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $pret = new Pret();
        $user= $userRepository->findOneBy(['email' => $email]);

        $pret->setMontant($data->montant)
            ->setDelai(new \DateTime($data->delai))
            ->setMessage($data->message)
            ->setDemandeur($compte->getUser())
            ->setDonneur($user);
        $manager->persist($pret);

        $compte->getUser()->addDemandeEnvoyer($pret);
        $user->addDemandeRecus($pret);

        $manager->persist($user);
        $manager->persist($compte);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/preter/{id}/{email}/{pretId}", name="preter", methods={"PUT"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param int price
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function preter(Compte $compte, string $email,UserRepository $userRepository, ResponseJson $responseJson, PretRepository $pretRepository, EntityManagerInterface $manager, int $pretId): JsonResponse
    {

        $user= $userRepository->findOneBy(['email' => $email]);
        $pret= $pretRepository->findOneBy(['id' => $pretId]);

        $transaction1 = new Transaction();
        $transaction1->setAction("Pret d'argent")
                    ->setCompte($compte)
                    ->setMontant($pret->getMontant())
                    ->setMessage("Pret d'argent")
                    ->setDemandeur($user)
                    ->setDonneur($compte->getUser())
                    ->setTime($pret->getDelai());
        $manager->persist($transaction1);

        $transaction2 = new Transaction();
        $transaction2->setAction("Emprunt d'argent")
                    ->setCompte($compte)
                    ->setMontant($pret->getMontant())
                    ->setMessage("Emprunt d'argent")
                    ->setTime($pret->getDelai());
        $manager->persist($transaction2);

        $compte->setSolde($compte->getSolde() - $pret->getMontant());
        $user->getCompte()->setSolde($user->getCompte()->getSolde() + $pret->getMontant());



        $compte->getUser()->addCeuxQuiMeDoivent($pret);
        $compte->addTransaction($transaction1);
        $user->addCeuQueJeDois($pret);
        $user->getCompte()->addTransaction($transaction2);

        $compte->getUser()->removeDemandeRecus($pret);
        $user->removeDemandeEnvoyer($pret);

        $me = $compte->getUser();

        $manager->persist($me);
        $manager->persist($user);
        $manager->persist($compte);
        $manager->flush();

        return $responseJson->responseJson($compte->getUser());
    }


/**
     * @Route("/refuser/{id}/{email}/{pretId}", name="refuser", methods={"POST"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function refuser(Compte $compte, string $email,UserRepository $userRepository, ResponseJson $responseJson, EntityManagerInterface $manager, PretRepository $pretRepository, int $pretId): JsonResponse
    {
        
        $user= $userRepository->findOneBy(['email' => $email]);
        $pret= $pretRepository->findOneBy(['id' => $pretId]);

        
        $compte->getUser()->removeDemandeRecus($pret);
        $user->removeDemandeEnvoyer($pret);

        $manager->persist($user);
        $manager->persist($compte);
        $manager->flush();

        return $responseJson->responseJson($pret);
    }

    /**
     * @Route("/rembourser/{id}/{email}/{pretId}", name="rembourser", methods={"PUT"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function rembourser(Compte $compte, string $email,UserRepository $userRepository, ResponseJson $responseJson, EntityManagerInterface $manager, PretRepository $pretRepository, int $pretId): JsonResponse
    {
        
        $user= $userRepository->findOneBy(['email' => $email]);
        $pret= $pretRepository->findOneBy(['id' => $pretId]);

        $compte->getUser()->removeCeuxQueJeDois($pret);
        $user->removeCeuxQuiMeDoivent($pret);

        $compte->setSolde($compte->getSolde() - $pret->getMontant());
        $user->getCompte()->setSolde($user->getCompte()->getSolde() + $pret->getMontant());

        $manager->persist($user);
        $manager->persist($compte);
        $manager->flush();

        return $responseJson->responseJson($user);
    }

    /**
     * @Route("/rembourser-banque/{id}/{pretId}", name="rembourser-banque", methods={"PUT"})
     *
     * @param Compte $compte
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function rembourserBanque(Compte $compte,UserRepository $userRepository, ResponseJson $responseJson, EntityManagerInterface $manager, PretRepository $pretRepository, int $pretId): JsonResponse
    {
        
        $pret= $pretRepository->findOneBy(['id' => $pretId]);
        $compte->getUser()->removeCeuxQueJeDois($pret);


        $compte->setSolde($compte->getSolde() - $pret->getMontant());

        $manager->persist($compte);
        $manager->flush();

        return $responseJson->responseJson($compte);
    }
    
}
