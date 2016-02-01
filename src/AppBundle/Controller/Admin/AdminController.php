<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
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
    private $em;
    private $current_user;
    private $model;

    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        $router,
        EntityManager $entityManager,
        $securityContext,
        ObjectRepository $model
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->model = $model;
        $this->em = $entityManager;
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
}
