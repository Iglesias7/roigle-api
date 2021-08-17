<?php

namespace App\Controller;

use App\Entity\Comment;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/comments", name="comments_")
 */
class CommentController extends AbstractController
{

    /**
     * Get single resource of User.
     *
     * @Route("/{id}", name="get-comment", methods={"GET"})
     *
     * @param ResponseJson $responseJson
     * @param Comment $comment
     *
     * @return JsonResponse
     */
    public function getOne(ResponseJson $responseJson, Comment $comment): JsonResponse
    {
        return $responseJson->responseJson($comment);
    }

    /**
     * @Route("", name="add-comment", methods={"POST"})
     *
     * @param ResponseJson $responseJson
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function post(ResponseJson $responseJson, UserRepository $userRepository, PostRepository $postRepository, EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $user = $userRepository->findOneBy(['id' => $data->userId]);
        $post = $postRepository->findOneBy(['id' => $data->postId]);

        $comment = new Comment();

        $comment->setBody($data->body)
                ->setTimestamp(new \DateTime())
                ->setPost($post)
                ->setUser($user);

        $manager->persist($comment);
        $manager->flush();

        return $responseJson->responseJson($comment);
    }

    /**
     * @Route("/{id}", name="update_comment", methods={"PUT"})
     *
     * @param Comment $comment
     * @param ResponseJson $responseFactory
     * @param EntityManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function put(Comment $comment, ResponseJson $responseFactory, UserRepository $responseJson , EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $user = $responseJson->findOneBy(['id' => $data->userId]);

        $comment->setBody($data->body)
                ->setTimestamp(new \DateTime())
                ->setUser($user);

        $manager->persist($comment);
        $manager->flush();

        return $responseFactory->responseJson($comment);
    }

    /**
     * Delete one resource of User
     *
     *
     * @Route("/{id}", name="delete-comment", methods={"DELETE"})
     *
     * @param ResponseJson $responseJson
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     *
     * @return JsonResponse
     */
    public function delete(ResponseJson $responseJson, Comment $comment, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($comment);
        $manager->flush();

        return $responseJson->responseJson();
    }
}
