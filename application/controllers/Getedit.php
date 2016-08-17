<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Getedit extends CI_Controller {
	
	 function __construct() {
        parent::__construct();
        $this->load->model('Cartracking_m'); // załadowanie modelu cartracking_m
		$this->load->model('auth_m'); // załadowanie modelu auth_m
    }
	
	function save_data(){ // Zapisanie wpisu
		
		$cartracking_data = $this->input->get();
		
							
		$error = $this->Cartracking_m->save_data($cartracking_data); 
		$this->load->view('car_edit_json', array('error'=>$error)); // przekazuje do widoku json
	}
	
	function delete_car(){ // Usuwanie wpisu
		
		$cartracking_data = $this->input->post();
	
		$delete = $this->Cartracking_m->delete_car($cartracking_data);		
		$this->load->view('car_delete_json', array('rezult'=>$delete)); // przekazuje do widoku json
	}
	
}
