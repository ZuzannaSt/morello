<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
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
 * Class ProjectsController
 * @Route(service="app.projects_controller")
 */
class ProjectsController
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
     * @Route("/projects", name="projects")
     */
    public function indexAction()
    {
        $projects = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Projects:index.html.twig',
            array('projects' => $projects)
        );
    }

    /**
    * @Route("projects/{id}/view", name="projects-view")
    */

    public function viewAction($id)
    {
        $project = $this->model->findOneById($id);
        if (!$project) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.project.not_found') . $id
            );
        }

      $users = $project->getUsers();
      $tasks = $project->getTasks();

      return $this->templating->renderResponse(
          'AppBundle:Projects:view.html.twig',
          array(
              'project' => $project,
              'users' => $users,
              'tasks' => $tasks
              )
      );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/projects/add", name="projects-add")
     */
    public function addAction(Request $request)
    {
        $projectForm = $this->formFactory->create(new ProjectType());
        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $this->model->add($projectForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.project.add.success'
            );

            $redirectUri = $this->router->generate('projects-view', array('project_id' => $this->model->getId()));

            return new RedirectResponse($redirectUri);
        } else {
            $this->session->getFlashBag()->set(
                'error',
                'flash_messages.project.add.error'
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Projects:add.html.twig',
         array('form' => $projectForm->createView())
        );
    }

    /**
    *
    * @param Request $request
    * @return Response
    * @Route("/projects/{id}/edit", name="projects-edit")
    *
    */
    public function editAction(Request $request)
    {
        $id = $request->get('id', null);
        $project = $this->model->findById($id);

        if (!$project) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.project.not_found') . $id
            );
        }

        $projectForm = $this->formFactory->create(
            new ProjectType(),
            current($project),
            array(
                'validation_groups' => 'project-edit'
                )
            );

        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $this->model->save($projectForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.project.edit.success'
            );

            $redirectUri = $this->router->generate('projects-view', array('id' => $id));

            return new RedirectResponse($redirectUri);
        } else {
            $this->session->getFlashBag()->set(
                'error',
                'flash_messages.project.edit.error'
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Projects:edit.html.twig',
            array('form' => $projectForm->createView())
        );
    }

    /**
    * @param Request $request
    * @return Response
    * @Route("/projects/{id}/delete", name="projects-delete")
    */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id', null);
        $project = $this->model->findById($id);

        if (!$project) {
            throw $this->createNotFoundException('errors.project.not_found');
        }

        $projectForm = $this->formFactory->create(
            new ProjectType(),
            current($project),
            array(
                'validation_groups' => 'project-delete'
                )
            );

        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $this->model->delete($projectForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.project.delete.success'
            );

            return new RedirectResponse($this->router->generate('projects'));
        }

          return $this->templating->renderResponse(
              'AppBundle:Projects:delete.html.twig',
              array('form' => $projectForm->createView())
          );
      }
}
