<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Auth extends CI_Controller
{
    
    public function index()
    {
        redirect(''); // Przekieruj na strone główną
    }
    
    public function login() // Logowanie
    {
        $data['error'] = "";
        $this->load->view('logform', $data); // załaduj panel logowanie
    }
    
    public function logout() // Wylogowanie
    {
        $this->session->sess_destroy(); // zniszcz sesje
        redirect(''); // Przekieruj na stronę główną
    }
    
    public function check() // Funkcja sprawdzająca
    {
        if ($this->input->post('cancel')) {
            redirect('');
        }
		
        $netid = strtolower($this->input->post('netid', TRUE));
        $pass  = $this->input->post('pass');
        $this->load->model('auth_m');
        
        $authorized = FALSE;
        
        if (!$this->session->userdata('netid')) { // pobranie z sesji netid
            
            $authorized = $this->auth_m->check_auth($netid, $pass);
            if ($authorized) {
                $user_info = $this->auth_m->get_user_info($netid, $pass);
                
                if ($user_info['netid']) {
                    $this->session->set_userdata($user_info); // ustaw sesje
                }
            }
            } else {
            $authorized = TRUE;
        }
        if ($authorized) { // Autoryzacja
				redirect(''); // strona główna
        } else {
				$data['error'] = "Niepoprawny NetID lub hasło"; // komunikat przy złym haśle
				$this->load->view('logform', $data); // załaduj panel logowania
        }
    }
}