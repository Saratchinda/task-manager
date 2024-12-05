<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskSettersAndGetters()
    {
        // Création de la tâche
        $task = new Task();
        $task->setTitle('My Task');
        $task->setDescription('Task Description');
        $task->setStatus('todo');
        
        // Assertions pour vérifier les setters et getters
        $this->assertEquals('My Task', $task->getTitle());
        $this->assertEquals('Task Description', $task->getDescription());
        $this->assertEquals('todo', $task->getStatus());
    }

    public function testCreatedAtAndUpdatedAt()
    {
        $task = new Task();
        $createdAt = $task->getCreatedAt();
        $updatedAt = $task->getUpdatedAt();

        // Vérification que CreatedAt et UpdatedAt sont bien des instances de DateTimeImmutable
        $this->assertInstanceOf(\DateTimeImmutable::class, $createdAt);
        $this->assertInstanceOf(\DateTimeImmutable::class, $updatedAt);
    }
}
