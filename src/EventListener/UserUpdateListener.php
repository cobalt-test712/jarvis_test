<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use  Psr\Log\LoggerInterface;
use App\Entity\User;

class UserUpdateListener{

	private $log;

	public function __construct(LoggerInterface $log){
		$this->log = $log;  
	}

    public function __invoke(User $user,LifecycleEventArgs $args)
    {
        $this->log->info("L'ustilisateur id = ".$user->getId()." firstname = " .$user->getFirstname()." a été modifié");
    }    
}