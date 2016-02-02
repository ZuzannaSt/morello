<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route(service="app.tasks_controller")
 */
class TasksController
{
    private $translator;
    private $templating;
    private $session;
    private $router;
    private $model;
    private $formFactory;

    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $model,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->model = $model;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/tasks", name="tasks")
     */
    public function indexAction()
    {
        $tasks = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Tasks:index.html.twig',
            array('tasks' => $tasks)
        );
    }

    /**
    * @Route("tasks/view/{id}", name="tasks-view")
    */

    public function viewAction($id)
    {
        $task = $this->model->findOneById($id);
        if (!$task) {
            throw $this->createNotFoundException(
                $this->translator->trans('No task found for id ') . $id
            );
        }

      $users = $task->getUsers();

      return $this->templating->renderResponse(
          'AppBundle:Tasks:view.html.twig',
          array('task' => $task, 'users' => $users)
      );
    }

    /**
     * @Route("/tasks/add", name="tasks-add")
     */
    public function addAction(Request $request)
    {
        $taskForm = $this->formFactory->create(new TaskType());

        $taskForm->handleRequest($request);

        if ($taskForm->isValid()) {
            $this->model->add($taskForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('Saved')
            );

            return new RedirectResponse($this->router->generate('tasks'));
        } else {
            $this->session->getFlashBag()->set(
                'error',
                $this->translator->trans('Not valid')
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Tasks:add.html.twig',
         array('form' => $taskForm->createView())
        );
    }

    /**
    *
    * @Route("/tasks/edit/{id}", name="tasks-edit")
    *
    */
    public function editAction(Request $request)
    {
        $id = $request->get('id', null);
        $task = $this->model->findById($id);

        if (!$task) {
            throw $this->createNotFoundException(
                $this->translator->trans('No task found for id ') . $id
            );
        }

        $taskForm = $this->formFactory->create(
            new TaskType(),
            current($task),
            array(
                'validation_groups' => 'task-edit'
                )
            );

        $taskForm->handleRequest($request);

        if ($taskForm->isValid()) {
            $this->model->save($taskForm->getData());
            return new RedirectResponse($this->router->generate('tasks'));
        }

          return $this->templating->renderResponse(
              'AppBundle:Tasks:edit.html.twig',
              array('form' => $taskForm->createView())
          );
    }

    /**
    * @Route("/tasks/delete/{id}", name="tasks-delete")
    */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id', null);
        $task = $this->model->findById($id);

        if (!$task) {
            throw $this->createNotFoundException('No task found');
        }

        $taskForm = $this->formFactory->create(
            new TaskType(),
            current($task),
            array(
                'validation_groups' => 'task-delete'
                )
            );

        $taskForm->handleRequest($request);

        if ($taskForm->isValid()) {
            $this->model->delete($taskForm->getData());
            return new RedirectResponse($this->router->generate('tasks'));
        }

          return $this->templating->renderResponse(
              'AppBundle:Tasks:delete.html.twig',
              array('form' => $taskForm->createView())
          );
      }
}
