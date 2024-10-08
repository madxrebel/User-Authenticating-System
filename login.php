<?php 

    include('config/db_connect.php');
    $errors = array('username' => '', 'password' => '');
    
    $username = $password = '';

    if (isset($_POST['submit'])) {

        $username = $_POST['username'];
        $password =  $_POST['password'];

        // write query
        $sql = "SELECT * FROM registration WHERE username='$username'";

        // get result
        $result = mysqli_query($conn, $sql);

        // fetch result as associative array
        $user = mysqli_fetch_assoc($result);

        if (empty($_POST['username'])) {
            $errors['username'] = "Username is required.";
        }
        else{
            if (strlen($username) > 16 || strlen($username) < 8) {
                $errors['username'] = "Mininum 8 characters, maximum 16 characters.";
            }
            else {
                if (!preg_match("/^[a-z][a-z0-9_]*$/", $username)) {
                    $errors['username'] = "Username should start with a lowercase letter, and contains only numbers and underscores:";
                }
                else {
                    if (isset($user['username'])) {
                            if ($username !== $user['username']) {
                            $errors['username'] = "User not available!";
                        }  
                    }
                    else {
                        $errors['username'] = "User not available!";
                    }
                }    
            }
        }

        if (empty($_POST['password'])) {
            $errors['password'] = "Password is required <br>";
        }
        else {
            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
                $errors['password'] = "Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character: <br>";
            }
            else {
                if (isset($user['username'])) {
                    if ($password !== $user['password']) {
                        $errors['password'] =  "Incorrect password!";
                    }
                }
                // else {
                //     $errors['password'] =  "Incorrect password!";
                // }
                
            }
        }



        if (!array_filter($errors)) {
            header('Location: index.php');
        }
        else {
            echo "error: " . mysqli_error($conn);
        }
    }


?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <div class="wrapper">
        <form action="login.php" method="POST">

            <h1 class="login-title">Login</h1>
            <div class="input-box username-div">
                <input type="text" placeholder="Username" id="inp-login-username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <i class='bx bxs-user'></i>
                <div class="red-text div-errors"><?php echo $errors['username']; ?></div>
            </div>

            <div class="input-box password-div">
                <input type="password" placeholder="Password" id="inp-login-password" name="password">
                <i class='bx bxs-lock-alt'></i>
                <div class="red-text div-errors"><?php echo $errors['password']; ?></div>
            </div>

            <div class="remember-forget">
                <label><input type="checkbox" id="inp-remember-me">Remember me</label>
                <a href="#" class="forget-password-link">Forget Password?</a>
            </div>

            <button type="submit" class="btn" id="btn-login" name="submit">Login</button>

            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>

        </form>
    </div>
    
    

    <?php include('templates/footer.php'); ?>



</html>