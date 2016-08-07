<?php
ob_start();
session_start();
$pageTitle = 'Login';

if (isset($_SESSION['user'])) {
        
        header('Location: index.php');
    }

include 'init.php';

// Check if user coming from HTTP Post Request
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if(isset($_POST['login'])) {
        
        
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);
        
        // Check if the user exist in database
        
        $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ?");
        
        $stmt->execute(array($user, $hashedPass));
            
        $get = $stmt->fetch();
       
        $count = $stmt->rowCount();
        
        // If count > 0 This mean the database contain record about this username
        
        if($count > 0) {
            
            $_SESSION['user'] = $user; // Register Session Name
            
            $_SESSION['uid'] = $get['UserID']; // Register User ID in Session
            
            header('Location: index.php'); // Redirect to Dashboard Page
            exit();
            
        }
            
        }
        else {
            
            $formErrors = array();
            
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password'];
            $email = $_POST['email'];
            
            if(isset($username)) {
                
                $filteredUser = filter_var($username, FILTER_SANITIZE_STRING);
                
                if(strlen($filteredUser) < 4) {
                    
                    $formErrors[] = 'Username Must Be Larger Than 4 Characters';
                }
            }
            
            
            if(isset($password) && isset($password2)) {
                
                if(empty($password)) {
                    
                    $formErrors[] = 'Sorry Password Can\'t Be Empty';
                }
                
                $pass1 = sha1($_POST['password']);
                
                $pass2 = sha1($_POST['password2']);
                
                if($pass1 !== $pass2) {
                    
                    $formErrors[] = 'Sorry Password Is Not Match';
                }
            }
            
            if(isset($email)) {
                
                $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                
                if(filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                    
                    $formErrors[] = 'This Email Is Not Valid';
                }
            }
            
            // Check If There's No Error Proceed The User Add operation
            
            if(empty($formErrors)) {
                
                // Check if user exist in database
                
                $check = checkItem("Username", "users", $username);
                
                if ($check == 1) {
                    
                    $formErrors[] = 'Sorry This User Exists';
                    
                }
                
                else {
                    
                    // Insert Userinfo in the database

                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, RegStatus, Date) VALUES(:zuser, :zpass, :zmail, 0, now())");

                    $stmt->execute(array(

                        'zuser' => $username,
                        'zpass' => sha1($password),
                        'zmail' => $email,
                        
                    ));

                    // Echo success message

                    $successMsg = 'Congrats User Successfully Added';
                }
                
                
            }
        }
        
    }


?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
    </h1>
    <!-- Start login form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required="required">
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required="required">
            
        </div>
        <input class="btn btn-primary btn-block" type="submit" value="login" name="login">
        
    </form>
    <!-- End login form -->
    <!-- Start signup form -->

    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required="required">
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type A Complex Password" required="required">
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type Password Again" required="required">
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" placeholder="Type A Valid Email" required="required">
        </div>
        
        <input class="btn btn-success btn-block" type="submit" value="Signup" name="signup">
    </form>
    <!-- End signup form -->
    <div class="the-errors text-center">
       <?php 
            if(!empty($formErrors)) {
                
                foreach($formErrors as $error) {
                    
                    echo '<div class="msg error">' . $error . '</div>';
                }
            }
          
            if (isset($successMsg)) {
                
                echo '<div class="msg success">' . $successMsg . '</div>';
            }
    
        ?>
    </div>

</div>

<?php include $tpl . 'footer.php';
ob_end_flush(); ?>