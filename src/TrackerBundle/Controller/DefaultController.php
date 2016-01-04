<?php

namespace TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{

    /**
     * @Route("/tracker/{name}")
     */
    public function indexAction($name)
    {
        return $this->render('TrackerBundle:Default:index.html.twig', array('name' => $name));
    }
}
