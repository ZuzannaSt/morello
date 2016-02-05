<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Board;
use AppBundle\Form\BoardType;
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
 * Class BoardsController
 * @Route(service="app.projects_boards_controller")
 *
 */
class BoardsController
{
    private $translator;
    private $templating;
    private $session;
    private $router;
    private $model;
    private $project_model;
    private $formFactory;
    private $securityContext;

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
     * @Route("/projects/{project_id}/boards", name="project_boards")
     */
    public function indexAction()
    {
        $boards = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Projects/Boards:index.html.twig',
            array('boards' => $boards)
        );
    }

    /**
     *
     * @param Id
     * @return Response
     * @Route("/projects/{project_id}/boards/{id}/view", name="project_boards_view")
     *
     */
    public function viewAction($id)
    {
        $board = $this->model->findOneById($id);
        if (!$board) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.board.not_found') . $id
            );
        }

      $users = $board->getUsers();

      return $this->templating->renderResponse(
          'AppBundle:Projects/Boards:view.html.twig',
          array('board' => $board, 'users' => $users)
      );
    }

    /**
     *
     * @param Request $request
     * @return Response
     * @Route("/projects/{project_id}/boards/add", name="project_boards_add")
     *
     */
    public function addAction(Request $request)
    {
        if (!$this->securityContext->isGranted('ROLE_MANAGER')) {
          throw new AccessDeniedException();
        }

        $project_id = $request->get('project_id', null);

        $boardForm = $this->formFactory->create(new BoardType());
        $boardForm->handleRequest($request);

        if ($boardForm->isValid()) {
            $this->model->add($boardForm->getData(), $project_id);
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.board.add.success'
            );

            $redirectUri = $this->router->generate('project_boards', array('project_id' => $project_id));
            return new RedirectResponse($redirectUri);
        } else {
            $this->session->getFlashBag()->set(
                'error',
                'flash_messages.board.add.error'
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Projects/Boards:add.html.twig',
         array('form' => $boardForm->createView())
        );
    }

    /**
    *
    * @param Request $request
    * @return Response
    * @Route("/projects/{project_id}/boards/{id}/edit", name="project_boards_edit")
    *
    */
    public function editAction(Request $request)
    {
        if (!$this->securityContext->isGranted('ROLE_MANAGER')) {
          throw new AccessDeniedException();
        }
        $project_id = $request->get('project_id', null);
        $id = $request->get('id', null);
        $board = $this->model->findById($id);

        if (!$board) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.board.not_found') . $id
            );
        }

        $boardForm = $this->formFactory->create(
            new BoardType(),
            current($board),
            array(
                'validation_groups' => 'board-edit'
                )
            );

        $boardForm->handleRequest($request);

        if ($boardForm->isValid()) {
            $this->model->save($boardForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.project.edit.success'
            );

            $redirectUri = $this->router->generate('project_boards', array('project_id' => $project_id));
            return new RedirectResponse($redirectUri);
        } else {
            $this->session->getFlashBag()->set(
                'error',
                'flash_messages.project.edit.error'
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Projects/Boards:edit.html.twig',
            array('form' => $boardForm->createView())
        );
    }

    /**
    *
    * @param Request $request
    * @return Respons
    * @Route("/projects/{project_id}/boards/delete/{id}", name="project_boards_delete")
    *
    */
    public function deleteAction(Request $request)
    {
        if (!$this->securityContext->isGranted('ROLE_MANAGER')) {
          throw new AccessDeniedException();
        }
        $project_id = $request->get('project_id', null);
        $id = $request->get('id', null);
        $board = $this->model->findById($id);

        if (!$board) {
            throw $this->createNotFoundException('errors.board.not_found');
        }

        $boardForm = $this->formFactory->create(
            new BoardType(),
            current($board),
            array(
                'validation_groups' => 'board-delete'
                )
            );

        $boardForm->handleRequest($request);

        if ($boardForm->isValid()) {
            $this->model->delete($boardForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.board.delete.success'
            );

            return new RedirectResponse($this->router->generate('project_boards', array('project_id' => $project_id)));
        }

          return $this->templating->renderResponse(
              'AppBundle:Projects/Boards:delete.html.twig',
              array('form' => $boardForm->createView())
          );
      }
}