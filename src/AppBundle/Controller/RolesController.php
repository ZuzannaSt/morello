<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Form\RoleType;
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
 * @Route(service="app.roles_controller")
 */
class RolesController
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
     * @Route("/roles", name="roles")
     */
    public function indexAction()
    {
        $roles = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Roles:index.html.twig',
            array('roles' => $roles)
        );
    }

    /**
    * @Route("roles/view/{id}", name="roles-view")
    */

    public function viewAction($id)
    {
        $role = $this->model->findOneById($id);
        if (!$role) {
            throw $this->createNotFoundException(
                $this->translator->trans('No role found for id ') . $id
            );
        }

      $users = $role->getUsers();

      return $this->templating->renderResponse(
          'AppBundle:Roles:view.html.twig',
          array('role' => $role, 'users' => $users)
      );
    }

    /**
     * @Route("/roles/add", name="roles-add")
     */
    public function addAction(Request $request)
    {
        $roleForm = $this->formFactory->create(new RoleType());

        $roleForm->handleRequest($request);

        if ($roleForm->isValid()) {
            $this->model->save($roleForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('Saved')
            );

            return new RedirectResponse($this->router->generate('roles'));
        } else {
            $this->session->getFlashBag()->set(
                'error',
                $this->translator->trans('Not valid')
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Roles:add.html.twig',
         array('form' => $roleForm->createView())
        );
    }
}
