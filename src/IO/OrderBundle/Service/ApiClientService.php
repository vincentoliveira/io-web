<?php

namespace IO\OrderBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;


/**
 * Description of ApiClientService
 *
 * @author vincent
 * @Service("io.api_client_service")
 */
class ApiClientService
{
 
    protected $headers;
    
    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;
    
    /**
     * Get base api url
     * 
     * @return string API base url
     */
    protected function getBaseApiUrl()
    {
        return $this->container->getParameter('io_api_base_url');
    }
    
    /**
     * Get restaurant auth token
     * 
     * @return string Restaurant auth token
     */
    protected function getRestaurantToken()
    {
        return $this->container->getParameter('io_auth_token');
    }
    
    /**
     * Load menu
     * 
     * @return array
     */
    public function loadMenu()
    {
        $baseUrl = $this->getBaseApiUrl();
        $authToken = $this->getRestaurantToken();
        $url = sprintf("%s/restaurant/menu/token/%s.json", $baseUrl, $authToken);

        $jsonResults = $this->restCall($url);
        $result = json_decode($jsonResults, true);
        
        if (!isset($result['carte'])) {
            return null;
        }
        
        return $result['carte'];
    }
    
    /**
     * Add product to cart. Create cart if not setted.
     * 
     * @param type $cart
     * @param type $productId
     * @param type $options
     * @return null
     */
    public function addProduct($cart, $productId, $options = array())
    {
        $baseUrl = $this->getBaseApiUrl();
        $authToken = $this->getRestaurantToken();
        
        $data = array(
            'token' => $authToken,
            'product_id' => $productId,
            'options' => $options
        );
            
        if ($cart) {
            $cartId = intval($cart['id']);
            $data['cart_id'] = $cartId;
            $method = "POST";
            $url = sprintf("%s/order/cart/add_product.json", $baseUrl);
        } else {
            $method = "POST";
            $url = sprintf("%s/order/cart/create.json", $baseUrl);
        }
        
        $jsonResults = $this->restCall($url, $data, $method);
        $result = json_decode($jsonResults, true);

        if (!isset($result['cart'])) {
            return null;
        }

        return $result['cart'];
    }
    
    /**
     * Add product to cart. Create cart if not setted.
     * 
     * @param type $cart
     * @param type $productId
     * @param type $options
     * @return null
     */
    public function removeProduct($cart, $productId, $extra = "")
    {
        $baseUrl = $this->getBaseApiUrl();
        $authToken = $this->getRestaurantToken();
        
        $data = array(
            'token' => $authToken,
            'product_id' => $productId,
            'cart_id' => intval($cart['id']),
            'extra' => $extra,
        );
            
        $url = sprintf("%s/order/cart/remove_product.json", $baseUrl);
        $jsonResults = $this->restCall($url, $data, "DELETE");
        $result = json_decode($jsonResults, true);

        if (!isset($result['cart'])) {
            return null;
        }

        return $result['cart'];
    }
    
    /**
     * Anthenticate user
     * 
     * @param array $data
     * @return client token or null
     */
    public function authenticate(array $data)
    {
        $baseUrl = $this->getBaseApiUrl();
            
        $url = sprintf("%s/client/auth.json", $baseUrl);
        $jsonResults = $this->restCall($url, $data, "POST");
        $result = json_decode($jsonResults, true);
        if (!isset($result['client_token'])) {
            return null;
        }
        
        return $result['client_token'];
    }

    
    /**
     * Register new user
     * 
     * @param array $data
     * @return client token or null
     */
    public function register(array $data)
    {
        $baseUrl = $this->getBaseApiUrl();
            
        $url = sprintf("%s/client/create.json", $baseUrl);
        $jsonResults = $this->restCall($url, $data, "POST");
        $result = json_decode($jsonResults, true);
        if (!isset($result['client_token'])) {
            return null;
        }
        
        return $result['client_token'];
    }

    
    /**
     * Validate cart
     * 
     * @param array $data
     * @return client token or null
     */
    public function validateCart($cart, $client, $deliveryDate, $orderType)
    {
        $baseUrl = $this->getBaseApiUrl();
            
        $restaurantToken = $this->getRestaurantToken();
        $data = array(
            'restaurant_token' => $restaurantToken,
            'client_token' => $client['token'],
            'cart_id' => $cart['id'],
            'delivery_date' => $deliveryDate->format('Y-m-d H:i:s'),
            'order_type' => $orderType,
        );
        
        $url = sprintf("%s/order/cart/validate.json", $baseUrl);
        $jsonResults = $this->restCall($url, $data, "POST");
        $result = json_decode($jsonResults, true);
        if (!isset($result['cart'])) {
            return null;
        }
        
        return $result['cart'];
    }
    
    /**
     * Rest call. Return response.
     * 
     * @param string $url
     * @param array $data
     * @param string $method GET, POST, PUT or DELETE
     * @return type
     */
    protected function restCall($url, $data = array(), $method = "GET")
    {        
        $query_data = http_build_query($data);
        
        $opts = array(
            'http' => array(
                'method' => $method,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($query_data) . "\r\n",
                'content' => $query_data,
            )
        );
        
        $context = stream_context_create($opts);
        if (($stream = @fopen($url, 'r', false, $context)) !== false) {
            $response = stream_get_contents($stream);
            fclose($stream);
            return $response;
         }

         $this->headers = $http_response_header;
         
        return null;
    }
}
