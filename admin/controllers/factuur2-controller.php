<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Factuur2_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('factuur');
        $this->load_model('factuurklanten');
        $this->load_model('user');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->factuurklanten_model->rules = Pf::event()->trigger("filter","factuur2-validation-rule",$this->factuurklanten_model->rules);
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

        if (isset($this->get->search["factuur_naam"]) && trim($this->get->search["factuur_naam"]) != ""){
            $where .= $operator.' `factuur_naam` like ? ';
            $where_values[] = '%'.$this->get->search["factuur_naam"].'%';
            $operator = ' AND ';
        }

        if (isset($this->get->search["id"]) && trim($this->get->search["id"]) != ""){
            $where .= $operator.' `id` like ? ';
            $where_values[] = $this->get->search["id"];
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

        $params = Pf::event()->trigger("filter","factuur2-index-params",$params);
        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->factuurklanten_model->fetch($params,true);
        $this->view->total_records = $this->factuurklanten_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->factuurklanten_model->fetch($params,true);
            $this->view->total_records = $this->factuurklanten_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","factuur2-index-template",$template);
        $this->view->render($template);
    }
  
    
    public function add(){
        $this->factuurklanten_model->rules = Pf::event()->trigger("filter","factuur2-adding-validation-rule",$this->factuurklanten_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","factuur2-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
            
            
                $data["factuur_naam"] = e($this->post->{"factuur_naam"});
                $data["factuur_klant"] = e($this->post->{"factuur_klant"}); 
                $data["factuur_gebruikerkoppeling"] = e($this->post->{"factuur_gebruikerkoppeling"});
                $data["factuur_bedrijfsnaam"] = e($this->post->{"factuur_bedrijfsnaam"});
                $data["factuur_adres"] = e($this->post->{"factuur_adres"});
                $data["factuur_postcode"] = e($this->post->{"factuur_postcode"});
                $data["factuur_provincie"] = e($this->post->{"factuur_provincie"});
                $data["factuur_datum"] = e($this->post->{"factuur_datum"});
                $data["factuur_content"] = e($this->post->{"factuur_content"});
                $data["factuur_info"] = e($this->post->{"factuur_info"});
                $data["factuur_status"] = 1;
          
            
            $data = Pf::event()->trigger("filter","factuur2-post-data",$data);
            $data = Pf::event()->trigger("filter","factuur2-adding-post-data",$data);
            
            $var = array();
            if (!$this->factuurklanten_model->insert($data)){
                $this->view->errors = $this->factuurklanten_model->errors;
                $var['content'] = $this->view->fetch($template);
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","factuur2-add-successfully",$this->factuurklanten_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
          
            $list_users = $this->get_users();
            if(isset($list_users) && $list_users != NULL){
                foreach($list_users as $key => $value){
                    $list_all_users[$value['id']] = $value['user_name'];
                }
            }
            $data['list_all_users'] = $list_all_users;
            $this->post->datas($data);
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->factuurklanten_model->rules = Pf::event()->trigger("filter","factuur2-editing-validation-rule",$this->factuurklanten_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","factuur2-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                
                
                $data["factuur_naam"] = e($this->post->{"factuur_naam"});
                $data["factuur_klant"] = e($this->post->{"factuur_klant"}); 
                $data["factuur_gebruikerkoppeling"] = e($this->post->{"factuur_gebruikerkoppeling"});
                $data["factuur_bedrijfsnaam"] = e($this->post->{"factuur_bedrijfsnaam"});
                $data["factuur_adres"] = e($this->post->{"factuur_adres"});
                $data["factuur_postcode"] = e($this->post->{"factuur_postcode"});
                $data["factuur_provincie"] = e($this->post->{"factuur_provincie"});
                $data["factuur_datum"] = e($this->post->{"factuur_datum"});
                $data["factuur_content"] = e($this->post->{"factuur_content"});
                $data["factuur_info"] = e($this->post->{"factuur_info"});
                $data["factuur_status"] = 1;
                
                
                $data = Pf::event()->trigger("filter","factuur2-post-data",$data);
                $data = Pf::event()->trigger("filter","factuur2-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                if (!$this->factuurklanten_model->save($data)){
                    $this->view->errors = $this->factuurklanten_model->errors;
                  
                     $list_users = $this->get_users();
                         if(isset($list_users) && $list_users != NULL){
                            foreach($list_users as $key => $value){
                                 $list_all_users[$value['id']] = $value['user_name'];
                            }
                          }
                          $data['list_all_users'] = $list_all_users;
                  
                    $this->post->datas($data);
                  
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","testimonials-edit-successfully",$data);
                     $list_users = $this->get_users();
                         if(isset($list_users) && $list_users != NULL){
                            foreach($list_users as $key => $value){
                                 $list_all_users[$value['id']] = $value['user_name'];
                            }
                          }
                          $data['list_all_users'] = $list_all_users;
                  
                    $this->post->datas($data);
                    $var['error'] = 0;
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->factuurklanten_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","testimonials-database-data",$data);
                    $data = Pf::event()->trigger("filter","testimonials-editing-database-data",$data);
                    
                          $list_users = $this->get_users();
                         if(isset($list_users) && $list_users != NULL){
                            foreach($list_users as $key => $value){
                                 $list_all_users[$value['id']] = $value['user_name'];
                            }
                          }
                          $data['list_all_users'] = $list_all_users;
                  
                    $this->post->datas($data);
                    $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 0;
                }else{
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
    
    
    
    public function bulk_action(){
        $var = array();
        $data = array();
        $params = array();
        
        if (Pf_Plugin_CSRF::is_valid($this->post->token,$this->key)){
            switch ($this->get->type){
                case 'delete':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $this->factuurklanten_model->delete('id=?',array($id));
                        }
                    }
                    $var['action'] = 'delete';
                break;
                case 'publish':
                    
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->factuurklanten_model->fetch_one($params);
                            $data['factuur_status'] = 1;
                            $this->factuurklanten_model->save($data);
                        }
                    }
                    $var['action'] = 'publish';
                break;
                case 'unpublish':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $params['where'] = array('id=?',array($id));
                            $data = $this->factuurklanten_model->fetch_one($params);
                            $data['factuur_status'] = 2;
                            $this->factuurklanten_model->save($data);
                        }
                    }
                    $var['action'] = 'unpublish';
                break;
            }
            Pf::event()->trigger("action","factuur2-bulk-action-successfully",$this->get->type,$this->post->id);
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
            if ($this->factuurklanten_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","testimonials-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
  
    public function change_statusklanten() {
        $data = array();
        $status = $this->post->status;
        $params = array();
        $params['where'] = array('id=?',array((int)$this->post->id));
        $data = $this->factuurklanten_model->fetch_one($params);
        
        switch ($status) {
            case 'publish':
                $data['factuur_status'] = 1;
                $this->factuurklanten_model->save($data);
                break;
            case 'unpublish':
                $data['factuur_status'] = 2;
                $this->factuurklanten_model->save($data);
                break;
        }
    }
  
  
  private function get_level($data, $display_level = '--'){
    $result = array();
    $data_id = array();
    foreach($data as $key => $value){
        $data_id[$key] = $value['category_parent'];
    }
    if (count($data)){
        $ids = $data_id;
        recursive($data, $result, min($ids), 0,'category');
    }

    if (count($data) !== count($result)) {
        $ids = array_map('get_id_of_category', $result);
        foreach ($data as $k => $v) {
            if (!in_array($v['id'], $ids)) {
                $v['level'] = 0;
                $result[] = $v;
                unset($data[$k]);
            }
        }
    }

    if (!is_null($display_level)) {
        return array_map('replace_recursive_title', $result);
    }
    return $result;
}
  
    private function get_users(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
    
        $params['fields'] = array('id,user_name,user_lastname');
    
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
    
        $records = $this->user_model->fetch($params,true);
    
        return $records;
    }

}