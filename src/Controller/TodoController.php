<?php

namespace App\Controller;

use App\Entity\TaskTodo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo")
     * @return Response
     */
    public function index2()
    {
        try {
            $tasktodo = $this->getDoctrine()->getRepository(TaskTodo::class)->findby([], ['id' => 'DESC']);
            return $this->render('todo/index.html.twig', [
                'controller_name' => 'TodoController',
                'tasktodo' => $tasktodo
            ]);
        }catch (NotFoundHttpException $e)
        {
            return $e->getStatusCode();
        }
    }

    /**
     * @Route("/create", name="app_create_task", methods={"POST"})
     *
     */
    public function create(Request $request)
    {
        $task = $request->request->get('task');
        try {
            if (empty($task)){
                return $this->redirectToRoute('todo');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $tasktodo = new TaskTodo();
            $tasktodo->setTitle($task);
            $entityManager->persist($tasktodo);
            $entityManager->flush();
            return $this->redirectToRoute('todo');
        }
        catch (RedirectionException $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @Route("/change-status/{id}", name="app_change_status")
     */
    public function changeStatus($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tasktodo = $entityManager->getRepository(TaskTodo::class)->find($id);

        if(!$tasktodo){
            throw $this->createNotFoundException();
        }

        $tasktodo->setStatus(!$tasktodo->isStatus());
        $entityManager->flush();

        try {
            return $this->redirectToRoute('todo');
        }
        catch (RedirectionException $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * @Route("/delete/{id}", name="app_task_delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tasktodo = $entityManager->getRepository(TaskTodo::class)->find($id);

        if(!$tasktodo){
            throw $this->createNotFoundException();
        }

        try {
            $entityManager->remove($tasktodo);
            $entityManager->flush();
            return $this->redirectToRoute('todo');
        }
        catch (RedirectionException $e)
        {
            return $e->getMessage();
        }
    }
}

