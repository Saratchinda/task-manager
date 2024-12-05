<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ApiTaskControllerTest extends TestCase
{
    public function testCreateTask()
    {
        $client = static::createClient();
        
        // Données pour la création de la tâche
        $data = [
            'title' => 'Test Task',
            'description' => 'Test description',
            'status' => 'todo'
        ];

        // Envoi de la requête POST
        $client->request('POST', '/api/task', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérification de la réponse
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED); // Vérifie le code HTTP 201
        $this->assertJsonContains(['message' => 'Tâche créée avec succès']); // Vérifie le message de succès
    }

    public function testUpdateTask()
    {
        $client = static::createClient();
        
        // Créer une tâche avant la mise à jour
        $client->request('POST', '/api/task', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Old Task',
            'description' => 'Old description',
            'status' => 'todo'
        ]));
        $response = $client->getResponse();
        $taskId = json_decode($response->getContent(), true)['task']['id'];

        // Données de mise à jour
        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 'in_progress'
        ];

        // Envoi de la requête PUT
        $client->request('PUT', "/api/task/{$taskId}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        // Vérification de la réponse
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // Vérifie le code HTTP 200
        $this->assertJsonContains(['message' => 'Tâche mise à jour avec succès']); // Vérifie le message de succès
    }

    public function testDeleteTask()
    {
        $client = static::createClient();

        // Créer une tâche avant la suppression
        $client->request('POST', '/api/task', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Task to Delete',
            'description' => 'Task description',
            'status' => 'todo'
        ]));
        $response = $client->getResponse();
        $taskId = json_decode($response->getContent(), true)['task']['id'];

        // Envoi de la requête DELETE
        $client->request('DELETE', "/api/task/{$taskId}");

        // Vérification de la réponse
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // Vérifie le code HTTP 200
        $this->assertJsonContains(['message' => 'Tâche supprimée avec succès']); // Vérifie le message de succès
    }

    public function testListTasks()
    {
        $client = static::createClient();

        // Envoi de la requête GET pour lister les tâches
        $client->request('GET', '/api/tasks');

        // Vérification de la réponse
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // Vérifie le code HTTP 200
        $this->assertJsonStructure([
            '*' => ['id', 'title', 'description', 'status', 'created_at', 'updated_at'] // Vérifie la structure de la réponse JSON
        ]);
    }
}
