<?php

class Create extends Controller {

    public function index() {		
	    $this->view('create/index');

    }
  // Create a new user
  public function newUser() {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $verifypassword = $_POST['verifypassword'] ?? '';

    $username = strtolower(trim($username));
    $password = trim($password);
    $verifypassword = trim($verifypassword);

    $user = $this->model('User');
    $user->create($username, $password, $verifypassword);
  }
}
?>
