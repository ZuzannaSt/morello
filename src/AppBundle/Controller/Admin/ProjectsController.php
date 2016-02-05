<?php

namespace AppBundle\Controller\Admin;

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
 *
 * Class ProjectsController
 * @package AppBundle\Controller\Admin
 * @Route(service="admin.projects_controller")
 *
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
     *
     * @return Response
     * @Route("/projects", name="projects")
     *
     */
    public function indexAction()
    {
        $projects = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Admin/Projects:index.html.twig',
            array('projects' => $projects)
        );
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/projects/add", name="admin_projects_add")
     */
    public function addAction(Request $request)
    {
        $projectForm = $this->formFactory->create(new ProjectType());
        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $project = $projectForm->getData();
            $this->model->add($project);
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.project.add.success'
            );

            $redirectUri = $this->router->generate('projects_view', array('id' => $project->getId()));
            return new RedirectResponse($redirectUri);
        } elseif ($projectForm->isValid()) {
            $this->session->getFlashBag()->set(
                'notice',
                'flash_messages.project.add.notice'
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Admin/Projects:add.html.twig',
         array('form' => $projectForm->createView())
        );
    }

    /**
    *
    * @param Request $request
    * @return Response
    * @Route("/projects/{id}/delete", name="admin_projects_delete")
    *
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
              'AppBundle:Admin/Projects:delete.html.twig',
              array('form' => $projectForm->createView())
          );
      }
}
