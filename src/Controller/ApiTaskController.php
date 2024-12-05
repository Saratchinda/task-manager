<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiTaskController extends AbstractController
{
    // 1. Création d'une tâche
    #[Route('/api/task', name: 'create_task', methods: ['POST'])]
    public function createTask(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Création de l'objet Task
        $task = new Task();
        $task->setTitle($data['title'] ?? '')
             ->setDescription($data['description'] ?? '')
             ->setStatus($data['status'] ?? 'todo');

        // Validation des données
        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400); // Retour des erreurs en cas de validation échouée
        }

        // Enregistrement de la tâche dans la base de données
        $em->persist($task);
        $em->flush();

        return $this->json([
            'message' => 'Tâche créée avec succès',
            'task' => [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $task->getUpdatedAt()->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    // 2. Modification d'une tâche
    #[Route('/api/task/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        TaskRepository $taskRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Tâche non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $task->setTitle($data['title'] ?? $task->getTitle())
             ->setDescription($data['description'] ?? $task->getDescription())
             ->setStatus($data['status'] ?? $task->getStatus());

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $task->setUpdatedAt(new \DateTimeImmutable()); // Mise à jour de la date de modification
        $em->flush();

        return $this->json([
            'message' => 'Tâche mise à jour avec succès',
            'task' => [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $task->getUpdatedAt()->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    // 3. Suppression d'une tâche
    #[Route('/api/task/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(
        int $id,
        EntityManagerInterface $em,
        TaskRepository $taskRepository
    ): JsonResponse {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['message' => 'Tâche non trouvée.'], 404);
        }

        $em->remove($task);
        $em->flush();

        return $this->json(['message' => 'Tâche supprimée avec succès']);
    }

    // 4. Liste paginée des tâches triées par statut
    #[Route('/api/tasks', name: 'list_tasks', methods: ['GET'])]
    public function listTasks(
        Request $request,
        TaskRepository $taskRepository
    ): JsonResponse {
        // Paramètre de la page
        $page = $request->query->getInt('page', 1); // Par défaut, page 1
        $tasks = $taskRepository->findBy([], ['status' => 'ASC'], 10, ($page - 1) * 10);

        return $this->json($tasks);
    }

    // 5. Recherche par titre ou description
    #[Route('/api/tasks/search', name: 'search_tasks', methods: ['GET'])]
    public function searchTasks(
        Request $request,
        TaskRepository $taskRepository
    ): JsonResponse {
        $query = $request->query->get('query', ''); // Récupère le paramètre de recherche
        $tasks = $taskRepository->searchByTitleOrDescription($query);

        return $this->json($tasks);
    }
}
