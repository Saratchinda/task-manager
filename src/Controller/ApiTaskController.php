<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiTaskController extends AbstractController
{
    // GET /api/task : Récupérer toutes les tâches
    #[Route('/api/task', name: 'app_api_task_get_all', methods: ['GET'])]
    public function getAllTasks(TaskRepository $taskRepository): JsonResponse
    {
        $tasks = $taskRepository->findAll();

        // Convertir les objets Task en tableaux associatifs pour JSON
        $taskData = array_map(function (Task $task) {
            return [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $task->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $tasks);

        return $this->json($taskData);
    }

    // GET /api/task/{id} : Récupérer une tâche spécifique
    #[Route('/api/task/{id}', name: 'app_api_task_get_one', methods: ['GET'])]
    public function getTask(TaskRepository $taskRepository, int $id): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Task not found'], 404);
        }

        $taskData = [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $task->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];

        return $this->json($taskData);
    }

    // POST : Créer une nouvelle tâche
    #[Route('/api/task', name: 'app_api_task_create', methods: ['POST'])]
    public function createTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title'] ?? 'Untitled Task');
        $task->setDescription($data['description'] ?? 'No description');
        $task->setStatus($data['status'] ?? 'todo');
        $task->setCreatedAt(new \DateTimeImmutable());
        $task->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json(['message' => 'Task created successfully', 'id' => $task->getId()], 201);
    }

    // PUT : Mettre à jour une tâche existante
    #[Route('/api/task/{id}', name: 'app_api_task_update', methods: ['PUT'])]
    public function updateTask(Request $request, TaskRepository $taskRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Task not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setStatus($data['status'] ?? $task->getStatus());
        $task->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        return $this->json(['message' => 'Task updated successfully']);
    }

    // DELETE  : Supprimer une tâche
    #[Route('/api/task/{id}', name: 'app_api_task_delete', methods: ['DELETE'])]
    public function deleteTask(TaskRepository $taskRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Task not found'], 404);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json(['message' => 'Task deleted successfully']);
    }
}
