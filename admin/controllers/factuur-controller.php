<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Factuur_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('factuur');
        $this->load_model('factuurklanten');
        $this->load_model('factuurvelden');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->factuur_model->rules = Pf::event()->trigger("filter","factuur-validation-rule",$this->factuur_model->rules);
    }
    public function index(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        
        $operator = '';
        
        if (empty($this->get->search)){
                $this->get->search = array();
        }

        if (isset($this->get->search["id"]) && trim($this->get->search["id"]) != ""){
            $where .= $operator.' `id` like ? ';
            $where_values[] = $this->get->search["id"];
            $operator = ' AND ';
        }

        if (isset($this->get->search["factuur_klantid"]) && trim($this->get->search["factuur_klantid"]) != ""){
            $where .= $operator.' `factuur_klantid` like ? ';
            $where_values[] = '%'.$this->get->search["factuur_klantid"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["factuur_status"]) && trim($this->get->search["factuur_status"]) != ""){
            $where .= $operator.' `factuur_status` like ? ';
            $where_values[] = '%'.$this->get->search["factuur_status"].'%';
            $operator = ' AND ';
        }
        
        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
            $where .= $operator.' `factuur_status` = ? ';
            $where_values[] = $this->post->id;
        }

        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","factuur-index-params",$params);
        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->factuur_model->fetch($params,true);
        $this->view->records2 = $this->factuurklanten_model->fetch($params,true);
        $this->view->total_records = $this->factuur_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->factuur_model->fetch($params,true);
            $this->view->records2 = $this->factuurklanten_model->fetch($params,true);
            $this->view->total_records = $this->factuur_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","factuur-index-template",$template);
        $this->view->render($template);
    }
         
    public function add(){
        $this->factuur_model->rules = Pf::event()->trigger("filter","factuur-adding-validation-rule",$this->factuur_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","factuur-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
          
                $data["factuur_nummer"] = date("Y");
                $data["factuur_klantid"] = e($this->post->{"factuur_klantid"});
                $data["factuur_info"] = e($this->post->{"factuur_info"});
                $data["factuur_datum"] = e($this->post->{"factuur_datum"});
                $data["factuur_verloopdatum"] = e($this->post->{"factuur_verloopdatum"});
                $data["factuur_prijs"] = e($this->post->{"factuur_prijs"});
                $data["factuur_BTW"] = e($this->post->{"factuur_BTW"});
                $data["factuur_status"] = 2;
            
            $data = Pf::event()->trigger("filter","testimonials-post-data",$data);
            $data = Pf::event()->trigger("filter","testimonials-adding-post-data",$data);
            
            $port_answer = isset($this->post->{"answer"}) ? $this->post->{"answer"} : array();
            Pf::database()->query('START TRANSACTION');
            $inserted = $this->factuur_model->insert($data);
            if($inserted === false){
                $this->view->errors = $this->factuur_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
                Pf::database()->query('ROLLBACK');
            }else{
                $new_id = $this->factuur_model->insert_id();;
                $insert_meta = true;
                if(count($port_answer) > 0){
                    $custom = array();
                    $int = count($port_answer);
                    for ($i = 0; $i < $int ; $i++) {
                        if(!empty($port_answer[$i])){
                            $custom = array(
                                    'factuur_factuur' => $new_id,
                                    'factuur_beschrijving' => e($port_answer[$i]),
                            );
                        }
                        $insert_meta = $this->factuurvelden_model->insert($custom);
                    }
                    if($insert_meta === false){
                        Pf::database()->query('ROLLBACK');
                        break;
                    }else{
                        Pf::database()->query('COMMIT');
                    }
                }
                Pf::database()->query('COMMIT');
              
                Pf::event()->trigger("action","factuur-add-successfully",$this->factuur_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
              
            }
            
            echo json_encode($var);
        }else{
        
                  $list_users = $this->get_users();
                  if(isset($list_users) && $list_users != NULL){
                      foreach($list_users as $key => $value){
                          $list_all_users[$value['id']] = $value['factuur_naam']." (".$value['factuur_bedrijfsnaam'].")";
                      }
                  }
                  $data2['list_all_users'] = $list_all_users;
          
                  $data = array();
                  $params = array();
                  $this->factuur_model->fetch($params,true);
                  $data2['nummer'] = $this->factuur_model->found_rows() + 1;
                  $this->post->datas($data2);
          

          
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->factuur_model->rules = Pf::event()->trigger("filter","testimonials-editing-validation-rule",$this->factuur_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","testimonials-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                
                
                $data["factuur_klantid"] = e($this->post->{"factuur_klantid"});
                $data["factuur_info"] = e($this->post->{"factuur_info"});
                $data["factuur_datum"] = e($this->post->{"factuur_datum"});
                $data["factuur_verloopdatum"] = e($this->post->{"factuur_verloopdatum"});
                $data["factuur_prijs"] = e($this->post->{"factuur_prijs"});
                $data["factuur_BTW"] = e($this->post->{"factuur_BTW"});
                $data["factuur_status"] = 2;
                
                $port_answer = isset($this->post->{"answer"}) ? $this->post->{"answer"} : array();
                $get_answers = $this->factuurvelden_model->get_answers($this->get->id);
                $number_answer = count($port_answer) + count($get_answers);  
              
              
                $data = Pf::event()->trigger("filter","testimonials-post-data",$data);
                $data = Pf::event()->trigger("filter","testimonials-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->factuur_model->save($data)){
                    $list_users = $this->get_users();
                    if(isset($list_users) && $list_users != NULL){
                        foreach($list_users as $key => $value){
                            $list_all_users[$value['id']] = $value['factuur_naam']." (".$value['factuur_bedrijfsnaam'].")";
                        }
                    }
                    $data['get_answers'] = $this->factuurvelden_model->get_answers($this->get->id);
                    $data['list_all_users'] = $list_all_users;
              
                    $this->post->datas($data);
                    $this->view->errors = $this->factuur_model->errors;
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    $list_users = $this->get_users();
                    if(isset($list_users) && $list_users != NULL){
                        foreach($list_users as $key => $value){
                            $list_all_users[$value['id']] = $value['factuur_naam']." (".$value['factuur_bedrijfsnaam'].")";
                        }
                    }
                  
                  
                    $data['factuur_factuur'] = $this->factuurvelden_model->get_aid($this->get->id);
                    $polls_aids = array();
                    if($data['factuur_factuur']){
                        foreach ($data['factuur_factuur'] as $get_polls_aid){
                            $polls_aids[] = $get_polls_aid;
                        }
                        foreach ($polls_aids as $polla_aid){
                            $id = 'factuurveld_'.$polla_aid['id'];
                            $polls_answers = $this->post->{$id};
                            $this->factuurvelden_model->update_velden($polla_aid['id'],$polls_answers);
                        }
                    }
                    if(isset($this->post->{'answer'})){
                        Pf::database()->query('START TRANSACTION');
                        $new_id = $this->get->id;
                        if(count($port_answer) > 0){
                            $custom = array();
                            $int = count($port_answer);
                            for ($i = 0; $i < $int ; $i++) {
                                if(!empty($port_answer[$i])){
                                    $custom = array(
                                            'factuur_factuur' => $new_id,
                                            'factuur_beschrijving' => e($port_answer[$i]),
                                    );
                                }
                                $insert_meta = $this->factuurvelden_model->insert($custom);
                            }
                            if($insert_meta === false){
                                Pf::database()->query('ROLLBACK');
                                break;
                            }else{
                                Pf::database()->query('COMMIT');
                            }
                        }
                    }
                  
                    $data['list_all_users'] = $list_all_users; 
                    $data['get_answers'] = $this->factuurvelden_model->get_answers($this->get->id);
                    $this->post->datas($data);
                    Pf::event()->trigger("action","factuur-edit-successfully",$data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){ 
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->factuur_model->fetch_one($params);
                    
                    
                    $list_users = $this->get_users();
                    if(isset($list_users) && $list_users != NULL){
                        foreach($list_users as $key => $value){
                            $list_all_users[$value['id']] = $value['factuur_naam']." (".$value['factuur_bedrijfsnaam'].")";
                        }
                    }
                    $data['list_all_users'] = $list_all_users;
                    $data['get_answers'] = $this->factuurvelden_model->get_answers($this->get->id); 
                  
                    $data = Pf::event()->trigger("filter","factuur-database-data",$data);
                    $data = Pf::event()->trigger("filter","factuur-editing-database-data",$data);
                    
                    $this->post->datas($data);
                    $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 0;
                }else{
                    $list_users = $this->get_users();
                    if(isset($list_users) && $list_users != NULL){
                        foreach($list_users as $key => $value){
                            $list_all_users[$value['id']] = $value['factuur_naam']." (".$value['factuur_bedrijfsnaam'].")";
                        }
                    }
                    $data['get_answers'] = $this->factuurvelden_model->get_answers($this->get->id);
                    $data['list_all_users'] = $list_all_users;
                    
                    $this->post->datas($data);
                    $var['error'] = 1;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }
        }else{
            $var['error'] = 1;
            $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        }
        
        echo json_encode($var);
    }
  
    public function pdf(){
      
        $template = null;
        $template = Pf::event()->trigger("filter","factuurpdf-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){

                if (isset($this->get->id)){ 
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $this->view->records3 = $this->factuur_model->fetch($params,true);
                  
                    $list_users = $this->get_users();
                    if(isset($list_users) && $list_users != NULL){
                        foreach($list_users as $key => $value){
                            $list_all_users[$value['id']] = $value['factuur_naam']." (".$value['factuur_bedrijfsnaam'].")";
                        }
                    }
                    $data['list_all_users'] = $list_all_users;
                    $data['get_answers'] = $this->factuurvelden_model->get_answers($this->get->id); 
                    $data = Pf::event()->trigger("filter","factuurpdf-database-data",$data);
                    $data = Pf::event()->trigger("filter","factuurpdf-editing-database-data",$data);
                    $this->post->datas($data);
                  
                  
                    $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 0;
                }else{
                    $this->view->records3 = $this->factuur_model->fetch($params,true);
                    $this->post->datas($data);
                    $var['error'] = 1;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            
        }else{
            $var['error'] = 1;
            $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        }
        
        echo json_encode($var);
    }
      
    public function bulk_action(){
        $var = array();
        $data = array();
        $params = array();
        
        if (Pf_Plugin_CSRF::is_valid($this->post->token,$this->key)){
            switch ($this->get->type){
                case 'delete':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $this->factuur_model->delete('id=?',array($id));
                        }
                    }
                    $var['action'] = 'delete';
                break;
                case 'publish':
                    
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->factuur_model->fetch_one($params);
                            $data['factuur_status'] = 1;
                            $this->factuur_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->factuur_model->fetch_one($params);
                            $data['factuur_status'] = 2;
                            $this->factuur_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","factuur-bulk-action-successfully",$this->get->type,$this->post->id);
            $var['error'] = 0;
        }else{
            $var['error'] = 1;
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=&type=');
        
        echo json_encode($var);
    }
    
    public function delete(){
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->factuur_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","factuur-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
    
    public function change_statusfactuur() {
        $data = array();
        $status = $this->post->status;
        $params = array();
        $params['where'] = array('id=?',array((int)$this->post->id));
        $data = $this->factuur_model->fetch_one($params);
        
        switch ($status) {
            case 'publish':
                $data['factuur_status'] = 1;
                $this->factuur_model->save($data);
                break;
            case 'unpublish':
                $data['factuur_status'] = 2;
                $this->factuur_model->save($data);
                break;
        }
    }

    private function get_users(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
    
        $params['fields'] = array('id,factuur_naam,factuur_bedrijfsnaam');
    
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
    
        $records = $this->factuurklanten_model->fetch($params,true);
    
        return $records;
    }
  
    public function delete_veld(){
        $data = array();
        $aid = $this->post->{"id"};
        $this->factuurvelden_model->delete('id=?',array($aid));
    }
 
  
}