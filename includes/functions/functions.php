<?php

    /* Get All function v1.0
    Function to get All records from database
    */

    function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {
        
        global $con;
        
        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
        
        $getAll->execute();
        
        $all = $getAll->fetchAll();
        
        return $all;
    }

    
    /* Check if user isn't activated
    function to check the restatus of the user
    */

    function checkUserStatus($user) {
        
        // Check if the user exist in database
        
        global $con;
        
        $stmtx = $con->prepare("SELECT Username, RegStatus FROM users WHERE Username = ? AND RegStatus = 0");
        
        $stmtx->execute(array($user));
       
        $status = $stmtx->rowCount();
        
        return $status;
        
    }

    /* Title function that echo the page title in case the page v 1.0 
    has the variable $pageTitle and echo defaul for other pages */

    function getTitle() {
        
        global $pageTitle;
        
        if(isset($pageTitle)) {
            
            echo $pageTitle;
        }
        
        else {
            
            echo 'Default';
        }
        
    }

    /* Home Redirect Function [ This Function Accept Paramters ] v 2.0
    $theMsg = Echo The Error Message [ error | success | warning ]
    $url = the link you want to redirect to
    seconds = seconds before redirecting */

    function redirectHome($theMsg, $url = null, $seconds = 3) {
        
        if ($url == null) {
            
            $url = 'index.php';
            
            $link = 'Homepage';
        }
        else {
            
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                
                $url = $_SERVER['HTTP_REFERER'];
                
                $link = 'Previous Page';
            }
            else {
                
                $url = 'index.php';
                
                $link = 'Homepage';
            }
        }
        echo $theMsg;
        
        echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
        
        header("refresh:$seconds; url=$url");
        
        exit();
    }

    /* Check Items Functions v 1.0
    Function To check item in database [ function accept paramters ]
    $select = the item to select [ example: user, item, category ]
    $from = the table to select from [ example: user, items, categories ]
    $value = the value of select [ example: osama, box, electronics] */

    function checkItem($select, $from, $value) {
        
        global $con;
        
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        
        $statement->execute(array($value));
        
        $count = $statement->rowCount();
        
        return $count;
        
    }

    /* Count number of items function v1.0
    Function to count number of items rows
    $item = the item to count
    $table = the table to choose from */

    function countItems($item, $table) {
        
        global $con;
        
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        
        $stmt2->execute();
        
        return $stmt2->fetchColumn();
    }

    /* Get latest records function v1.0
    Function to get latest items from database [Users , Items, Comments]
    $select = Field to select
    $table = the table to choose from
    $order = The DESC ordering
    $limit = number of records to get
    */

    function getLatest($select, $table, $order, $limit = 5) {
        
        global $con;
        
        $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        
        $getStmt->execute();
        
        $rows = $getStmt->fetchAll();
        
        return $rows;
    }