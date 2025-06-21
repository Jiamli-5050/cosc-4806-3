<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        
    }

  //test function to see if the users table is working
    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }
//verify user login
    public function authenticate($username, $password) {
		$username = strtolower($username);
      require_once 'Log.php';
      $log = new Log();

    // Check if the user has made too many failed attempts in the last 60 seconds
      $attempts = $log->getFailedAttempts($username);
      if ($attempts && $attempts['failed_attempts'] >= 3) {
          $_SESSION["login_error"] = "Too many failed attempts. Please try again in 60 seconds.";
          header("Location: /login");
          exit;
      }
  // Check if the username and password are correct
      $db = db_connect();
      $statement = $db->prepare("select * from users WHERE username = :name;");
      $statement->bindValue(':name', $username);
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
//verify password
      if ($rows && password_verify($password, $rows['password'])) {
          $_SESSION['auth'] = 1;
          $_SESSION['username'] = ucfirst($username);
          $log->logAttempt($username, 'good');
        header("Location: /home");
        exit;
      } else {
        $log->logAttempt($username, 'bad');
        $_SESSION["login_error"] = "Invalid username or password.";
        header("Location: /login");
        exit;
      }
    }

   //register new user
    public function create($username, $password, $verifypassword) {
      $username = strtolower(trim($username));
      $password = trim($password);
      $verifypassword = trim($verifypassword);

      if (empty($username) || empty($password) || empty($verifypassword)) {
        $_SESSION["create_error"] = "Please fill in all fields.";
        header("Location: /create");
        exit;
      }
      if (strlen($password) < 8) {
        $_SESSION["createError"] = "Password must be at least 8 characters.";
        header("Location: /create");
        exit;
      }
      // Check if the username already exists
      $db = db_connect();
      $check = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
      $check->execute([$username]);
      if ($check->fetchColumn() > 0) {
        $_SESSION["createError"] = "Username already exists.";
        header("Location: /create");
        exit;
      }
      // Check if the passwords match
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $insert = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($insert->execute([$username, $hashed])) {
      $_SESSION['auth'] = 1;
      $_SESSION['username'] = ucfirst($username);
      header("Location: /home");
      exit;
    } else {
      $_SESSION["createError"] = "Error creating user, please try again.";
      header("Location: /create");
      exit;
    }
  }
}

