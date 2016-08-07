<?php
    
    session_start();

    $pageTitle = 'Create New Item';
    
    include 'init.php';
    
    if(isset($_SESSION['user'])) {

        $new_name = '';
        
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $formErrors = array();
        
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);


        
        
        //$imageName = addslashes($_FILES['image']['name']);
        //$imageData = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        //$imageType = addslashes($_FILES['image']['type']);
        
        if(strlen($name) < 4) {
            
            $formErrors[] = 'Item Title Must Be At Least 4 Characters';
        }
        
        if(strlen($desc) < 10) {
            
            $formErrors[] = 'Item Title Must Be At Least 10 Characters';
        }
        
        if(strlen($country) < 2) {
            
            $formErrors[] = 'Item Title Must Be At Least 2 Characters';
        }
        
        if(empty($price)) {
            
            $formErrors[] = 'Item Price Must Be Not Empty';
        }
        
        if(empty($status)) {
            
            $formErrors[] = 'Item Status Must Be Not Empty';
        }
        
        if(empty($category)) {
            
            $formErrors[] = 'Item Category Must Be Not Empty';
        }
        
        if(empty($formErrors)) {

                    // Insert Userinfo in the database

                    $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags) VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

                    $stmt->execute(array(

                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status,
                        'zcat' => $category,
                        'zmember' => $_SESSION['uid'],
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


                    move_uploaded_file($image_tmp_name, "layout/images/" .$new_name);

                    $stmt = $con->prepare("UPDATE items SET Image_Name = ? WHERE Item_ID = ?");

                    $stmt->execute(array(

                        $new_name,
                        $id

                    ));

                    //$stmt = $con->prepare("INSERT INTO images(Image_Name, User_ID) VALUES(:zimagename, :zuserid)");

                    //$stmt->execute(array(

                        //'zimagename' => $new_name,
                        //'zuserid' => $_SESSION['uid']

                    //));

                    // Echo success message

                    if ($stmt) {
                        
                        $successMsg = 'Item Is Successfully Added';
                    


                        
                    }
                    
                    
                }

        
    }
?>

    <h1 class="text-center"><?php echo $pageTitle ?></h1>

    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="name" class="form-control live" required="required" placeholder="Name Of Item" data-class=".live-title">
                                    </div>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Description Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="description" class="form-control live" required="required" placeholder="Description Of The Item" data-class=".live-desc">
                                    </div>
                                </div>
                                <!-- End Description Field -->
                                <!-- Start Price Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="price" class="form-control live" required="required" placeholder="Price Of The Item" data-class=".live-price">
                                    </div>
                                </div>
                                <!-- End Price Field -->
                                <!-- Start Price Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made">
                                    </div>
                                </div>
                                <!-- End Price Field -->
                                <!-- Start Status Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
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
                                
                                <!-- Start Categories Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select class="form-control" name="category">
                                            <option value="0">...</option>
                                            <?php
                                                $cats = getAllFrom('*', 'categories', '', '', 'ID');
                                                
                                                foreach ($cats as $cat) {
                                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Categories Field -->
                                <!-- Start Tags Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)">
                                    </div>
                                </div>
                                <!-- End Tags Field -->
                                <!-- Start Image Upload Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Image</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="file" name="image" class="form-control" required="required">
                                    </div>
                                </div>
                                <!-- End Image Upload Field -->
                                <!-- Start Save Field -->
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                                    </div>
                                </div>
                                <!-- End Save Field -->
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">$<span class="live-price"></span></span>
                                <?php
                                  
                                    //if(isset(image)) {

                                    //$stmt2 = $con->prepare("SELECT * FROM items WHERE Item_ID = 33");
        
                                    //$stmt2->execute();

                                    

                                    //$row = $stmt2->fetch();
                                    
                                    //$res = mysql_query("select * from items where Item_ID = 33");
        
                                    //while($row=mysql_fetch_array($res)) {
                                        
                                        //echo '<img class="img-responsive" src="data:image/jpeg;base64,'.base64_encode( $row['Image_Data'] ).'" alt="" />';
                                    //}
                                   
                                    
                                    

                                if(!empty($new_name)) {    

                                ?>
                                
                                <img class="img-responsive" src="layout/images/<?php echo $new_name ?>" alt="Hello" />
                                <?php
                                } ?>

                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Looping through errors */ -->
                    <?php
        
                        if(!empty($formErrors)) {
                            
                            foreach($formErrors as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
        
                        if (isset($successMsg)) {
                
                            echo '<div class="alert alert-success">' . $successMsg . '</div>';
                        }
        
                    ?>
                    <!-- End Looping through errors */ -->
                </div>
            </div>
        </div>
    </div>

    

<?php
    }
    else {
        
        header('Location: login.php');
        
        exit();
    }

    include $tpl . "footer.php";
?>