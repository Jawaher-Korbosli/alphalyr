<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends AbstractController
{
  
    #[Route('/groups', name: 'group_list',methods:["GET"])]
    public function list(GroupRepository $groupRepository): JsonResponse
    {
        $groups = $groupRepository->transformAll();

        return $this->json($groups);
    }

  
    #[Route('/groups', name: 'group_create',methods:["POST"])]
    public function create(Request $request, GroupRepository $groupRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Invalid data'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group = $groupRepository->findOneBy(['nom' => $data['nom']]);

        if ($group) {
            return $this->json([
                'message' => 'Group with this nom already exists'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group = new Group();
        $group->setNom($data['nom']);

        $em->persist($group);
        $em->flush();

        return $this->json($groupRepository->transform($group));
    }


    #[Route('/groups/{id}', name: 'group_show',methods:["GET"])]
    public function show($id, GroupRepository $groupRepository): JsonResponse
    {
        $group = $groupRepository->find($id);

        if (!$group) {
            return $this->json([
                'message' => 'Group not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($groupRepository->transform($group));
    }

 
    #[Route('/groups/{id}', name: 'group_update',methods:["PUT"])]
    public function update($id, Request $request, GroupRepository $groupRepository, EntityManagerInterface $em): JsonResponse
    {
        $group = $groupRepository->find($id);

        if (!$group) {
            return $this->json([
                'message' => 'Group not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Invalid data'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group->setNom($data['nom']);

        $em->persist($group);
        $em->flush();

        return $this->json($groupRepository->transform($group));
    }

    
}
