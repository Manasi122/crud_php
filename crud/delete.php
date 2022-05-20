<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location:login.php');
}
include "config.php";
include "base.php";
// if (isset($_GET['empid'])) {
    $empid = $_GET['empid'];
    $delete = "DELETE FROM `employee` WHERE `empid`='$empid'";
    $result = $conn->query($delete);
     if ($result == TRUE) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
        header("location:./retrive.php");
        
    }else{
        echo "Error:" . $delete . "<br>" . $conn->error;
    }
// } 
?>

</body>

</html>


