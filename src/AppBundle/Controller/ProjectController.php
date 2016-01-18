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
class ProjectController
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
            'AppBundle:Project:index.html.twig',
            array('projects' => $projects)
        );
    }

     /**
     * @Route("projects/view/{id}", name="project_view")
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
           'AppBundle:Project:view.html.twig',
           array('project' => $project)
       );
      }

    /**
     * @Route("/projects/new", name="project_new")
     */
    public function createAction(Request $request)
    {
        $projectForm = $this->formFactory->create(new ProjectType());

        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('Saved')
            );
        } else {
            $this->session->getFlashBag()->set(
                'error',
                $this->translator->trans('Not valid')
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Project:new.html.twig',
         array('form' => $projectForm->createView())
        );
    }

    /**
    *
    * @Route("/projects/edit/{id}", name="project_edit")
    *
    */
    public function editAction($id)
    {
    }

    /**
    * @Route("/projects/delete/{id}", name="project_delete")
    */
    public function deleteAction(Request $request)
    {
      $id = $request->get('id', null);
      $project = $this->model->find($id);
      if (!$project) {
           throw $this->createNotFoundException(
               $this->translator->trans('No project found for id ') . $id
           );
       }

       $projectForm = $this->formFactory->create(
           new ProjectType(),
           $project,
           array(
               'validation_groups' => 'project_delete'
           )
       );

       $projectForm->handleRequest($request);

       if ($projectForm->isValid()) {
          remove($project);
       }

       return $this->templating->renderResponse(
           'AppBundle:Project:delete.html.twig',
           array('form' => $currencyForm->createView())
       );

    }
}
