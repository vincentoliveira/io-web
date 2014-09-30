<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends DefaultController
{

    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function homeAction()
    {
        return array();
    }
}
