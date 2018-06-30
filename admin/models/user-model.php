<?php
class User_Model extends Pf_Model{

	  public function __construct(){
        parent::__construct(''.DB_PREFIX.'users');
    }
	
}