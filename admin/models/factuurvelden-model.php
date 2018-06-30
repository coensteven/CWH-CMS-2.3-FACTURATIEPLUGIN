<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Factuurvelden_Model extends Pf_Model{ 
  
    public $rules = array(
			'factuur_beschrijving'=>'max_len,400',
		);
  
//this is function get answers
    public function get_answers($id){
        $params = array();
        $where = 'factuur_factuur=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('factuur_beschrijving','factuur_factuur','id');
        $params['where'] =  array($where,$where_value);
        $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('ASC');
        $result = $this->fetch($params);
        return  $result;
    }
    // this is function get aid 
    public function get_aid($id){
        $params = array();
        $where = 'factuur_factuur=?';
        $where_value = array();
        $where_value[] = $id;
        $params['fields'] =  array('id');
        $params['where'] =  array($where,$where_value);
        $params["order"] = "`".Pf::database ()->escape('id')."` ".Pf::database ()->escape('ASC');
        $result = $this->fetch($params);
        return  $result;
    }
    //this is function update answers
    public function update_velden($aid,$field){
        $data = array();
        $where = 'id='.$aid.'';
        $data['factuur_beschrijving'] = $field;
        return  $this->update($data,$where);
    }
  
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'factuurvelden');
    }
}