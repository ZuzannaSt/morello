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
 * @Route(service="app.projects_tasks_controller")
 */
class TasksController
{
    private $translator;
    private $templating;
    private $session;
    private $router;
    private $model;
    private $project_model;
    private $formFactory;

    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $model,
        ObjectRepository $project_model,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->model = $model;
        $this->project_model = $project_model;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/projects/{project_id}/tasks", name="projects-tasks")
     */
    public function indexAction()
    {
        $tasks = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Projects/Tasks:index.html.twig',
            array('tasks' => $tasks)
        );
    }

    /**
    * @Route("/projects/{project_id}/tasks/view/{id}", name="projects-tasks-view")
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
          'AppBundle:Projects/Tasks:view.html.twig',
          array('task' => $task, 'users' => $users)
      );
    }

    /**
     * @Route("/projects/{project_id}/tasks/add", name="projects-tasks-add")
     */
    public function addAction(Request $request)
    {
        $project_id = $request->get('project_id', null);

        $taskForm = $this->formFactory->create(new TaskType());
        $taskForm->handleRequest($request);

        if ($taskForm->isValid()) {
            $this->model->add($taskForm->getData(), $project_id);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('Saved')
            );

            return new RedirectResponse($this->router->generate('projects-tasks', array('project_id' => $project_id)));
        } else {
            $this->session->getFlashBag()->set(
                'error',
                $this->translator->trans('Not valid')
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Projects/Tasks:add.html.twig',
         array('form' => $taskForm->createView())
        );
    }

    /**
    *
    * @Route("/projects/{project_id}/tasks/edit/{id}", name="projects-tasks-edit")
    *
    */
    public function editAction(Request $request)
    {
        $project_id = $request->get('project_id', null);
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
            return new RedirectResponse($this->router->generate('projects-tasks', array('project_id' => $project_id)));
        }

          return $this->templating->renderResponse(
              'AppBundle:Projects/Tasks:edit.html.twig',
              array('form' => $taskForm->createView())
          );
    }

    /**
    * @Route("/projects/{project_id}/tasks/delete/{id}", name="projects-tasks-delete")
    */
    public function deleteAction(Request $request)
    {
        $project_id = $request->get('project_id', null);
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
            return new RedirectResponse($this->router->generate('projects-tasks', array('project_id' => $project_id)));
        }

          return $this->templating->renderResponse(
              'AppBundle:Projects/Tasks:delete.html.twig',
              array('form' => $taskForm->createView())
          );
      }
}
