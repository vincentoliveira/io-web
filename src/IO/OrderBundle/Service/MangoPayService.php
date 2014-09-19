<?php

namespace IO\OrderBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use MangoPay\MangoPayApi;

/**
 * Description of MangoPAy Api Client Service
 *
 * @author vincent
 * @Service("io.mango_pay_service")
 */
class MangoPayService
{
    
    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;
    
    /**
     * @var MangoPayApi
     */
    protected $api = null;
    
    /**
     * Config Mango Pay api
     * 
     * @return \MangoPay\MangoPayApi
     */
    protected function mangoPayApi()
    {
        if ($this->api) {
            return $api;
        }
        
        $this->api = new MangoPayApi();
        $baseUrl = $this->container->getParameter('mango_api_base_url');
        $clientId = $this->container->getParameter('mango_api_client_id');
        $clientPassword = $this->container->getParameter('mango_api_client_password');
        $tmpFolder = $this->container->getParameter('mango_api_temporary_folder');
        
        $this->api->Config->BaseUrl = $baseUrl;
        $this->api->Config->ClientId = $clientId;
        $this->api->Config->ClientPassword = $clientPassword;
        $this->api->Config->TemporaryFolder = $tmpFolder;
        
        return $this->api;
    }
    
    /**
     * Get innovorder user id
     * 
     * @return int
     */
    protected function getRestaurantUserId()
    {
        return $this->container->getParameter('mango_restaurant_user_id');
    }
    
    /**
     * Get innovorder wallet id
     * 
     * @return int
     */
    protected function getRestaurantWalletId()
    {
        return $this->container->getParameter('mango_restaurant_wallet_id');
    }
    
    protected function getCurrency()
    {
        return "EUR";
    }
    
    /**
     * Get innovorder wallet id
     * 
     * @return int
     */
    protected function getInnovorderFees()
    {
        return $this->container->getParameter('io_fees');
    }
    
    public function getExecutionDetails()
    {
        $executionDetails = new \MangoPay\PayInExecutionDetailsWeb();
        $executionDetails->ReturnURL = $this->container->get('router')->generate('payment_payment_callback', array(), true);
        $executionDetails->Culture = 'FR';
        $executionDetails->SecureMode = 'DEFAULT';
        return $executionDetails;
    }
    
    public function getPaymentDetails()
    {
        $paymentDetails = new \MangoPay\PayInPaymentDetailsCard();
        $paymentDetails->CardType = "CB_VISA_MASTERCARD";
        return $paymentDetails;
    }
    
    /**
     * Get Payin by id
     * 
     * @param string $id
     * @return \MangoPay\PayIn
     */
    public function getPayIn($id)
    {
        $api = $this->mangoPayApi();
        return $api->PayIns->Get($id);
    }
    
    public function createUserAndWallet($io_user)
    {
        $io_wallet = array();
        if (!isset($io_user['wallet']) ||
                empty($io_user['wallet']['user_id'])) {
            $mango_user = $this->createUser($io_user);
            if (!$mango_user) {
                return null;
            }
            $io_wallet['user_id'] = $mango_user->Id;
            $io_user['user']['wallet'] = $io_wallet;
        }
        
        if (!isset($io_user['wallet']) ||
                empty($io_user['wallet']['wallet_id'])) {
            $mango_wallet = $this->createWallet($io_user);
            if (!$mango_wallet) {
                return null;
            }
            $io_wallet['wallet_id'] = $mango_user->Id;
        }
        
        return $io_wallet;
    }
    
    /**
     * 
     * @param array $user
     * @return \MangoPay\User
     */
    public function createUser($io_user)
    {
        return null;
    }
    
    /**
     * 
     * @param array $user
     * @return \MangoPay\Wallet
     */
    public function createWallet($io_user)
    {
        return null;
    }
    
    /**
     * Create payment
     * 
     * @param type $user
     * @param type $cart
     * @return \MangoPay\PayIn
     */
    public function createPayIn($user, $cart)
    {
        if (!isset($user['wallet'])) {
            return null;
        }
        
        $currency = $this->getCurrency();
        $totalAmount = 100 * $cart['total_unpayed'];
        $feesAmount = $this->calculateFeesAmount($totalAmount);
        $executionDetails = $this->getExecutionDetails();
        $paymentDetails = $this->getPaymentDetails();
        
        $payIn = new \MangoPay\PayIn();
        $payIn->AuthorId = $user['wallet']['user_id'];;
        $payIn->DebitedWalletId = $user['wallet']['wallet_id'];
        $payIn->CreditedUserId = $this->getRestaurantUserId();
        $payIn->CreditedWalletId = $this->getRestaurantWalletId();
        $payIn->DebitedFunds = $this->getMoney($totalAmount, $currency);
        $payIn->Fees = $this->getMoney($feesAmount, $currency);
        $payIn->CreditedFunds = $this->getMoney($totalAmount - $feesAmount, $currency);
        $payIn->ExecutionType = "WEB";
        $payIn->ExecutionDetails = $executionDetails;
        $payIn->PaymentDetails = $paymentDetails;
        $payIn->Tag = $this->getOrderTag($cart);
        
        $api = $this->mangoPayApi();
        return $api->PayIns->Create($payIn);
    }
    
    public function getOrderTag($cart)
    {
        return 'io-order-' . $cart['id'];
    }


    protected function getMoney($amount, $currency)
    {
        $money = new \MangoPay\Money();
        $money->Amount = intval($amount);
        $money->Currency = $currency;
        
        return $money;
    }
    
    protected function calculateFeesAmount($totalAmount)
    {
        $fees = $this->getInnovorderFees();
        
        $amount = $fees['fixed'];
        $amount += round($totalAmount * $fees['percent'] / 100);
        
        return $amount;
    }
            
}
