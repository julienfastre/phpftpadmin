<?php

namespace Fastre\PhpFtpAdminBundle\Services\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Fastre\PhpFtpAdminBundle\Entity\Account;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Listen to the creation of new accounts
 *
 * @author julienfastre
 */
class CreateAccountListener {
    
    private $path;
    
    private $inside_folder;
    
    public function __construct($path, $inside_folder) {
        $this->path = $path;
        $this->inside_folder = trim($inside_folder);
        
        
        $fs = new Filesystem();
        
        if (!$fs->exists($path)) {
            $fs->mkdir($path, 0700);
        }
        
    }
    
    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        
        if ($entity instanceof Account) {
            $fs = new Filesystem();
            
            $fs->mkdir($this->path.'/'.$entity->getUsername());
            
            if($this->inside_folder != '') {
                $folders = split('/', $this->inside_folder);
                
                if (count($folders) === 0) {
                    $folders = array($this->inside_folder);
                }
            }
            
            $complete_path = $this->path.'/'.$entity->getUsername();
            
            foreach($folders as $folder) {
                $complete_path .= '/'.$folder;
                $fs->mkdir($complete_path);
            }
        }
    }
    
    
}


