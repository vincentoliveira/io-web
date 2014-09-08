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
        return array();
    }
}
