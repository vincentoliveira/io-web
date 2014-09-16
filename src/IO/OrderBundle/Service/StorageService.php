<?php

namespace IO\OrderBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * Description of StorageService
 *
 * @author vincent
 * @Service("io.storage_service")
 */
class StorageService
{

    private static $_session_menu = 'io_menu';
    private static $_session_menu_next_update = 'io_menu_lastupdate';
    private static $_session_cart = 'io_cart';
    private static $_session_client = 'io_client';
    
    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     *
     * @var Session
     */
    private $session;

    public function getSession()
    {
        if (!$this->session) {
            $this->session = new Session();
        }

        return $this->session;
    }

    /**
     * Get value
     * 
     * @param type $name
     * @return type
     */
    public function get($name)
    {
        $token = $this->container->getParameter('io_auth_token');
        return $this->getSession()->get($token . $name);
    }

    /**
     * Set value
     * 
     * @param type $name
     * @param type $value
     * @return type
     */
    public function set($name, $value)
    {
        $token = $this->container->getParameter('io_auth_token');
        return $this->getSession()->set($token . $name, $value);
    }
    
    /**
     * Get menu
     * 
     * @return array
     */
    public function getMenu()
    {
        $nextUpdate = $this->get(self::$_session_menu_next_update);
        $now = new \DateTime();
        if ($nextUpdate && $nextUpdate > $now) {
            return $this->get(self::$_session_menu);
        } else {
            echo 'reset';
            return null;
        }
    }

    /**
     * Set menu
     */
    public function setMenu($menu)
    {
        $nextUpdate = new \DateTime();
        $nextUpdate->add(new \DateInterval('PT1M'));
        $this->set(self::$_session_menu_next_update, $nextUpdate);
        return $this->set(self::$_session_menu, $menu);
    }

    /**
     * Get cart
     * 
     * @return array
     */
    public function getCart()
    {
        return $this->get(self::$_session_cart);
    }

    /**
     * Set cart
     */
    public function setCart($cart)
    {
        return $this->set(self::$_session_cart, $cart);
    }


    /**
     * Get client
     * 
     * @return array
     */
    public function getClient()
    {
        return $this->get(self::$_session_client);
    }

    /**
     * Set client
     */
    public function setClient($cart)
    {
        return $this->set(self::$_session_client, $cart);
    }
}
