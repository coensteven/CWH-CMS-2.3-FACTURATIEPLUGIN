<?php
defined('PF_VERSION') OR header('Location:404.html');
require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-setting.php';
$setting = Pf::setting();
$setting->add_title('pf_factuur', "Facturering");
$util_setting = new Pf_Plugin_Setting;
$util_setting->set_name('pf_factuur');
$util_setting->add_element_input("BTW standaard", 'btwstandaard');
$util_setting->add_element_input("BTW 1", 'btw1');
$util_setting->add_element_input("BTW 2", 'btw2');
$util_setting->add_element_input("BTW 3", 'btw3');
$util_setting->add_element_input("BTW 4", 'btw4');  
$util_setting->add_element_input("Bedrijfsnaam", 'bedrijfsnaam');
$util_setting->add_element_input("Adres", 'adres');
$util_setting->add_element_input("Postcode", 'postcode');
$util_setting->add_element_input("Provincie", 'provincie');