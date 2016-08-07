<?php

    /* Manage Members Page
     You can Add | Edit | Delete Members From Here
    */
    
    ob_start(); // Output buffering start

    session_start();

    $pageTitle = 'Members';

    if (isset($_SESSION['Username'])) { 
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage page

    if($do == 'Manage') {
        
        // Manage Members Page
        
        $query = '';
        
        if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
            
            $query = 'AND RegStatus = 0';
        }
        
        // Select All users except admin
        
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        
        // Execute the statement
        
        $stmt->execute();
        
        // Assign To Variable
        
        $rows = $stmt->fetchAll();
        
        if(!empty($rows)) {
            
        
        ?>

        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registerd Date</td>
                        <td>Control</td>
                    </tr>
                    
                    <?php
                        foreach($rows as $row) {
                            
                            echo "<tr>";
                                echo "<td>" . $row['UserID'] . "</td>";
                                echo "<td>" . $row['Username'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date'] . "</td>";
                                echo "<td>
                                        <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                        
                                        if($row['RegStatus'] == 0) {
                                        
                                        echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                                        }
                                echo "</td>";
                            echo "</tr>";
                        }
                    
                    ?>
                    
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

        <?php } 
            else {
                
                echo '<div class="container">';
                    echo '<div class="nice-message">There\'s No Members To Show</div>';
                    echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
                echo '</div>';
                
            }
        ?>

        <?php
    }
        
    elseif($do == 'Add') {
        
        // Add Members Page
        
        ?>
        
        <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop">
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="Password Must Be Hard And Complex" required="required">
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control"  required="required" placeholder="Email Must Be Valid">
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" class="form-control"  required="required" placeholder="Full Name Appear In Your Profile Page">
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    <!-- Start Save Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End Save Field -->
                </form>
            </div>

    <?php
    }
        
    elseif($do == 'Insert') {
        
        // Insert Page
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get variables from the form
            
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];
        
            $hashPass = sha1($_POST['password']);
            
            // Validate The Form
            
            $formErrors = array();
            
            if(strlen($user) < 4) {
                
                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 Characters</strong>';
            }
            
            if(strlen($user) > 20) {
                
                $formErrors[] = 'Username Can\'t Be More Than <strong>20 Characters</strong>';
            }
            
            if(empty($user)) {
                
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';
            }
            
            if(empty($pass)) {
                
                $formErrors[] = 'Password Can\'t Be <strong>Empty</strong>';
            }
            
            if(empty($name)) {
                
                $formErrors[] = 'Full Name Can\'t Be <strong>Empty</strong>';
            }
            
            if(empty($email)) {
                
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';
            }
            
            // Loop into error array and echo it
            
            foreach($formErrors as $error) {
                
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            // Check If There's No Error Proceed The update operation
            
            if(empty($formErrors)) {
                
                // Check if user exist in database
                
                $check = checkItem("Username", "users", $user);
                
                if ($check == 1) {
                    
                    $theMsg = '<div class="alert alert-danger">Sorry This User Exists</div>';
                    
                    redirectHome($theMsg, 'back');
                }
                
                else {
                    
                    // Insert Userinfo in the database

                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date) VALUES(:zuser, :zpass, :zmail, :zname, 1, now())");

                    $stmt->execute(array(

                        'zuser' => $user,
                        'zpass' => $hashPass,
                        'zmail' => $email,
                        'zname' => $name
                    ));

                    // Echo success message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                    
                    redirectHome($theMsg, 'back', 3);
                }
                
                
            }
            
            
        }
        else {
            
            echo "<div class='container'>";
            
            $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Browse This Page Directly</div>';
            
            redirectHome($theMsg);
            
            echo "</div>";
        }
        
        echo "</div>";
        
    }

    elseif($do == 'Edit') {
        
        // Edit page
        
        // Check if get request userid is numeric & get the integer value of it
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        
        // Select all data depend on this ID
        
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        
        // Execute query
        
        $stmt->execute(array($userid));
                           
        // Fecth the data
                       
        $row = $stmt->fetch();
                       
        // The row count
                       
        $count = $stmt->rowCount();
        
        // If there's such ID show the form               
                       
        if($count > 0) {  ?>

            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" >
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required="required">
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    <!-- Start Save Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End Save Field -->
                </form>
            </div>

        <?php
        }
        
        // If there's no such ID show error message              
                       
        else {
            
            echo "<div class='container'>";
            
            $theMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
            
            redirectHome($theMsg);
            
            echo "</div>";
        }
    }
        
    elseif($do == 'Update') {

        // Update Page

        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get variables from the form

            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            
            //Password trick
            
            $pass = '';
            
            if(empty($_POST['newpassword'])) {
                
                $pass = $_POST['oldpassword'];
            }
            else {
                $pass = sha1($_POST['newpassword']);
            }
            
            $formErrors = array();
            
            if(strlen($user) < 4) {
                
                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 Characters</strong>';
            }
            
            if(strlen($user) > 20) {
                
                $formErrors[] = 'Username Can\'t Be More Than <strong>20 Characters</strong>';
            }
            
            if(empty($user)) {
                
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';
            }
            
            if(empty($name)) {
                
                $formErrors[] = 'Full Name Can\'t Be <strong>Empty</strong>';
            }
            
            if(empty($email)) {
                
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';
            }
            
            // Loop into error array and echo it
            
            foreach($formErrors as $error) {
                
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            // Check If There's No Error Proceed The update operation
            
            if(empty($formErrors)) {
                
                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                
                $stmt2->execute(array($user, $id));
                
                $count = $stmt2->rowCount();
                
                if($count == 1) {
                    
                    $theMsg = "<div class='alert alert-danger'>Sorry This User Exists</div>";
                    
                    redirectHome($theMsg, 'back', 3);
                }
                else {
                
                    // Update the database with this info

                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    // Echo success message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

                    redirectHome($theMsg, 'back', 3);

                    
                }
                    
                                
                
                
            }
            
            
        }
        else {
            
            $theMsg = '<div class="alert alert-danger">Sorry you can\'t browse this page directly</div>';
            
            redirectHome($theMsg);
        }
        
        echo "</div>";
    }
        
    elseif($do == 'Delete') {
        
        // Delete Member Page
        
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        
        // Check if get request userid is numeric & get the integer value of it
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        
        // Select all data depend on this ID
        
        $check = checkItem('userid', 'users', $userid);
        
        // If there's such ID show the form
        
        if($check > 0) {
            
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
            
            $stmt->bindParam(":zuser", $userid);
            
            $stmt->execute();
            
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
            
            redirectHome($theMsg, 'back');
            
        }
        else {
            
            $theMsg = '<div class="alert alert-danger">This ID Not Exist</div>';
            
            redirectHome($theMsg);
        }
        
        echo '</div>';
        
    }
        
    elseif ($do == 'Activate') {
        
        // Activate Member Page
        
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        
        // Check if get request userid is numeric & get the integer value of it
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        
        // Select all data depend on this ID
        
        $check = checkItem('userid', 'users', $userid);
        
        // If there's such ID show the form
        
        if($check > 0) {
            
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            
            $stmt->execute(array($userid));
            
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated</div>';
            
            redirectHome($theMsg);
            
        }
        else {
            
            $theMsg = '<div class="alert alert-danger">This ID Not Exist</div>';
            
            redirectHome($theMsg);
        }
        
        echo '</div>';
    }

    include $tpl . 'footer.php';

} else {
    
    header('Location: index.php');
    
    exit();
}

ob_end_flush(); // Release the output