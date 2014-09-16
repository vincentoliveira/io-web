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
     * Config Mango Pay api
     * 
     * @return \MangoPay\MangoPayApi
     */
    protected function mangoPayApi()
    {
        $api = new MangoPayApi();
        $baseUrl = $this->container->getParameter('mango_api_base_url');
        $clientId = $this->container->getParameter('mango_api_client_id');
        $clientPassword = $this->container->getParameter('mango_api_client_password');
        $tmpFolder = $this->container->getParameter('mango_api_temporary_folder');
        
        $api->Config->BaseUrl = $baseUrl;
        $api->Config->ClientId = $clientId;
        $api->Config->ClientPassword = $clientPassword;
        $api->Config->TemporaryFolder = $tmpFolder;
        
        return $api;
    }
    
    /**
     * Get innovorder user id
     * 
     * @return int
     */
    protected function getInnovOrderUserId()
    {
        return $this->container->getParameter('mango_innovorder_user_id');
    }
    
    /**
     * Get innovorder wallet id
     * 
     * @return int
     */
    protected function getInnovOrderWalletId()
    {
        return $this->container->getParameter('mango_innovorder_wallet_id');
    }
    
    public function getAllUsers()
    {
        $api = $this->mangoPayApi();
        
        $userId = $this->getInnovOrderUserId();
        
        $users = $api->Users->GetWallets($userId);
        return $users;
    }
}
