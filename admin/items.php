<?php

    /* Items Page */
    
    ob_start(); // Output buffering start

    session_start();

    $pageTitle = '';

    if(isset($_SESSION['Username'])) {
        
        include 'init.php';
        
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        if ($do == 'Manage') {
            
            // Manage Items Page
    

            $stmt = $con->prepare("SELECT 
            
                                        items.*,
                                        categories.Name AS category_name,
                                        users.Username
                                    FROM
                                        items
                                    INNER JOIN
                                        categories
                                    ON
                                        categories.ID = items.Cat_ID
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = items.Member_ID
                                    ORDER BY
                                        Item_ID DESC");

            // Execute the statement

            $stmt->execute();

            // Assign To Variable

            $items = $stmt->fetchAll();
                
            if(!empty($items)) {
                
                
        
            ?>

            <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>

                        <?php
                            foreach($items as $item) {

                                echo "<tr>";
                                    echo "<td>" . $item['Item_ID'] . "</td>";
                                    echo "<td>" . $item['Name'] . "</td>";
                                    echo "<td>" . $item['Description'] . "</td>";
                                    echo "<td>" . $item['Price'] . "</td>";
                                    echo "<td>" . $item['Add_Date'] . "</td>";
                                    echo "<td>" . $item['category_name'] . "</td>";
                                    echo "<td>" . $item['Username'] . "</td>";
                                    echo "<td>
                                            <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                            if($item['Approve'] == 0) {
                                        
                                            echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                            }
                                    echo "</td>";
                                echo "</tr>";
                            }

                        ?>

                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Item</a>
            </div>

            <?php } 
            else {
                
                echo '<div class="container">';
                    echo '<div class="nice-message">There\'s No Items To Show</div>';
                    echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Item</a>';
                echo '</div>';
                
            }
            ?>

            <?php
            
            
        }
        
        elseif ($do == 'Add') {
            
            // Add Items Page
        
            ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name Of Item">
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item">
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item">
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made">
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="member">
                                <option value="0">...</option>
                                <?php
                                    $allMembers = getAllFrom("*", "users", "", "", "UserID");
                                   
                                    foreach ($allMembers as $user) {
                                        echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="category">
                                <option value="0">...</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                                
                                    foreach ($allCats as $cat) {
                                        echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                        $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                        foreach ($childCats as $child) {
                                            echo "<option value='" . $child['ID'] . "'>--- " . $child['Name'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)">
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- Start Image Upload Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" name="image" class="form-control" required="required">
                            </div>
                        </div>
                        <!-- End Image Upload Field -->
                    <!-- Start Save Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                        </div>
                    </div>
                    <!-- End Save Field -->
                </form>
            </div>

            
            <?php
            
        }
        
        elseif ($do == 'Insert') {
            
            // Insert Page
        
            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Get variables from the form

                echo "<h1 class='text-center'>Insert Item</h1>";
                echo "<div class='container'>";

                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $member = $_POST['member'];
                $cat = $_POST['category'];
                $tags = $_POST['tags'];


                // Validate The Form

                $formErrors = array();

                if(empty($name)) {

                    $formErrors[] = 'Name Can\'t Be <strong>Empty</strong>';
                }

                if(empty($desc)) {

                    $formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';
                }

                if(empty($price)) {

                    $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
                }

                if(empty($country)) {

                    $formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';
                }

                if($status == 0) {

                    $formErrors[] = 'You Must Choose The <Strong>Status</strong>';
                }
                
                if($member == 0) {

                    $formErrors[] = 'You Must Choose The <Strong>Member</strong>';
                }
                
                if($cat == 0) {

                    $formErrors[] = 'You Must Choose The <Strong>Category</strong>';
                }

                // Loop into error array and echo it

                foreach($formErrors as $error) {

                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                // Check If There's No Error Proceed The update operation

                if(empty($formErrors)) {

                    // Insert Userinfo in the database

                    $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags) VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

                    $stmt->execute(array(

                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status,
                        'zcat' => $cat,
                        'zmember' => $member,
                        'ztags' => $tags
                    ));

                    $id = $con->lastInsertId();

                    $image_tmp_name = $_FILES['image']['tmp_name'];

                    $imagetypes = array(
                            'image/png' => '.png',
                            'image/gif' => '.gif',
                            'image/jpeg' => '.jpg',
                            'image/bmp' => '.bmp');

                    $extension = $imagetypes[$_FILES['image']['type']];

                    //$extension1 = end(explode(".", $image_tmp_name));

                    //echo $extension1;

                    //$extension = $_FILES['image']['type'];

                    //$new_name = $_SESSION['user'] . "_" . $id . $extension;

                    $new_name = $id . $extension;


                    move_uploaded_file($image_tmp_name, "../layout/images/" .$new_name);

                    $stmt = $con->prepare("UPDATE items SET Image_Name = ? WHERE Item_ID = ?");

                    $stmt->execute(array(

                        $new_name,
                        $id

                    ));

                    // Echo success message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

                    redirectHome($theMsg, 'back');
                    


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
        
        elseif ($do == 'Edit') {
            
            // Edit page
        
            // Check if get request itemid is numeric & get the integer value of it

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select all data depend on this ID

            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");

            // Execute query

            $stmt->execute(array($itemid));

            // Fecth the data

            $item = $stmt->fetch();

            // The row count

            $count = $stmt->rowCount();

            // If there's such ID show the form               

            if($count > 0) {  ?>

                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                        <!-- Start Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" required="required" placeholder="Name Of Item" value="<?php echo $item['Name'] ?>">
                            </div>
                        </div>
                        <!-- End Name Field -->
                        <!-- Start Description Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item" value="<?php echo $item['Description'] ?>">
                            </div>
                        </div>
                        <!-- End Description Field -->
                        <!-- Start Price Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item" value="<?php echo $item['Price'] ?>">
                            </div>
                        </div>
                        <!-- End Price Field -->
                        <!-- Start Price Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made" value="<?php echo $item['Country_Made'] ?>">
                            </div>
                        </div>
                        <!-- End Price Field -->
                        <!-- Start Status Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="status">
                                    <option value="1" <?php if($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
                                    <option value="2" <?php if($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
                                    <option value="3" <?php if($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
                                    <option value="3">Used</option>
                                    <option value="4" <?php if($item['Status'] == 4) { echo 'selected'; } ?>>Very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Field -->
                        <!-- Start Members Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Member</label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="member">
                                    
                                    <?php
                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();
                                        foreach ($users as $user) {
                                            echo "<option value='" . $user['UserID'] . "'"; if($item['Member_ID'] == $user['UserID']) { echo 'selected'; } echo">" . $user['Username'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Members Field -->
                        <!-- Start Categories Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10 col-md-6">
                                <select class="form-control" name="category">
                                    
                                    <?php
                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();
                                        foreach ($cats as $cat) {
                                            echo "<option value='" . $cat['ID'] . "'"; if($item['Cat_ID'] == $cat['ID']) { echo 'selected'; } echo">" . $cat['Name'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Categories Field -->
                        <!-- Start Tags Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Tags</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" value="<?php echo $item['tags'] ?>">
                            </div>
                        </div>
                        <!-- End Tags Field -->
                        <!-- Start Image Upload Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" name="image" class="form-control" required="required">
                            </div>
                        </div>
                        <!-- End Image Upload Field -->
                        <!-- Start Save Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Item" class="btn btn-primary btn-sm">
                            </div>
                        </div>
                        <!-- End Save Field -->
                    </form>
                    
                    <?php
                    
                    // Select All users except admin
        
                    $stmt = $con->prepare("SELECT comments.*, users.Username AS Member
                    FROM comments
                    INNER JOIN users
                    ON users.UserID = comments.user_id
                    WHERE item_id = ?");

                    // Execute the statement

                    $stmt->execute(array($itemid));

                    // Assign To Variable

                    $rows = $stmt->fetchAll();
                            
                    if (!empty($rows)) {

                    ?>

                    <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>Username</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>

                            <?php
                                foreach($rows as $row) {

                                    echo "<tr>";
                                        echo "<td>" . $row['comment'] . "</td>";
                                        echo "<td>" . $row['Member'] . "</td>";
                                        echo "<td>" . $row['comment_date'] . "</td>";
                                        echo "<td>
                                                <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";

                                                if($row['status'] == 0) {

                                                echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                                }
                                        echo "</td>";
                                    echo "</tr>";
                                }

                            ?>

                        </table>
                    </div>
                    <?php } ?>
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
        
        elseif ($do == 'Update') {
            
            // Update Page

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Get variables from the form

                $id = $_POST['itemid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $cat = $_POST['category'];
                $member = $_POST['member'];
                $tags = $_POST['tags'];
                

                // Validate The Form

                $formErrors = array();

                if(empty($name)) {

                    $formErrors[] = 'Name Can\'t Be <strong>Empty</strong>';
                }

                if(empty($desc)) {

                    $formErrors[] = 'Description Can\'t Be <strong>Empty</strong>';
                }

                if(empty($price)) {

                    $formErrors[] = 'Price Can\'t Be <strong>Empty</strong>';
                }

                if(empty($country)) {

                    $formErrors[] = 'Country Can\'t Be <strong>Empty</strong>';
                }

                if($status == 0) {

                    $formErrors[] = 'You Must Choose The <Strong>Status</strong>';
                }
                
                if($member == 0) {

                    $formErrors[] = 'You Must Choose The <Strong>Member</strong>';
                }
                
                if($cat == 0) {

                    $formErrors[] = 'You Must Choose The <Strong>Category</strong>';
                }

                // Loop into error array and echo it

                foreach($formErrors as $error) {

                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }


                // Check If There's No Error Proceed The update operation

                if(empty($formErrors)) {

                    // Update the database with this info

                    $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?,
                    Status = ?, Cat_ID = ?, Member_ID = ?, tags = ? WHERE Item_ID = ?");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                    $id = $con->lastInsertId();

                    $image_tmp_name = $_FILES['image']['tmp_name'];

                    $imagetypes = array(
                            'image/png' => '.png',
                            'image/gif' => '.gif',
                            'image/jpeg' => '.jpg',
                            'image/bmp' => '.bmp');

                    $extension = $imagetypes[$_FILES['image']['type']];

                    //$extension1 = end(explode(".", $image_tmp_name));

                    //echo $extension1;

                    //$extension = $_FILES['image']['type'];

                    //$new_name = $_SESSION['user'] . "_" . $id . $extension;

                    $new_name = $id . $extension;


                    move_uploaded_file($image_tmp_name, "../layout/images/" .$new_name);

                    $stmt = $con->prepare("UPDATE items SET Image_Name = ? WHERE Item_ID = ?");

                    $stmt->execute(array(

                        $new_name,
                        $id

                    ));

                    // Echo success message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

                    redirectHome($theMsg, 'back', 3);


                }


            }
            else {

                $theMsg = '<div class="alert alert-danger">Sorry you can\'t browse this page directly</div>';

                redirectHome($theMsg);
            }

            echo "</div>";
            
        }
        
        elseif ($do == 'Delete') {
            
            // Delete Item Page
        
            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";

            // Check if get request itemid is numeric & get the integer value of it

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select all data depend on this ID

            $check = checkItem('Item_ID', 'items', $itemid);

            // If there's such ID show the form

            if($check > 0) {

                $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");

                $stmt->bindParam(":zid", $itemid);

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
        
        elseif ($do == 'Approve') {
            
            // Approve Item Page
        
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

            // Check if get request itemid is numeric & get the integer value of it

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select all data depend on this ID

            $check = checkItem('Item_ID', 'items', $itemid);

            // If there's such ID show the form

            if($check > 0) {

                $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

                $stmt->execute(array($itemid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated</div>';

                redirectHome($theMsg, 'back');

            }
            else {

                $theMsg = '<div class="alert alert-danger">This ID Not Exist</div>';

                redirectHome($theMsg);
            }

            echo '</div>';
            
        }
        
        include $tpl . 'footer.php';
        
    }

    else {
        
        header('Location: index.php');
        
        exit();
        
    }

    ob_end_flush(); // Release the output

?>
