<?php
namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\Admin\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AdminController
 * @package AppBundle\Controller\Admin
 * @Route(service="admin.admin_controller")
 */
class AdminController
{
    private $translator;
    private $templating;
    private $router;
    private $session;
    private $user_model;
    private $formFactory;
    private $current_user;

    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        RouterInterface $router,
        Session $session,
        ObjectRepository $user_model,
        FormFactory $formFactory,
        $securityContext
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->router = $router;
        $this->session = $session;
        $this->user_model = $user_model;
        $this->formFactory = $formFactory;
        $user = null;
        $token = $securityContext->getToken();
        if (null !== $token && is_object($token->getUser())) {
            $this->current_user = $token->getUser();
        } else {
            $this->current_user = null;
        }
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function indexAction()
    {
        return $this->templating->renderResponse(
            'AppBundle:Admin:dashboard.html.twig',
            array(
                'current_user_name' => $this->current_user->getFullName()
            )
        );
    }

    /**
     * @return Response
     * @Route("/users", name="admin_users_index")
     */
    public function usersIndexAction()
    {
        $users = $this->user_model->findAll();

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:index.html.twig',
            array('users' => $users)
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/users/add", name="admin_user_add")
     */
    public function userAddAction(Request $request)
    {
        $user = new User();
        $adminUserForm = $this->formFactory->create(new UserType(), $user);
        $adminUserForm->handleRequest($request);

        if ($adminUserForm->isValid()) {
            $encoder = $this->encoder
                ->getEncoder($user);
            $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
            $user->setPassword($password);
            $this->em->persist($user);
            $this->em->flush();

            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.user.add.success'
            );

            $redirectUri = $this->router->generate('admin_user_view', array('user_id' => $user->getId()));

            return new RedirectResponse($redirectUri);
        }

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:create.html.twig',
            array('form' => $adminUserForm->createView())
        );
    }

    /**
     * @param $user_id
     * @return Response
     * @Route("/users/{user_id}/view", name="admin_user_view")
     */
    public function userViewAction($user_id)
    {
        $user = $this->user_model->find($user_id);

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:view.html.twig',
            array('user' => $user)
        );
    }


    /**
     * @param Request $request
     * @param $user_id
     * @return Response
     * @Route("/users/{user_id}/edit", name="admin_user_edit")
     */
    public function userEditAction(Request $request)
    {
        $user_id = $request->get('user_id', null);
        $user = $this->user_model->findById($user_id);

        if (!$user) {
            throw $this->createNotFoundException(
                $this->translator->trans('No user found for id ') . $id
            );
        }

        $adminUserForm = $this->formFactory->create(
            new UserType(),
            current($user),
            array(
                'validation_groups' => 'user-edit'
                )
            );

        $adminUserForm->handleRequest($request);

        if ($adminUserForm->isValid()) {
            $this->user_model->save($adminUserForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.user.edit.success'
            );

            $redirectUri = $this->router->generate('admin_user_view', array('user_id' => $user_id));
            return new RedirectResponse($redirectUri);
        }

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:add.html.twig',
            array('form' => $adminUserForm->createView())
        );
    }

    /**
     * @param $user_id
     * @return Response
     * @Route("/users/{user_id}/delete", name="admin_user_delete")
     */

    public function userDeleteAction(Request $request)
    {
        $user_id = $request->get('user_id', null);
        $user = $this->user_model->findById($user_id);

        if (!$user) {
            throw $this->createNotFoundException('No user found');
        }

        $adminUserForm = $this->formFactory->create(
            new UserType(),
            current($user),
            array(
                'validation_groups' => 'user-delete'
                )
            );

        $adminUserForm->handleRequest($request);

        if ($adminUserForm->isValid()) {
            $this->user_model->delete($adminUserForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.user.delete.success'
            );

            $redirectUri = $this->router->generate('admin_users_index');
            return new RedirectResponse($redirectUri);
        }

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:delete.html.twig',
            array('form' => $adminUserForm->createView())
        );
    }
}
