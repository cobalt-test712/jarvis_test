<?php

namespace App\Service;

use App\Entity\User;


class ConverterUser
{
	/**
     * Modification d'un utilisateur.
     *
     * @param $data.
     * @param User $user.
     *
     * @return User 
     *
     */
	public function convert($data, User $user): ?User
	{
		if(empty($data->getFirstname()) && empty($data->getLastname()) ) 
			return null; 

		if ($data->getFirstname())
			$user->setFirstname($data->getFirstname());
		if ($data->getLastname()) 
			$user->setLastname($data->getLastname());
		return $user;
	}
	
}