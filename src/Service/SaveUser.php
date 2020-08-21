<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;



class SaveUser{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
	    $this->em = $em; 
	}

    /**
     * Enregistre un utilisateur.
     *
     * @param User $user.
     * @return boolean $update 
     *
     */
	public function save(User $user,$updated = false)
	{   
	    if(empty($user->getFirstname()) || empty($user->getLastname())){
		    return array(array("message" => "L'utilisateur n'a pas été créé" ),"status" => Response::HTTP_NOT_FOUND);
		}
		if( !$updated ){
            $user->setCreationdate( new \Datetime() );
            $user->setUpdatedate( new \Datetime());
		    $this->em->persist($user);
		    $message = array("message" => "L'utilisateur a été bien créé");
		}else{
			$user->setUpdatedate(new \Datetime());
			$message = array("message" => "L'utilisateur a été bien modifié");
		}

		$this->em->flush();
		return array($message,"status" => Response::HTTP_CREATED);
	}
}