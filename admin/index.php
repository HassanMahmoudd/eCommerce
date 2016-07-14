<?php
    
    session_start();
    if (isset($_SESSION['Username'])) {
        
        header('Location: dashboard.php'); // Redirect to Dashboard Page
        
    }
    
    include 'init.php';
    include $tpl . "header.php";
    include 'includes/languages/english.php';
    
    // Check if user coming from HTTP Post Request
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);
        
        // Check if the user exist in database
        
        $stmt = $con->prepare("SELECT Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1");
        
        $stmt->execute(array($username, $hashedPass));
        $count = $stmt->rowCount();
        
        // If count > 0 This mean the database contain record about this username
        
        if($count > 0) {
            
            $_SESSION['Username'] = $username; //Register Session Name
            header('Location: dashboard.php'); // Redirect to Dashboard Page
            exit();
            
        }
        
    }
?>
    
    
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    </form>

<?php

    include $tpl . "footer.php";
?>