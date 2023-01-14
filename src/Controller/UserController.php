<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
   
 
    #[Route('/users', name: 'user_list',methods:["GET"])]
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->transformAll();

        return $this->json($users);
    }

  
    #[Route('/users', name: 'user_create',methods:["POST"])]
    public function create(Request $request, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Invalid data'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if ($user) {
            return $this->json([
                'message' => 'User with this email already exists'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setActif($data['actif']);
        $user->setDateCreation(new \DateTime());

        $em->persist($user);
        $em->flush();

        return $this->json($userRepository->transform($user));
    }

   
    #[Route('/users/{id}', name: 'user_show',methods:["GET"])]
    public function show($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($userRepository->transform($user));
    }

   
    #[Route('/users/{id}', name: 'user_update',methods:["PUT"])]
    public function update($id, Request $request, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Invalid data'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->setEmail($data['email']);
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setActif($data['actif']);
        $user->setDateCreation(new \DateTime());

        $em->persist($user);
        $em->flush();

        return $this->json($userRepository->transform($user));
    }
}
