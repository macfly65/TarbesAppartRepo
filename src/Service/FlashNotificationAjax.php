<?php

namespace App\Service;
use Twig\Environment;

class FlashNotificationAjax {
    
    private $twig;
    
    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }
    
    /**
     * Simulation d'une notification flash de Symfony en Ajax
     * @param string $type (success, danger)
     * @return string
     */
    public function notification($type, $message)
    {
        if(in_array($type, array('success', 'danger'))){
            return  $this->twig->render('layouts_admin/flash_message.html.twig', [
                        'app' => array('flashes' => array($type => array($message))),
            ]);
        }
        
        return  $this->twig->render('layouts_admin/flash_message.html.twig', [
                    'app' => array('flashes' => array('danger' => array('Erreur sur le type de notification'))),
        ]);            
    }
    
    
}