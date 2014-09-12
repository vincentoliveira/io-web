<?php

namespace IO\DefaultBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MentionsLegalesController extends BaseController {

    /**
     * @Route("/mentionslegales", name="mentions_legales")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

}
