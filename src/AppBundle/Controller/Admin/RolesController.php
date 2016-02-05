<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Role;
use AppBundle\Form\Admin\RoleType;
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
 * Class RolesController
 * @Route(service="admin.roles_controller")
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
     *
     * @return Response
     * @Route("/roles", name="admin_roles_index")
     *
     */
    public function indexAction()
    {
        $roles = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Admin/Roles:index.html.twig',
            array('roles' => $roles)
        );
    }

    /**
     *
     * @param Id $id
     * @return Response
     * @Route("roles/view/{id}", name="admin_role_view")
     *
     */
    public function viewAction($id)
    {
        $role = $this->model->findOneById($id);
        if (!$role) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.role.not_found') . $id
            );
        }

      $users = $role->getUsers();

      return $this->templating->renderResponse(
          'AppBundle:Admin/Roles:view.html.twig',
          array('role' => $role, 'users' => $users)
      );
    }

    /**
     *
     * @param Request $request
     * @return Response
     * @Route("/roles/add", name="admin_role_add")
     *
     */
    public function addAction(Request $request)
    {
        $roleForm = $this->formFactory->create(new RoleType());
        $roleForm->handleRequest($request);

        if ($roleForm->isValid()) {
            $this->model->save($roleForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.role.add.success'
            );

            return new RedirectResponse($this->router->generate('roles'));
        } else {
            $this->session->getFlashBag()->set(
                'error',
                'flash_messages.project.add.error'
            );
        }

        return $this->templating->renderResponse(
         'AppBundle:Admin/Roles:add.html.twig',
         array('form' => $roleForm->createView())
        );
    }
}
