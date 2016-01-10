<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\ProjectType;
use AppBundle\Form\ProjectEdit;
use AppBundle\Entity\Project;

class ProjectController extends Controller
{
    /**
     * @Route("/projects", name="projects")
     */
    public function indexAction()
    {
        $projects = $this->get('app.project_repository')
            ->findAllOrderedByName();

        return $this->render(
            'AppBundle:Project:index.html.twig',
            array('projects' => $projects)
        );
    }

     /**
     * @Route("projects/show/{id}", name="project_show")
     * @Method("GET")
     * @Template()
     */

     public function viewAction($id)
     {
       $em = $this->getDoctrine()->getManager();

       $project = $em->getRepository('AppBundle:Project')->find($id);

       if (!$project) {
           throw $this->createNotFoundException('Unable to find Project entity.');
       }

       $deleteForm = $this->createDeleteForm($id);

       return array(
           'project'      => $project,
           'delete_form' => $deleteForm->createView(),
       );
      }

    /**
     * @Route("/projects/new", name="project_new")
     */
    public function newAction(Request $request)
    {
        // 1) build the form
        $project = new Project();
        $form = $this->createCreateForm($project);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) save the Project!
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            // maybe set a "flash" success message for the user
            $redirectUrl = $this->generateUrl('projects');

            return $this->redirect($redirectUrl);
        }

        return $this->render(
            'AppBundle:Project:new.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Creates a form to create a Project entity.
     *
     * @param Project $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Project $project)
    {
        $form = $this->createForm(new ProjectType(), $project, array(
            'action' => $this->generateUrl('project_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
    * Creates a form to edit a Project entity.
    *
    * @param Project $entity The project
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Project $project)
    {
        $form = $this->createForm(new ProjectType(), $project, array(
            'action' => $this->generateUrl('project_update', array('id' => $project->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/projects/{id}/edit", name="project_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $editForm = $this->createEditForm($project);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'project'      => $project,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Project entity.
     *
     * @Route("projects/{id}", name="project_update")
     * @Method("PUT")
     * @Template("AppBundle:Project:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('AppBundle:Project')->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($project);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('project_edit', array('id' => $id)));
        }

        return array(
            'project'      => $project,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("projects/{id}", name="project_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('AppBundle:Project')->find($id);

            if (!$project) {
                throw $this->createNotFoundException('Unable to find Project entity.');
            }

            $em->remove($project);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('projects'));
    }

    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param mixed $id The project id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
