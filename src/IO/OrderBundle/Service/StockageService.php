<?php

namespace IO\OrderBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of StockageService
 *
 * @author vincent
 * @Service("io.stockage_service")
 */
class StockageService
{

    private static $_session_menu = 'io_menu';
    private static $_session_cart = 'io_cart';

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
}
