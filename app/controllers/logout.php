<?php

class Logout extends Controller {

    public function index() {		
	   if (session_status() === PHP_SESSION_NONE) {
           session_start();
       }
    
        $_SESSION = array();
        session_destroy();
        header('location:/login');
    }
}