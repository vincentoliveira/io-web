<?php

namespace IO\DefaultBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MamaResponsableController extends BaseController
{
    /**
     * @Route("/mama", name="mama_responsable")
     * @Template()
     */
    
    
    public function indexAction()
    {
        $list = array(
            array('id' => 1, 'title' => "Premier article", 'date' => new \DateTime()),
            array('id' => 2, 'title' => "Deuxième article", 'date' => new \DateTime()),
            array('id' => 3, 'title' => "Troisième article", 'date' => new \DateTime())
        );
        return $this->render('IODefaultBundle:MamaResponsable:index.html.twig',
                array('articles' => $list));
    }
}
