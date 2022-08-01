<?php

namespace App\Tests;
use App\Entity\TaskTodo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTodoTest extends KernelTestCase
{
    /**
     * @test
     */
    public function task_can_be_insert_in_database()
    {
        $taskTodo = new TaskTodo();
        $taskTodo->setTitle('Do something');
        $taskTodo->setStatus(false);
        $taskRepository = $this->createMock(ObjectRepository::class);
        $taskRepository->expects($this->any())
            ->method('find')
            ->willReturn($taskTodo);
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($taskTodo);

        $this->assertEquals('Do something', $taskTodo->getTitle());
        $this->assertEquals(false, $taskTodo->isStatus());
    }
}
