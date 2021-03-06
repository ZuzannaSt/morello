<?php

/**
 * StatusesController
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Statu;
use AppBundle\Form\Admin\StatusType;
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
 * Class StatusesController
 * @package AppBundle\Controller\Admin
 * @Route(service="admin.statuses_controller")
 */
class StatusesController
{
    /**
     * Translator
     * @access private
     * @var Translator
     */
    private $translator;

    /**
     * Templating
     * @access private
     * @var EngineInterface
     */
    private $templating;

    /**
     * Router
     * @access private
     * @var RouterInterface
     */
    private $router;

    /**
     * Session
     * @access private
     * @var Session
     */
    private $session;

    /**
     * Model
     * @access private
     * @var ObjectRepository
     */
    private $model;

    /**
     * Form factory
     * @access private
     * @var FormFactory
     */
    private $formFactory;

    /**
     * StatusesController constructor.
     * @param Translator $translator
     * @param EngineInterface $templating
     * @param Session $session
     * @param RouterInterface $router
     * @param ObjectRepository $model
     * @param FormFactory $formFactory
     */
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
     * Index Action
     *
     * @return Response
     * @Route("/statuses", name="admin_statuses_index")
     *
     */
    public function indexAction()
    {
        $statuses = $this->model->findAllOrderedByName();

        return $this->templating->renderResponse(
            'AppBundle:Admin/Status:index.html.twig',
            array('statuses' => $statuses)
        );
    }

    /**
     * Add Action
     *
     * @param Request $request
     * @return Response
     * @Route("/statuses/add", name="admin_statuses_add")
     *
     */
    public function addAction(Request $request)
    {
        $statusForm = $this->formFactory->create(new StatusType());
        $statusForm->handleRequest($request);

        if ($statusForm->isValid()) {
            $this->model->save($statusForm->getData());
            $this->session->getFlashBag()->set(
                'success',
                'flash_messages.status.add.success'
            );

            return new RedirectResponse($this->router->generate('admin_statuses_index'));
        } else {
            $this->session->getFlashBag()->set(
                'notice',
                'flash_messages.status.add.notice'
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Admin/Status:add.html.twig',
            array('form' => $statusForm->createView())
        );
    }
}
