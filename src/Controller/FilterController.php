<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Roigle\ApiHelper\ResponseJson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/filters", name="filters_")
 */
class FilterController extends AbstractController
{

    /**
     * @Route("/all", name="all")
     * @param PostRepository $postRepository
     * @param ResponseJson $responseJson
     * @return JsonResponse
     */
    public function getAll(PostRepository $postRepository, ResponseJson $responseJson): JsonResponse
    {
        $posts = $postRepository->getAll();

        return $responseJson->responseJson($posts);
    }

    /**
     * @Route("/active", name="active")
     * @param PostRepository $postRepository
     * @param ResponseJson $responseJson
     * @return JsonResponse
     */
    public function active(PostRepository $postRepository, ResponseJson $responseJson):  JsonResponse
    {
        $posts = $postRepository->getActive();

        return $responseJson->responseJson($posts);
    }

    /**
     * @Route("/newest", name="newest")
     * @param PostRepository $postRepository
     * @param ResponseJson $responseJson
     * @return JsonResponse
     */
    public function newest(PostRepository $postRepository, ResponseJson $responseJson):  JsonResponse
    {
        $posts = $postRepository->newest();

        return $responseJson->responseJson($posts);
    }

    /**
     * @Route("/vote", name="vote")
     * @param PostRepository $postRepository
     * @param ResponseJson $responseJson
     * @return JsonResponse
     */
    public function vote(PostRepository $postRepository, ResponseJson $responseJson):  JsonResponse
    {
        $posts = $postRepository->getVote();

        return $responseJson->responseJson($posts);
    }
}