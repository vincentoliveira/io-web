<?php

namespace IO\DefaultBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class KidnapMamaController extends BaseController {

    /**
     * @Route("/traiteur", name="kidnap_mama")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

}
