<?php
/**
 * Rozsireni controlleru o spolecne metody
 */
class My_Controller_Action extends Zend_Controller_Action
{
	/**
	* Vrati objekt prave prihlaseneho uzivatele
	*/
	protected function getUser(){
		//Zjištění zda je uživatel admin (kvůli viditelnosti registrace)
		$login = Zend_Auth::getInstance()->getIdentity();
		$user = My_Model::get('Users')->getUserByEmail($login);
		return $user;
	}
}