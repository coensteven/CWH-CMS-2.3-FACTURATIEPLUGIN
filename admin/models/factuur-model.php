<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Factuur_Model extends Pf_Model{
    
        public $rules = array(
			'factuur_klantid'=>'required',
			'factuur_datum'=>'required',
			'factuur_verloopdatum'=>'required',
			'factuur_BTW'=>'required',
			'factuur_prijs'=>'required',
		);
	
	        public $elements_value = array(
                'factuur_datum' => 'YYYY-MM-DD',
                'factuur_verloopdatum' => 'YYYY-MM-DD',
            );
	
    public function __construct(){
        parent::__construct(''.DB_PREFIX.'factuur');
    }
	
}