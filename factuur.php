<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Factuur_Plugin extends Pf_Plugin {
    public $name = 'Factuur';
    public $version = '1.0';
    public $author = 'CWH';
    public $description = 'This is the factuur description';
    public function activate() {
        $db = Pf::database();
        $db->query("CREATE TABLE `".DB_PREFIX."factuur` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `factuur_klantid` varchar(10) NOT NULL,
            `factuur_nummer` varchar(10) NOT NULL,
            `factuur_status` varchar(10) NOT NULL,
            `factuur_prijs` varchar(10) NOT NULL,
            `factuur_BTW` varchar(10) NOT NULL,
            `factuur_info` varchar(400) NOT NULL,
            `factuur_datum` varchar(100) NOT NULL,
            `factuur_verloopdatum` varchar(100) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ");
        $db->query("CREATE TABLE `".DB_PREFIX."factuurvelden` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `factuur_factuur` varchar(40) NOT NULL,
            `factuur_beschrijving` varchar(400) NOT NULL,
            `factuur_prijs` varchar(20) NOT NULL,
            `factuur_btw` varchar(10) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ");
        $db->query("CREATE TABLE `".DB_PREFIX."factuurklanten` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `factuur_naam` varchar(50) NOT NULL,
            `factuur_content` varchar(1000) NOT NULL,
            `factuur_info` varchar(200) NOT NULL,
            `factuur_status` int(11) NOT NULL,
            `factuur_klant` varchar(200) NOT NULL,
            `factuur_bedrijfsnaam` varchar(100) NOT NULL,
            `factuur_adres` varchar(200) NOT NULL,
            `factuur_postcode` varchar(20) NOT NULL,
            `factuur_plaats` varchar(40) NOT NULL,
            `factuur_provincie` varchar(20) NOT NULL,
            `factuur_datum` varchar(40) NOT NULL,
            `factuur_gebruikerkoppeling` varchar(10) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ");
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."factuur`;";
        
        $db->query($sql);
        
        $sql2 = "DROP TABLE IF EXISTS `".DB_PREFIX."factuurklanten`;";
        
        $db->query($sql2);
      
        $sql3 = "DROP TABLE IF EXISTS `".DB_PREFIX."factuurvelden`;";
        
        $db->query($sql3);
    }
    public function admin_init() {
        if (is_admin() or is_editor() or is_author()){
            $this->admin_menu ( 'fa fa-tasks', "Facturering", 'factuur', 'factuur' );
            $this->admin_children_menu('fa fa-angle-double-right', "Klanten", 'factuur2', 'factuur2', 'factuur');
            $this->admin_children_menu('fa fa-angle-double-right', "Facturen", 'factuur', 'factuur', 'factuur');
        }
    }
    
    public function public_init(){
        //$this->add_shortcode('testimonials', 'display','display');
    }
    
    public function factuur(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->css('testimonials/admin/assets/testimonials.css',__FILE__);
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        $this->js('testimonials/admin/assets/testimonials.js',__FILE__);
    }
    public function factuur2(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->css('testimonials/admin/assets/testimonials.css',__FILE__);
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        $this->js('testimonials/admin/assets/testimonials.js',__FILE__);
    }
}