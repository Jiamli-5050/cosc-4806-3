<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

class Log {

  //test function to see if the logs table is working
  public function test() {
    $db = db_connect();
    $stmt = $db->prepare("SELECT * FROM logs;");
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  //collects all failed attempts in the last 60 seconds
  public function getFailedAttempts($username) {
    $db = db_connect();
    $sql = "SELECT COUNT(*) AS failed_attempts
    FROM logs
    WHERE username = :name 
    AND attempt = 'bad'
    AND time > NOW() - INTERVAL 60 SECOND";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  //logs a failed or successful attempt
  public function logAttempt($username, $attempt) {
    $db = db_connect();
    $stmt = $db->prepare("INSERT INTO logs (username, attempt, time) VALUES (?, ?, NOW())");
    $stmt->execute([$username, $attempt]);
  }
}
?>