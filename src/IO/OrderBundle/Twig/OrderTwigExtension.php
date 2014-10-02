<?php

namespace IO\OrderBundle\Twig;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * Order TwigExtension
 * 
 * @Service("io.order_twig_extension")
 * @Tag("twig.extension")
 */
class OrderTwigExtension extends \Twig_Extension
{

    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     * Storage Service
     * 
     * @Inject("io.storage_service")
     * @var \IO\OrderBundle\Service\StorageService
     */
    public $storage;

    /**
     * {@inheritDoc}
     */
    public function getGlobals()
    {
        return array(
            'cart' => $this->storage->getCart(),
            'client' => $this->storage->getClient(),
            'order_type' => $this->storage->get('order_type'),
            'restaurantName' => $this->container->getParameter('io_restaurant_name'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'apiMedia' => new \Twig_SimpleFilter('apiMedia', array($this, 'apiMediaFilter')),
            'total_price' => new \Twig_SimpleFilter('total_price', array($this, 'totalPriceFilter')),
            'ordonate_products' => new \Twig_SimpleFilter('ordonate_products', array($this, 'ordonateProductsFilter')),
            'product_media' => new \Twig_SimpleFilter('product_media', array($this, 'productMediaFilter')),
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'order_time_options' => new \Twig_SimpleFunction('order_time_options', array($this, 'orderTimeOptionsFunction')),
        );
    }

    /**
     * Return api media path
     * 
     * @return string
     */
    public function apiMediaFilter($mediaPath)
    {
        return $this->container->getParameter('io_api_base_url') . '/../media/' . $mediaPath;
    }

    /**
     * 
     * @param type $cart
     */
    public function totalPriceFilter($cart)
    {
        if ($cart === null) {
            return 0;
        }

        $total = 0;
        foreach ($cart['products'] as $product) {
            $total += $product['price'];
        }

        return $total;
    }

    /**
     * Ordonate product for cart
     * 
     * @param array $products
     * @return array
     */
    public function ordonateProductsFilter($products)
    {
        $result = array();

        foreach ($products as $product) {
            $id = $product['product_id'];
            $extra = $product['extra'];

            $key = $id . '-' . $extra;
            if (!isset($result[$key])) {
                $result[$key] = array(
                    'count' => 1,
                    'product' => $product,
                );
            } else {
                $result[$key]['count'] ++;
            }
        }

        return $result;
    }

    /**
     * Get product media from product id
     * 
     * @param int $productId
     * @return string
     */
    public function productMediaFilter($productId)
    {
        $menu = $this->storage->getMenu();
        if (!$menu) {
            return null;
        }
        
        foreach ($menu as $category) {
            foreach ($category['products'] as $product) {
                if ($product['id'] === $productId) {
                    return $product['media']['path'];
                }
            }
        }

        return null;
    }

    /**
     * Generate option list for order time
     * 
     * @return string
     */
    public function orderTimeOptionsFunction()
    {
        $options = "";
        
        $timeServices = $this->container->getParameter('restaurant_time_service');
        
        $currentTime = time() % 86400;
        
        foreach ($timeServices as $timeService) {
            $startTime = strtotime($timeService["start"]) % 86400;
            $endTime = strtotime($timeService["end"]) % 86400;
            
            for ($time = $startTime; $time < $endTime; $time += 900) {
                if ($time > $currentTime + 1800) {
                    // TODO: Fix timezone
                    $timeStr = sprintf('%d:%02d', 2 + $time / 3600, ($time / 60) % 60);
                    $options .= sprintf('<option value="%s">%s</option>', $timeStr, $timeStr);
                }
            }
        }
        
        return $options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_twig_extension';
    }

}
