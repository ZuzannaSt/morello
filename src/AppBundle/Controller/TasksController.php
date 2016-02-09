<?php

/**
 * TasksController
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

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
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 *
 * Class TasksController
 * @package AppBundle\Controller
 * @Route(service="app.projects_tasks_controller")
 *
 */
class TasksController
{
    /**
     * Translator
     * @access private
     * @var Translator
     */
    private $translator;

    /**
     * Templating
     * @access private
     * @var EngineInterface
     */
    private $templating;

    /**
     * Router
     * @access private
     * @var RouterInterface
     */
    private $router;

    /**
     * Session
     * @access private
     * @var Session
     */
    private $session;

    /**
     * Model
     * @access private
     * @var ObjectRepository
     */
    private $model;

    /**
     * Project Model
     * @access private
     * @var ObjectRepository
     */
    private $project_model;

    /**
     * Form factory
     * @access private
     * @var FormFactory
     */
    private $formFactory;

    /**
     * SecurityContext
     * @access private
     * @var
     */
    private $securityContext;

    /**
     * TasksController constructor.
     * @param Translator $translator
     * @param EngineInterface $templating
     * @param Session $session
     * @param RouterInterface $router
     * @param ObjectRepository $model
     * @param ObjectRepository $project_model
     * @param FormFactory $formFactory
     * @param $securityContext
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $model,
        ObjectRepository $project_model,
        FormFactory $formFactory,
        $securityContext
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->model = $model;
        $this->project_model = $project_model;
        $this->formFactory = $formFactory;
        $this->securityContext = $securityContext;
    }

    /**
     * View Action
     *
     * @param Request $request
     * @param Id
     * @return Response
     * @Route("/projects/{project_id}/boards/{board_id}/tasks/{id}/view", name="project_board_tasks_view")
     *
     */
    public function viewAction(Request $request, $id)
    {
        $task = $this->model->findOneById($id);
        $project_id = $request->get('project_id', null);
        $board_id = $request->get('board_id', null);

        if (!$task) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.task.not_found') . $id
            );
        }

        $users = $task->getUsers();

        return $this->templating->renderResponse(
            'AppBundle:Projects/Boards/Tasks:view.html.twig',
            array(
                'task' => $task,
                'users' => $users,
                'project_id' => $project_id,
                'board_id' => $board_id
            )
        );
    }

    /**
     * Add Action
     *
     * @param Request $request
     * @return Response
     * @Route("/projects/{project_id}/boards/{board_id}/tasks/add", name="project_board_tasks_add")
     *
     */
    public function addAction(Request $request)
    {
        $project_id = $request->get('project_id', null);
        $board_id = $request->get('board_id', null);

        $taskForm = $this->formFactory->create(new TaskType());
        $taskForm->handleRequest($request);

        if ($taskForm->isValid()) {
            $this->model->add($taskForm->getData(), $board_id);
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.task.add.success'
            );

            $redirectUri = $this->router->generate(
                'project_boards_index',
                array(
                    'project_id' => $project_id,
                    'board_id' => $board_id
                )
            );
            return new RedirectResponse($redirectUri);
        } else {
            $this->session->getFlashBag()->set(
                'notice',
                'flash_messages.task.add.notice'
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Projects/Boards/Tasks:add.html.twig',
            array('form' => $taskForm->createView())
        );
    }

    /**
     * Edit Action
     *
     * @param Request $request
     * @return Response
     * @Route("/projects/{project_id}/boards/{board_id}/tasks/{id}/edit", name="project_board_tasks_edit")
     *
     */
    public function editAction(Request $request)
    {
        $project_id = $request->get('project_id', null);
        $board_id = $request->get('board_id', null);
        $id = $request->get('id', null);
        $task = $this->model->findById($id);

        if (!$task) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.task.not_found') . $id
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
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.project.edit.success'
            );

            $redirectUri = $this->router->generate(
                'project_boards_index',
                array(
                    'project_id' => $project_id,
                    'board_id' => $board_id
                )
            );
            return new RedirectResponse($redirectUri);
        } else {
            $this->session->getFlashBag()->set(
                'notice',
                'flash_messages.project.edit.notice'
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Projects/Boards/Tasks:edit.html.twig',
            array('form' => $taskForm->createView())
        );
    }

    /**
     * Delete Action
     *
     * @param Request $request
     * @return Respons
     * @Route("/projects/{project_id}/tasks/boards/{board_id}/delete/{id}", name="project_board_tasks_delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $project_id = $request->get('project_id', null);
        $board_id = $request->get('board_id', null);
        $id = $request->get('id', null);
        $task = $this->model->findById($id);

        if (!$task) {
            throw $this->createNotFoundException('errors.task.not_found');
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
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.task.delete.success'
            );

            return new RedirectResponse($this->router->generate(
                'project_boards_index',
                array(
                    'project_id' => $project_id
                )
            ));
        }

          return $this->templating->renderResponse(
              'AppBundle:Projects/Boards/Tasks:delete.html.twig',
              array('form' => $taskForm->createView())
          );
    }
}
