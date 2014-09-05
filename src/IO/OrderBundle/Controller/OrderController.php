<?php

namespace IO\OrderBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class OrderController extends BaseController
{
    /**
     * @Route("/carte", name="menu")
     * @Template()
     */
    public function menuAction()
    {
        return array();
    }
    
    
    /**
     * @Route("/panier", name="cart")
     * @Template()
     */
    public function cartAction()
    {
        return array();
    }
}
