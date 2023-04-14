<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Manager\HotelManager;
use App\Service\RequestService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    /** 
     * Obtener el listado de registros
     */
    #[Route('/api/hotel', name: 'api_hotel_index', methods: ['GET'])]
    public function index(
        ManagerRegistry $doctrine,
    ): JsonResponse
    {
        $records = $doctrine
            ->getRepository(Hotel::class)
            ->findAll();

        return $this->json([
            'message' => 'Registros retornados!',
            'records' => $records,
        ]);
    }

    /**
     * Obtener un registro específico por ID
     */
    #[Route('/api/hotel/{id}', name: 'api_hotel_show', methods: ['GET'])]
    public function show(
        int $id,
        ManagerRegistry $doctrine,
    ): JsonResponse
    {
        $record = $doctrine
            ->getRepository(Hotel::class)
            ->find($id);

        if (!$record) {
            return $this->json([
                'message' => 'No se encontraron registros!',
                'record' => null,
            ], 400);
        }

        return $this->json([
            'message' => 'Registro retornado!',
            'record' => $record,
        ]);
    }
    
    /**
     * Agregar un nuevo registro
     */
    #[Route('/api/hotel', name: 'api_hotel_new', methods: ['POST'])]
    public function new(
        RequestService $requestService,
        HotelManager $manager,
        ManagerRegistry $doctrine,
    ): JsonResponse
    {
        $data = $requestService->getContent();

        $records = $doctrine
            ->getRepository(Hotel::class)
            ->findBy(['nombre' => $data['nombre']]);
        
        if (count($records) == 0) {
            $record = $manager->save($data);

            return $this->json([
                'message' => 'Registro creado correctamente!',
                'record' => $record,
            ]);
        } else {
            return $this->json([
                'message' => 'El nombre de hotel ya se encuentra registrado',
                'record' => null,
            ], 400);
        }
    }

    /**
     * Editar un registro existente
     */
    #[Route('/api/hotel/{id}', name: 'api_hotel_edit', methods: ['PUT'])]
    public function edit(
        int $id,
        ManagerRegistry $doctrine,
        RequestService $requestService,
        HotelManager $manager,
    ): JsonResponse
    {
        $record = $doctrine
            ->getRepository(Hotel::class)
            ->find($id);
        if (!$record) {
            return $this->json([
                'message' => 'No se encontro el registro!',
                'record' => null,
            ], 400);
        }

        $data = $requestService->getContent();

        $record = $manager->save($data, $id);

        return $this->json([
            'message' => 'Hotel actualizado correctamente!',
            'record' => $record,
        ]);
    }

    /**
     * Eliminar un registro
     */
    #[Route('/api/hotel/{id}', name: 'api_hotel_delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        ManagerRegistry $doctrine,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $record = $doctrine
            ->getRepository(Hotel::class)
            ->find($id);
        if (!$record) {
            return $this->json([
                'message' => 'No se encontró el registro!',
                'record' => null,
            ], 400);
        }

        $entityManager->remove($record);
        $entityManager->flush();

        return $this->json([
            'message' => 'Hotel eliminado correctamente!',
            'record' => $record,
        ]);
    }
}
