<?php

/**
 * DashboardController
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * Class DashboardController
 * @package AppBundle\Controller
 * @Route(service="app.dashboard_controller")
 *
 */
class DashboardController
{
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var null
     */
    private $current_user;

    /**
     * DashboardController constructor.
     * @param Translator $translator
     * @param EngineInterface $templating
     * @param $securityContext
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        $securityContext
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $user = null;
        $token = $securityContext->getToken();
        if (null !== $token && is_object($token->getUser())) {
            $this->current_user = $token->getUser();
        } else {
            $this->current_user = null;
        }
    }
    /**
     *
     * @Route("/dashboard", name="user_dashboard")
     * @return Response
     *
     */
    public function displayAction()
    {
        return $this->templating->renderResponse(
            'AppBundle:Default:dashboard.html.twig',
            array(
                'current_user_name' => $this->current_user->getFullName(),
                'user' => $this->current_user
            )
        );
    }

    /**
     * @param $user_id
     * @return Response
     * @Route("/user_profile", name="user_profile")
     */
    public function userViewAction()
    {
        return $this->templating->renderResponse(
            'AppBundle:Default:user_profile.html.twig',
            array('user' => $this->current_user)
        );
    }
}
