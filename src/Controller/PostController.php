<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Roigle\ApiHelper\ResponseJson;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/posts")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/responses/{id}", name="responses")
     */
    public function getResponses(PostRepository $postRepository, ResponseJson $responseJson, int $id):  JsonResponse
    {
        $responses = $postRepository->getResponses($id);

        return $responseJson->responseJson($responses);
    }

    /**
     * Get single resource of User.
     *
     * @Route("/{id}", name="get-post", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param User $user
     *
     * @return JsonResponse
     */
    public function getPost(ResponseJson $responseJson, Post $post): JsonResponse
    {
        return $responseJson->responseJson($post);
    }

    /**
     * @Route("/add", name="add-post", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function addPost(ResponseJson $responseJson, PostRepository $postRepository, UserRepository $userRepository, EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        if($data->parentId) {
            $parent = $postRepository->findOneBy(['id' => $data->parentId]);
        } else {
            $parent = null;
        }

        $user = $userRepository->findOneBy(['id' => $data->userId]);
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('UTC'));

        $post = new Post();

        $post->setTitle($data->title)
            ->setBody($data->body)
            ->setTimestamp($date)
            ->setUser($user)
            ->setParent($parent);

        $manager->persist($post);
        $manager->flush();

        return $responseJson->responseJson($post);
    }

    /**
     * @Route("/{id}", name="update_post", methods={"PUT"})
     *
     * @param ResponseJson $responseFactory
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function put(Post $post, ResponseJson $responseJson, UserRepository $userRepository , EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $user = $userRepository->findOneBy(['id' => $data->userId]);
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('UTC'));

        $post->setTitle($data->title)
            ->setBody($data->body)
            ->setTimestamp($date)
            ->setUser($user);

        $manager->persist($post);
        $manager->flush();

        return $responseJson->responseJson($post);
    }

    /**
     * Delete one resource of User
     *
     *
     * @Route("/{id}", name="delete_post", methods={"DELETE"})
     *
     * @param ResponseJson $responseJson
     * @param User $user
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function delete(ResponseJson $responseJson, Post $post, EntityManagerInterface $manager): JsonResponse
    {

        if($post->getParent()){
            $post->setParent(null);
        }

        $manager->persist($post);

        $manager->remove($post);
        $manager->flush();

        return $responseJson->emptyJson();
    }
}
