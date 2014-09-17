<?php

namespace IO\OrderBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Templating\EngineInterface;

/**
 * Description of MailerService
 *
 * @author vincent
 * @Service("io.mailer_service")
 */
class MailerService
{

    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    
    /**
     * Templating engine
     * 
     * @Inject("twig")
     * @var EngineInterface 
     */
    public $templating;
    
    /**
     * Set client
     */
    public function clientOrderConfirmation($cart, $client)
    {
        $restaurantName = $this->container->getParameter('io_restaurant_name');
        if (empty($restaurantName)) {
            $restaurantName = 'InnovOrder';
        }

        $subject = sprintf('[%s] Commande #%d', $restaurantName, $cart['id']);
        $body = $this->templating->render('IOOrderBundle:Mail:clientOrderConfirmation.html.twig', array(
            'cart' => $cart,
            'client' => $client,
        ));
        //$this->container->get('templating')->renderResponse();
        $message = \Swift_Message::newInstance()
                ->setFrom('no-reply@innovorder.fr')
                ->setTo($client['user']['email'])
                ->setContentType('text/html')
                ->setSubject($subject)
                ->setBody($body);
        
        $this->container->get('mailer')->send($message);
    }

}
