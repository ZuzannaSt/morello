<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
    private $current_user;
    private $model;

    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        $router,
        $securityContext,
        ObjectRepository $model
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->model = $model;
        $this->router = $router;
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
     * @Route("/users", name="admin_users_list")
     */
    public function usersListAction()
    {
        $users = $this->userModel->findAll();

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:list.html.twig',
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
     * @Route("/users/{userId}", name="admin_user_view")
     */
    public function userShowAction($user_id)
    {
        $user = $this->userModel->find($user_id);

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:view.html.twig',
            array('user' => $user)
        );
    }


    /**
     * @param Request $request
     * @param $user_id
     * @return Response
     * @Route("/users/{userId}/edit", name="admin_user_edit")
     */
    public function userEditAction(Request $request, $user_id)
    {
        $user = $this->userModel->find($user_id);
        $adminUserForm = $this->formFactory->create(new UserEditType(), $user);
        $adminUserForm->handleRequest($request);

        if ($adminUserForm->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.user.edit.success'
            );

            $redirectUri = $this->router->generate('admin_user_show', array('userId' => $user->getId()));

            return new RedirectResponse($redirectUri);
        }

        return $this->templating->renderResponse(
            'AppBundle:Admin/Users:create.html.twig',
            array(
                'form' => $adminUserForm->createView(),
                'user' => $user
            )
        );
    }

    /**
     * @param $user_id
     * @return Response
     * @Route("/users/{userId}/delete", name="admin_user_delete")
     */

    public function userDeleteAction($user_id)
    {
        $user = $this->userModel->find($user_id);
        $this->em->remove($user);
        $this->em->flush();

        $redirectUri = $this->router->generate('admin_users_list');
        return new RedirectResponse($redirectUri);
    }
}
