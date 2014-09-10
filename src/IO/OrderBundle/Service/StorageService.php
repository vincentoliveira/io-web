<?php

namespace IO\OrderBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of StockageService
 *
 * @author vincent
 * @Service("io.storage_service")
 */
class StorageService
{

    private static $_session_menu = 'io_menu';
    private static $_session_cart = 'io_cart';
    private static $_session_client = 'io_client';

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
        return$this->getSession()->get($name);
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
        return$this->getSession()->set($name, $value);
    }
    
    /**
     * Get menu
     * 
     * @return array
     */
    public function getMenu()
    {
        return $this->getSession()->get(self::$_session_menu);
    }

    /**
     * Set menu
     */
    public function setMenu($menu)
    {
        return $this->getSession()->set(self::$_session_menu, $menu);
    }


    /**
     * Get cart
     * 
     * @return array
     */
    public function getCart()
    {
        return $this->getSession()->get(self::$_session_cart);
    }

    /**
     * Set cart
     */
    public function setCart($cart)
    {
        return $this->getSession()->set(self::$_session_cart, $cart);
    }


    /**
     * Get client
     * 
     * @return array
     */
    public function getClient()
    {
        return $this->getSession()->get(self::$_session_client);
    }

    /**
     * Set client
     */
    public function setClient($cart)
    {
        return $this->getSession()->set(self::$_session_client, $cart);
    }
}
