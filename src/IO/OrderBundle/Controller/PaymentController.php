<?php

namespace IO\OrderBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Route("/payment")
 * 
 */
class PaymentController extends BaseController
{
    /**
     * Stockage Service
     * 
     * @Inject("io.storage_service")
     * @var \IO\OrderBundle\Service\StorageService
     */
    public $stockage;

    /**
     * @Route("/", name="payment_index")
     * @Template()
     */
    public function indexAction()
    {
        $cart = $this->stockage->getCart();
        $client = $this->stockage->getClient();
        if ($client === null || $cart === null || !isset($cart['order_type'])) {
            return $this->redirect($this->generateUrl('menu'));
        }
        return array();
    }

}
