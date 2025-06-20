<?php
session_start();

class Log {

  public function test() {
    $db = db_connect();
    $stmt = $db->prepare("SELECT * FROM logs;");
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    return $rows;
  }

  public function getFailedAttempts($username) {
    $db = db_connect();
    $sql = "SELECT COUNT(*) AS failed_attempts FROM logs WHERE username = :name AND attempt
    = 'bad'
    AND time > NOW() - INTERVAL 60 SECOND";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function logAttempt($username, $attempt) {
    $db = db_connect();
    $stmt = $db->prepare("INSERT INTO logs (username, attempt, time) VALUES (?, ?, NOW())");
    $stmt->execute([$username, $attempt]);
  }
}
?>