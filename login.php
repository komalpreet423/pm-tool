<?php include './includes/db.php' ;
?>
<?php
 ob_start();
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password_hash = $_POST['password'];
    if (empty($email) || empty($password_hash)) {
        echo "Both fields are required.";
    } else {
        $epassword = md5($password_hash);
        $sql = "SELECT id, name, email, role, phone_number FROM users WHERE email = '$email' AND password_hash = '$epassword'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['userId'] = $user['id'];
           header('Location: ' . BASE_URL . '/index.php');
            exit();
       } else {
        $error = "Invalid email or password";
       }
   }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="./assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h2 class="text-center mb-4">Login</h2>
        <?php
             if (isset($error)) {
                echo "<div class='alert alert-danger text-center'>$error</div>";
            }
            ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>

