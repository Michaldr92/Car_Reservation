<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Getdata extends CI_Controller {
	
	 function __construct() {
        parent::__construct();
        $this->load->model('Cartracking_m'); // załadowanie modelu cartracking_m
    }
	
	function get_carinfo($id){ // Pobierz informacje 
		$list = $this->Cartracking_m->get_carinfo($id);
		$this->load->view('car_list_json', array('response'=>$list));
	}
	
	
}
?>