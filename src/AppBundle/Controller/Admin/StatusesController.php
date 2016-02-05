<?php

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
 * Class StatusController
 * @Route(service="admin.statuses_controller")
 */
class StatusesController
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
     *
     * @param Id $id
     * @return Response
     * @Route("statuses/view/{id}", name="admin_statuses_view")
     *
     */
    public function viewAction($id)
    {
        $status = $this->model->findOneById($id);
        if (!$status) {
            throw $this->createNotFoundException(
                $this->translator->trans('errors.role.not_found') . $id
            );
        }

      $users = $status->getTasks();

      return $this->templating->renderResponse(
          'AppBundle:Admin/Status:view.html.twig',
          array('task' => $task, 'users' => $users)
      );
    }

    /**
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
                'flash_messages.role.add.success'
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
