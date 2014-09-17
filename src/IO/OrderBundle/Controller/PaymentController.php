<?php

namespace IO\OrderBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Route("/payment")
 * 
 */
class PaymentController extends BaseController
{
    /**
     * Storage Service
     * 
     * @Inject("io.storage_service")
     * @var \IO\OrderBundle\Service\StorageService
     */
    public $storage;

    /**
     * ApiClient Service
     * 
     * @Inject("io.api_client_service")
     * @var \IO\OrderBundle\Service\ApiClientService
     */
    public $apiClient;

    /**
     * MangoPay Service
     * 
     * @Inject("io.mango_pay_service")
     * @var \IO\OrderBundle\Service\MangoPayService
     */
    public $mangoPay;

    /**
     * MangoPay Service
     * 
     * @Inject("io.mailer_service")
     * @var \IO\OrderBundle\Service\MailerService
     */
    public $mailerSv;

    /**
     * @Route("/", name="payment_index")
     * @Template()
     */
    public function indexAction()
    {
        $cart = $this->storage->getCart();
        $client = $this->storage->getClient();
        if ($client === null || $cart === null || !isset($cart['validated']) || !$cart['validated']) {
            return $this->redirect($this->generateUrl('menu'));
        }
        return array();
    }

    /**
     * @Route("/payment", name="payment_payment")
     * @Template()
     */
    public function paymentAction()
    {
        $users = $this->mangoPay->getAllUsers();
        
        echo '<pre>';
        print_r($users);
        die;
    }
    
    /**
     * @Route("/validate/no_payment", name="payment_validate_without_payment")
     * @Template("IOOrderBundle:Payment:index.html.twig")
     * @Method("POST")
     */
    public function validateWithoutPaymentAction()
    {
        $cart = $this->storage->getCart();
        $client = $this->storage->getClient();
        if ($client === null || $cart === null || !isset($cart['validated']) || !$cart['validated']) {
            return $this->redirect($this->generateUrl('menu'));
        }
                    
        $deliveryDate = $this->storage->get('client_delivery_date');
        $orderType = $this->storage->get('order_type');
        $newCart = $this->apiClient->validateCart($cart, $client, $deliveryDate, $orderType);
        if ($newCart) {
            $newCart['validated'] = true;
            $this->storage->setCart($newCart);
            return $this->redirect($this->generateUrl('payment_validated'));
        }
        
        return array(
            'error' => 'Une erreur s\'est produite. veuillez réessayer ultérieurement.',
        );
    }

    
    /**
     * @Route("/validated", name="payment_validated")
     * @Template()
     */
    public function validatedAction()
    {
        $cart = $this->storage->getCart();
        $client = $this->storage->getClient();
        if ($client === null || $cart === null || !isset($cart['validated']) || !$cart['validated'] || !$cart['delivery_date']) {
            return $this->redirect($this->generateUrl('menu'));
        }
        
        // TODO: send email
        $this->mailerSv->clientOrderConfirmation($cart, $client);
        
        $this->storage->setCart(null);
        
        return array('validated_cart' => $cart);
    }
}
