<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Match;
use App\Entity\User;
use App\Repository\CardRepository;
use App\Repository\CarRepository;
use App\Repository\MatchRepository;
use App\Repository\UserRepository;
use App\Roigle\ApiHelper\ResponseJson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/matchs", name="matchs")
 */
class MatchController extends AbstractController
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
        public function getAll(ResponseJson $responseJson, CardRepository $repository, User $user): JsonResponse
        {
                $card = $repository->findAllCardById($user);
                return $responseJson->responseJson($card);
        }

    /**
     * Get single resource of User.
     *
     * @Route("/{id}", name="get-match", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param Match $match
     *
     * @return JsonResponse
     */
    public function getOne(ResponseJson $responseJson, Match $match): JsonResponse
    {
        return $responseJson->responseJson($match);
    }

    /**
     * @Route("/add-match", name="add-match", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param MatchRepository $matchRepository
     * @param MessageBusInterface $bus
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function addMatch(
        ResponseJson $responseJson,
        MatchRepository $matchRepository,
        MessageBusInterface $bus,
        Request $request,
        EntityManagerInterface $manager,
        UserRepository $userRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent());

        $match = new Match();

        $milieux = null;
        $bank = null;

        $joueur2= $userRepository->findOneBy(['email' => $data->email]);
        $joueur1= $userRepository->findOneBy(['id' => $data->id]);
        
        $joueur1->setIsOpen(false)
                ->getCompte()->setSolde($joueur1->getCompte()->getSolde() - $data->request->mise);

        $joueur2->setIsOpen(false)
                ->getCompte()->setSolde($joueur2->getCompte()->getSolde() - $data->request->mise);
        
        $manager->persist($joueur1);
        $manager->persist($joueur2);

        $match->setJoueur1($joueur1)
                ->setJoueur2($joueur2)
                ->setMise($data->request->mise * 2)
                ->setBank($bank)
                ->setMilieux($milieux);

        $manager->persist($match);

        $manager->flush();

        $id = $match->getId();
        $update = new Update (
            'http://monsite.com/add-match',
            \json_encode(['message' => $id])
        );

        $bus->dispatch($update);

        return $responseJson->responseJson($id);
    }

    /**
     * @Route("/init", name="init", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function init(ResponseJson $responseJson, MessageBusInterface $bus, EntityManagerInterface $manager, MatchRepository $matchRepository,  UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $joueur1 = $userRepository->findOneBy(['id' => $data->joueur1->id]);
        $joueur2= $userRepository->findOneBy(['id' => $data->joueur2->id]);

        for($i = 0; $i < 4; ++$i) {
                $hand = new Card();
                $hand->setImage($data->joueur1->hand[$i]->image)
                        ->setNumber($data->joueur1->hand[$i]->number)
                        ->setType($data->joueur1->hand[$i]->type)
                        ->setFaceUp($data->joueur1->hand[$i]->faceUp);
                $manager->persist($hand);

                $joueur1->addHand($hand)->setAMoi($data->joueur1->aMoi);
                $manager->persist($joueur1); 
        }

        for($i = 0; $i < 4; ++$i) {
                $hand = new Card();
                $hand->setImage($data->joueur2->hand[$i]->image)
                        ->setNumber($data->joueur2->hand[$i]->number)
                        ->setType($data->joueur2->hand[$i]->type)
                        ->setFaceUp($data->joueur2->hand[$i]->faceUp);
                $manager->persist($hand);

                $joueur2->addHand($hand)->setAMoi($data->joueur2->aMoi);
                $manager->persist($joueur2);    
        }

        $match = $matchRepository->findOneBy(['id' => $data->matchId]);

        $milieux = new Card();
        $bank = new Card();

        $milieux->setImage($data->milieux->image)
                ->setNumber($data->milieux->number)
                ->setType($data->milieux->type)
                ->setFaceUp($data->milieux->faceUp);
        $manager->persist($milieux);

        $bank->setImage($data->bank->image)
                ->setNumber($data->bank->number)
                ->setType($data->bank->type)
                ->setFaceUp($data->bank->faceUp);
        $manager->persist($bank);

        $match->setBank($bank)
              ->setMilieux($milieux);

        $manager->persist($match);

        $manager->flush();

        $match1 = $matchRepository->findOneBy(['id' => $data->matchId]);

        $update = new Update (
            'http://monsite.com/init',
            \json_encode(['message' => $match->getId()])
        );

        $bus->dispatch($update);

        return $responseJson->responseJson($match1);
    }

    /**
     * @Route("/play", name="play", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function play(ResponseJson $responseJson, MessageBusInterface $bus, EntityManagerInterface $manager, CardRepository $cardRepository, MatchRepository $matchRepository,  UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $cardPlay = $cardRepository->findOneBy(['id' => $data->cardPlayId]);

        $joueur1 = $userRepository->findOneBy(['id' => $data->joueur1->id]);
        $joueur2 = $userRepository->findOneBy(['id' => $data->joueur2->id]);
        $match = $matchRepository->findOneBy(['id' => $data->matchId]);

        if($data->joueur1->aMoi){
            $joueur1->removeHand($cardPlay)
                    ->setAMoi(false);
            $joueur2->setAMoi(true);

            $manager->persist($joueur1);
            $manager->persist($joueur2);
        }

        if($data->joueur2->aMoi){
            $joueur2->removeHand($cardPlay)
                    ->setAMoi(false);
            $joueur1->setAMoi(true);

            $manager->persist($joueur1);
            $manager->persist($joueur2);
        }

        $bank = new Card();

        $bank->setImage($data->bank->image)
                ->setNumber($data->bank->number)
                ->setType($data->bank->type)
                ->setFaceUp($data->bank->faceUp);
        $manager->persist($bank);

//        $manager->remove($match->getBank());
//        $manager->remove($match->getMilieux());
//        $cardRepository->deleteCard($match->getBank());
//        $cardRepository->deleteCard($match->getMilieux());

        $match->setBank($bank);

        $milieux = new Card();

        $milieux->setImage($cardPlay->getImage())
            ->setNumber($cardPlay->getNumber())
            ->setType($cardPlay->getType())
            ->setFaceUp(true);
        $manager->persist($milieux);
        $match->setMilieux($milieux);

        $manager->persist($match);
//        $manager->remove($cardPlay);
        $cardRepository->deleteCard($cardPlay);
        $manager->persist($cardPlay);

        $manager->flush();

        $match1 = $matchRepository->findOneBy(['id' => $data->matchId]);

        $update = new Update (
            'http://monsite.com/play',
            \json_encode(['message' => $match1->getId()])
        );

        $bus->dispatch($update);

        return $responseJson->responseJson($match1);
    }


    /**
     * @Route("/peche", name="peche", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param MessageBusInterface $bus
     * @param EntityManagerInterface $manager
     * @param MatchRepository $matchRepository
     * @param UserRepository $userRepository
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function peche(
        ResponseJson $responseJson,
        MessageBusInterface $bus,
        EntityManagerInterface $manager,
        MatchRepository $matchRepository,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());

        $peche = new Card();

        $peche->setImage($data->peche->image)
                ->setNumber($data->peche->number)
                ->setType($data->peche->type)
                ->setFaceUp($data->peche->faceUp);
        $manager->persist($peche);

        $joueur1 = $userRepository->findOneBy(['id' => $data->joueur1->id]);
        $joueur2 = $userRepository->findOneBy(['id' => $data->joueur2->id]);
        $match = $matchRepository->findOneBy(['id' => $data->matchId]);

        if($data->joueur1->aMoi) {
            $joueur1->addHand($peche)
                    ->setAMoi(false);
            $joueur2->setAMoi(true);

            $manager->persist($joueur1);
            $manager->persist($joueur2);
        }

        if($data->joueur2->aMoi){
            $joueur2->addHand($peche)
                    ->setAMoi(false);
            $joueur1->setAMoi(true);

            $manager->persist($joueur1);
            $manager->persist($joueur2);
        }

        $bank = new Card();

        $bank->setImage($data->bank->image)
                ->setNumber($data->bank->number)
                ->setType($data->bank->type)
                ->setFaceUp($data->bank->faceUp);
        $manager->persist($bank);

//        $cardRepository->deleteCard($match->getBank());

        $match->setBank($bank);

        $manager->persist($match);

        $manager->flush();

        $match1 = $matchRepository->findOneBy(['id' => $data->matchId]);

        $update = new Update (
            'http://monsite.com/peche',
            \json_encode(['message' => $match1->getId()])
        );

        $bus->dispatch($update);

        return $responseJson->responseJson($match1);
    }

    /**
     * @Route("/match/delete", name="delete", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param MessageBusInterface $bus
     * @param EntityManagerInterface $manager
     * @param CardRepository $cardRepository
     * @param MatchRepository $matchRepository
     * @param UserRepository $userRepository
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function delete(
        ResponseJson $responseJson,
        MessageBusInterface $bus,
        EntityManagerInterface $manager,
        CardRepository $cardRepository,
        MatchRepository $matchRepository,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());

        $joueur1 = $userRepository->findOneBy(['id' => $data->joueur1->id]);
        $joueur2 = $userRepository->findOneBy(['id' => $data->joueur2->id]);
        $match = $matchRepository->findOneBy(['id' => $data->matchId]);

        $matchRepository->deleteMatch($match);
        $manager->flush();
        $cardRepository->deleteCard($match->getBank());
        $cardRepository->deleteCard($match->getMilieux());

        $joueur1->setAMoi(false);
        $joueur2->setAMoi(false);

        $joueur1->setIsOpen(true)
                ->getCompte()->setSolde($joueur1->getCompte()->getSolde() + $match->getMise() / 2);

        $joueur2->setIsOpen(true)
                ->getCompte()->setSolde($joueur2->getCompte()->getSolde() + $match->getMise() / 2);
        
        foreach($joueur1->getHand() as $card) {
            $cardPlay = $cardRepository->findOneBy(['id' => $card->getId()]);
            $joueur1->removeHand($cardPlay);
            $manager->persist($joueur1);
            $cardRepository->deleteCard($card->getId());
//            $manager->remove($cardPlay);
        }

        foreach($joueur2->getHand() as $card) {
            $cardPlay = $cardRepository->findOneBy(['id' => $card->getId()]);
            $joueur2->removeHand($cardPlay);
            $manager->persist($joueur2);
            $cardRepository->deleteCard($card->getId());
//            $manager->remove($cardPlay);
        }

        $manager->flush();

        $update = new Update (
            'http://monsite.com/delete_match',
            \json_encode(['message' => 'match supprimé'])
        );

        $bus->dispatch($update);

        return $responseJson->emptyJson();
    }

    /**
     * @Route("/finish", name="finish", methods={"DELETE"})
     *
     * @param ResponseJson $responseJson
     * @param MessageBusInterface $bus
     * @param EntityManagerInterface $manager
     * @param CardRepository $cardRepository
     * @param MatchRepository $matchRepository
     * @param UserRepository $userRepository
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function finish(
        ResponseJson $responseJson,
        MessageBusInterface $bus,
        EntityManagerInterface $manager,
        CardRepository $cardRepository,
        MatchRepository $matchRepository,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent());

        $joueur1 = $userRepository->findOneBy(['id' => $data->joueur1->id]);
        var_dump($joueur1);
        $joueur2 = $userRepository->findOneBy(['id' => $data->joueur2->id]);
        $match = $matchRepository->findOneBy(['id' => $data->matchId]);
        $mise = $match->getMise();

        $matchRepository->deleteMatch($match);
        $manager->flush();
        $cardRepository->deleteCard($match->getBank());
        $cardRepository->deleteCard($match->getMilieux());

        $joueur1->setAMoi(false);
        $joueur2->setAMoi(false);

        if(empty($joueur1->getHand())){
            foreach($joueur2->getHand() as $card) {
                $cardPlay = $cardRepository->findOneBy(['id' => $card->getId()]);
                $joueur2->removeHand($cardPlay);
                $manager->persist($joueur2);
                $cardRepository->deleteCard($card->getId());
            }
            $gagnant = $joueur1->getFirstName();
            $joueur1->setIsOpen(true)
                    ->getCompte()
                    ->setSolde($joueur1->getCompte()->getSolde() + $mise);
            $joueur1->setNbTrophet($joueur1->getNbTrophet() + 1);

        } else if(empty($joueur2->getHand())){
            foreach($joueur1->getHand() as $card) {
                $cardPlay = $cardRepository->findOneBy(['id' => $card->getId()]);
                $joueur1->removeHand($cardPlay);
                $manager->persist($joueur1);
                $cardRepository->deleteCard($card->getId());
            }
            $gagnant = $joueur1->getFirstName();
            $joueur2->setIsOpen(true)
                    ->getCompte()
                    ->setSolde($joueur2->getCompte()->getSolde() + $mise);
            $joueur2->setNbTrophet($joueur2->getNbTrophet() + 1);
        }

        $manager->persist($joueur1);
        $manager->persist($joueur2);

        $manager->flush();

        $update = new Update (
            'http://monsite.com/finish_match',
            \json_encode(['message' => $gagnant])
        );

        $bus->dispatch($update);

        return $responseJson->emptyJson();
    }
}
