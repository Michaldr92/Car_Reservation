<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth  {

public function check(){
	
	//sleep(2);
	
	$routing =& load_class('Router');
	$class = strtolower($routing->fetch_class());
	$method = strtolower($routing->fetch_method());
	
	$route = $class.'/'.$method;
	
	//loguj($route);
	
	$route_white = array('user/check_pass','user/logout','cron/clean');
	

	if (! in_array($route,$route_white)) {
		$CI = &get_instance();
		if (isset($CI)){
			$CI->load->library('session');

			$user_info = $CI->session->userdata('userInfo');
			if (! isset($user_info['lname']) ){
				$response = array('error'=>'Proszę się zalogować','logged'=>false,'status'=>'access denied');
				//header("HTTP/1.0 403 Forbidden");
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
			
		}
	}
}

	
}