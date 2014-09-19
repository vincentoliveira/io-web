<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HistoryController extends DefaultController
{

    /**
     * @Route("/notrehistoire", name="history")
     * @Template("IODefaultBundle:About:history.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
