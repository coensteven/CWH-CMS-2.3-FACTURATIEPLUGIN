<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Testimonials_Model extends Pf_Model{
    public function __construct(){
        parent::__construct(DB_PREFIX.'testimonials');
    }
}