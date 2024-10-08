<?php 
    
    include('config/db_connect.php');
    $errors = array('username' => '', 'email' => '', 'password' => '', 'confirm-password' => '');

    $username = $email = '';

    if (isset($_POST['submit'])) {

        $username = $_POST['username'];
        $email =  $_POST['email'];

        // write query
        $query_u = "SELECT username FROM registration WHERE username='$username'";
        $query_e = "SELECT email FROM registration WHERE email='$email'";

        // get result 
        $result_u = mysqli_query($conn, $query_u);
        $result_e = mysqli_query($conn, $query_e);

        // fetch data as an array 
        $used_u = mysqli_fetch_assoc($result_u);
        $used_e = mysqli_fetch_assoc($result_e);

        

        // print_r($user_u);
        // print_r($user_e);

        if (empty($_POST['username'])) {
            $errors['username'] = "Username is required.";
        }
        else{
            if (strlen($username) > 16 || strlen($username) < 8) {
                $errors['username'] = "Mininum character 8 and maximum character 16.";
            }
            else {
                if (!preg_match("/^[a-z][a-z0-9_]*$/", $username)) {
                    $errors['username'] = "Username should start with a lowercase letter, and contains only numbers and underscores:";
                }
                else {
                    if (isset($used_u['username'])) {
                            if ($username === $used_u['username']) {
                            $errors['username'] = "Username not available!";
                        }
                    }
                    
                }    
            }
        }
        if (empty($_POST['email'])) {
            $errors['email'] = "email is required";
        }
        else {
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email must be valid. <br>";
            }
            else {
                if (isset($used_e['email'])) {
                        if ($email === $used_e['email']) {
                        $errors['email'] = "This email is already been used!";
                    }
                }
                
            }
        }
        if (empty($_POST['password'])) {
            $errors['password'] = "Password is required <br>";
        }
        else {
            $password =  $_POST['password'];
            if (!preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^", $password)) {
                $errors['password'] = "Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character: <br>";
            }
        }
        if (empty($_POST['confirm-password'])) {
            $errors['confirm-password'] = "Password is required <br>";
        }
        else {
            $confirmPassword = $_POST['confirm-password'];
            if (!preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^", $confirmPassword)) {
                $errors['confirm-password'] = "Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character: <br>";
            }
            else {
                if ($confirmPassword != $password) {
                    $errors['confirm-password'] = "Password do not match. <br>";
                }                
            }
        }

        if (!array_filter($errors)) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password']);

            // write sql query
            $sql = "INSERT INTO registration (username, email, password, confirm_password) VALUES ('$username', '$email', '$password', '$confirmPassword')";

            // get result from query
            if (mysqli_query($conn, $sql)) {
                header('Location: login.php');
            }
            else {
                echo "query error: " . mysqli_error($conn);
            }
        }
    }

?>



<!DOCTYPE html>
<html>
    
    <?php include('templates/header.php'); ?>


    <div class="wrapper">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <h1 class="register-title">Register</h1>
            <div class="input-box username-div">
                <input type="text" placeholder="Username" id="inp-register-username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <i class='bx bxs-user'></i>
                <div class="red-text div-errors"><?php echo $errors['username']; ?></div>
            </div>

            <div class="input-box email-div">
                <input type="text" placeholder="Email" id="inp-email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <i class='bx bxs-envelope'></i>
                <div class="red-text div-errors"><?php echo $errors['email']; ?></div>
            </div>

            <div class="input-box password-div">
                <input type="password" placeholder="Password" id="inp-register-password" name="password">
                <i class='bx bxs-lock-alt'></i>
                <div class="red-text div-errors"><?php echo $errors['password']; ?></div>
            </div>

            <div class="input-box confirm-password-div">
                <input type="password" placeholder="Confirm Password" id="inp-confirm-password" name="confirm-password">
                <i class='bx bxs-lock-alt'></i>
                <div class="red-text div-errors"><?php echo $errors['confirm-password']; ?></div>
            </div>

            <input type="submit" name="submit" id="btn-register" class="btn z-depth-0" value="Register">

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>

        </form>
    </div>
    

    <?php include('templates/footer.php'); ?>

</html>