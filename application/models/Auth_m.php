<?php
class Auth_m extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('adldap'); // Załadowanie zewnętrznej biblioteki ADLDAP
    }
    
    
    function check_auth($netid, $pass){ // Sprawdzenie autoryzacji
        return $this->adldap->authenticate($netid,$pass);
    }
    
    function get_user_info($netid, $pass){ // Pobranie danych użytkownika
        $tab['first_name']=$tab['last_name']=$tab['email']=$tab['netid']='';
        
        $info=$this->adldap->user_info($netid,array('mail',"sn","givenname"));
        
        if (isset($info[0]['givenname'][0])) $tab['first_name']=$info[0]['givenname'][0];
        if (isset($info[0]['sn'][0])) $tab['last_name']=$info[0]['sn'][0];
        if (isset($info[0]['mail'][0])) $tab['email']=$info[0]['mail'][0];
        $tab['netid']=$netid;
        //$tab['admin']=$admin;
        $this->load->view('Cartracking_v'); // Wyświetlenie widoku
        
        
        return $tab;
    }
    
    
    function getSession(){ // Pobierz sesje
        $session = $this->session->userdata();
        
        $admin=0;
        
        
        if (!isset($session['netid'])) $session['netid'] = ''; // netid
        if (!isset($session['first_name'])) $session['first_name'] = ''; // imie
        if (!isset($session['last_name'])) $session['last_name'] = ''; // nazwisko
        if (!isset($session['email'])) $session['email'] = ''; // email
        
        //Sprawdz Admin
        if (in_array($session['email'], explode(',',ADMINS))) $admin=1;
        if (!isset($session['admin'])) $session['admin'] = $admin;
        
        return $session;
    }
}