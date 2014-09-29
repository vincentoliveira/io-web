<?php

namespace IO\OrderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
        $cart = $this->storage->getCart();
        $client = $this->storage->getClient();

        if ($client === null || $cart === null || !isset($cart['validated']) || !$cart['validated']) {
            return $this->redirect($this->generateUrl('menu'));
        }

        if (!isset($client['user']['wallet']) ||
                empty($client['user']['wallet']['user_id']) ||
                empty($client['user']['wallet']['wallet_id'])) {
            $wallet = $this->mangoPay->createUserAndWallet($client['user']);
            if (!$wallet) {
                // Erreur... on arrête tout !
                throw new \Exception('Echec de la création du Wallet MangoPay');
            }
            //save wallet in InnovOrder API
            $client['user']['wallet'] = $wallet;

            $user = $this->apiClient->editUser($client['user']);
            if ($user === null) {
                throw new \Exception('Echec de l\'assignation du Wallet InnovOrder');
            }

            $client['user'] = $user;
            $this->storage->setClient($client);
        } else {
            $wallet = $client['user']['wallet'];
        }

        $payment = $this->mangoPay->createPayIn($client['user'], $cart);

        return $this->redirect($payment->ExecutionDetails->RedirectURL);
    }

    /**
     * @Route("/payment/callback", name="payment_payment_callback")
     * @Template()
     */
    public function paymentCallbackAction(Request $request)
    {
        $transactionId = $request->query->get('transactionId');
        if ($transactionId === null) {
            return $this->redirect($this->generateUrl('menu'));
        }

        $payIn = $this->mangoPay->getPayIn($transactionId);
        $cart = $this->storage->getCart();
        if ($payIn === null || $payIn->Tag !== $this->mangoPay->getOrderTag($cart)) {
            return $this->redirect($this->generateUrl('menu'));
        }
        
        // validate order
        $client = $this->storage->getClient();
        $this->validateCart($cart, $client);

        //Payment call
        $order = $this->apiClient->paymentResult($cart, $payIn);
        if ($order) {
            $order['validated'] = true;
            $this->storage->setCart($order);
        }
        
        return $this->redirect($this->generateUrl('payment_validated'));
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

        $response = $this->validateCart($cart, $client);
        if ($response) {
            return $response;
        }
        
        return array(
            'error' => 'Une erreur s\'est produite. Veuillez réessayer ultérieurement.',
        );
    }
    
    /**
     * Validate cart
     * 
     * @param type $cart
     * @param type $client
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function validateCart($cart, $client)
    {
        $deliveryDate = $this->storage->get('client_delivery_date');
        $orderType = $this->storage->get('order_type');
        $newCart = $this->apiClient->validateCart($cart, $client, $deliveryDate, $orderType);
        if ($newCart === null) {
            return null;
        }
        
        $newCart['validated'] = true;
        $this->storage->setCart($newCart);
        return $this->redirect($this->generateUrl('payment_validated'));
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

        $this->mailerSv->clientOrderConfirmation($cart, $client);
        $this->storage->setCart(null);

        return array('validated_cart' => $cart);
    }

}
