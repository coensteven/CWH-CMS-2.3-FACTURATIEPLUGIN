<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Factuurklanten_Model extends Pf_Model{ 
  
    public $rules = array(
			'factuur_naam'=>'required|max_len,255',
		);
  
    public $elements_value = array(
    );
	
	
	    public function get_gebruiker($id){
        $params = array();
        $where = 'id=?';
        $where_value[] = $id;
        $params['fields'] =  array('factuur_naam','id');
        $params['where'] =  array($where,$where_value);
        $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('ASC');
        $result = $this->fetch_one($params);
        return  $result;
    }
  
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'factuurklanten');
    }
}