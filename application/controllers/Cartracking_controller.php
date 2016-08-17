<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Cartracking_c extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Cartracking_m'); // załadowanie modelu cartracking_m
        $this->load->model('auth_m'); // załadowanie modelu auth_m
    }
    
    function index() // Główna strona
    {     
        $session = $this->auth_m->getSession(); // sesja
        $this->load->view('Cartracking_v', array('session' => $session
        )); // załaduj główny widok      
    }
    
    function get_infolist() // Pobierz informacje
    {        
        $results = $this->Cartracking_m->get_infolist(); // funkcja getlist
        echo json_encode($results); // przekaż do JSON
    }
    
}

?>