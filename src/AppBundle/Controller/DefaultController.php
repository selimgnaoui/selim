<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }
    /**
     * @Route("/show", name="show")
     */
    public function showAction(Request $request)
    {   // get the service
        $autoService = $this->container->get('auto_service');
        $autos=$autoService->fetchApi();
        $autoService->savetoDatabase($autos);
        return $this->render('default/show.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,'autos'=>$result
        ));
    }




}
