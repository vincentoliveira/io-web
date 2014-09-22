<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class JoinController extends DefaultController
{

    /**
     * @Route("/contacteznous", name="join")
     * @Template("IODefaultBundle:Contact:join.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
