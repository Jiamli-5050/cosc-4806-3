<?php
require_once 'app/views/headerPublic.php';
session_start();

$error = $_SESSION["createError"] ?? '';
?>
<main class="container">
  <div class="page-header">
    <h1>Create Account</h1>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger"> <?php echo $error; ?> </div>
  <?php endif; ?>

  <form action="/create/newUser" method="POST">
    <div class="form-group">
      <label for="username":</label>
      <input type ="text" name="username" class="form-control" required>
      </div>

    <div class=form-group">
      <label>Password:</label>
      <input type="password" name="password" class="form-control"
        pattern="(?=.[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}"
        title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character."
        required>
    </div>

    <div class="form-group">
      <label>Verify Password:</label>
      <input type="password" name="verifypassword" class="form-control" required>
      </div>

    <button type="submit" class="btn btn-primary">Submit</button>
    </form>

  <br><a href="index.php">Home Page</a>
</main>
<?php require_once 'app/views/templates/footer.php'; ?>
      