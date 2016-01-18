<?php

namespace AppBundle\Controller;

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
     * @Route("projects/view/{id}", name="projects-view")
     */

     public function viewAction($id)
     {
         $project = $this->model->find($id);
         if (!$project) {
             throw $this->createNotFoundException(
                $this->translator->trans('No project found for id ') . $id
             );
       }

       return $this->templating->renderResponse(
           'AppBundle:Projects:view.html.twig',
           array('project' => $project)
       );
      }

    /**
     * @Route("/projects/add", name="projects-add")
     */
    public function addAction(Request $request)
    {
        $projectForm = $this->formFactory->create(new ProjectType());

        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $this->model->save($projectForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('Saved')
            );

            return new RedirectResponse($this->router->generate('projects'));
        } else {
            $this->session->getFlashBag()->set(
                'error',
                $this->translator->trans('Not valid')
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Projects:add.html.twig',
         array('form' => $projectForm->createView())
        );
    }

    /**
    *
    * @Route("/projects/edit/{id}", name="projects-edit")
    *
    */
    public function editAction($id)
    {
    }

    /**
    * @Route("/projects/delete/{id}", name="projects-delete")
    */
    public function deleteAction($id)
    {
    }
}
