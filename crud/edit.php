<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location:login.php');
}
include "base.php";
include "config.php";
?>

<?php

if (isset($_POST['submit'])) {
    $empid = $_GET['empid'];
    $fullname = $_POST['fname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $filename = $_FILES['files']['name'];
    $fpath = $_FILES['files']['tmp_name'];
    $ferror = $_FILES['files']['error'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    if (!empty($fullname) && !empty($email) && !empty($contact) && !empty($gender) && !empty($city)) {
        if ($ferror == 0) {
            $desc = 'img/' . $filename;
            move_uploaded_file($fpath, $desc);
            $update = "UPDATE `employee` SET `full name`='$fullname',`email`='$email',`contact no`='$contact',`gender`='$gender',`images`='$desc',`city`='$city',`address`='$address' WHERE `empid` = '$empid' ";
            $result = mysqli_query($conn, $update);
            if ($result == TRUE) {
                $interest_input = $_POST['interest'];
                $all_programs = $_POST['program'];
                // print_r($all_programs);
                print_r($interest_input);
                $selecti = "SELECT * FROM employee_interest WHERE empid = '$empid'";
                $selecti_run = mysqli_query($conn, $selecti);
                $int_val = [];
                foreach ($selecti_run as $int_data) {
                    $int_val[] = $int_data['i_id'];
                    // print_r($int_val);
                }
                // insert new data
                foreach ($interest_input as $intvalue) {
                    echo 'sd';
                    if (!in_array($intvalue, $int_val)) {

                        echo $intvalue . "insert this value<br>";
                        $insertq = "INSERT INTO employee_interest (empid,i_id) VALUES  ('$empid','$intvalue')";
                        echo $insertq;
                        $insertq_run = mysqli_query($conn, $insertq);
                    }
                }
                // delete previously added user data
                foreach ($int_val as $arrval) {
                    if (!in_array($arrval, $interest_input)) {
                        echo $arrval . "delete this row<br>";
                        $deleteq = "DELETE FROM `employee_interest` WHERE empid='$empid' AND i_id='$arrval'";
                        $deleteq_run = mysqli_query($conn, $deleteq);
                    }
                }
                $selectp = "SELECT * FROM employee_program WHERE empid = '$empid'";
                $selectp_run = mysqli_query($conn, $selectp);
                $prog_val = [];
                foreach ($selectp_run as $prog_data) {
                    $prog_val[] = $prog_data['p_id'];
                    // print_r($prog_val);
                }

                foreach ($all_programs as $progvalue) {
                    // echo 'sd';
                    if (!in_array($progvalue, $prog_val)) {

                        echo $progvalue . "insert this value<br>";
                        $insertq2 = "INSERT INTO employee_program (empid,p_id) VALUES  ('$empid','$progvalue')";
                        // echo $insertq2;
                        $insertq2_run = mysqli_query($conn, $insertq2);
                    }
                }
                foreach ($prog_val as $arrval2) {
                    if (!in_array($arrval2, $all_programs)) {
                        // echo $arrval2 . "delete this row<br>";
                        $deleteq2 = "DELETE FROM `employee_program` WHERE empid='$empid' AND p_id='$arrval2'";
                        $deleteq_run2 = mysqli_query($conn, $deleteq2);
                    }
                }
            }
        }
    } else {

        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error:</strong> all fields are required
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>';
    }
    header("Location: http://localhost/test/day_5/crud/retrive.php");
}

?>
<div class="container">
    <form action="" method="post" onsubmit="return validate()" name="insertform" enctype="multipart/form-data">

        <?php
        $empid = $_GET['empid'];
        $showquery = "SELECT * FROM `employee` WHERE `empid` = '$empid' ";
        $showdata = mysqli_query($conn, $showquery);
        $arrdata = mysqli_fetch_assoc($showdata);
        ?>
        <!-- name -->
        <div class="mb-3">
            <label for="fname" class="form-label">Name:</label>
            <input type="text" class="form-control" value="<?php echo $arrdata['full name'] ?>" id="fname" name="fname">
        </div>
        <!-- email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email address:</label>
            <input type="email" class="form-control" value="<?php echo $arrdata['email'] ?>" id="email" name="email">
        </div>
        <!-- contact -->
        <div class="mb-3">
            <label for="contact" class="form-label">Contact No:</label>
            <input type="number" class="form-control" value="<?php echo $arrdata['contact no'] ?>" id="contact" name="contact" required>
        </div>
        <!-- gender -->
        <div class="mb-3">
            <label class="form-label" for="gender">Gender:</label>
            <?php
            $fetch = "SELECT * FROM `gender`";
            $result = mysqli_query($conn, $fetch);
            $rowcounts = mysqli_num_rows($result);
            ?>

            <?php
            for ($i = 1; $i <= $rowcounts; $i++) {
                $row = mysqli_fetch_array($result);
            ?>
                <input type="radio" id="gender" name="gender" value="<?php echo $row['gender'] ?>" <?php echo ($arrdata['gender'] == $row['gender']) ? 'checked' : ''; ?> required><?php echo $row["gender"] ?>

            <?php
            }
            ?>
        </div>
        <!-- image -->
        <div class="mb-3">
            <label for="file" class="form-label">Image:</label>
            <input type="file" class="form-control" id="files" name="files" required>
        </div>
        <!-- programs -->
        <div class="mb-3">
            <label for="program">Programming language</label><br>
            <?php
            $query1 = 'SELECT * FROM program';
            $resultprog = mysqli_query($conn, $query1);
            if (mysqli_num_rows($resultprog) > 0) {
                foreach ($resultprog as $program) {
                    if (isset($_GET['empid'])) {
                        $empid = $_GET['empid'];
                        $user_prog = "SELECT p_id FROM employee_program WHERE empid='$empid'";
                        $user_prog_result = mysqli_query($conn, $user_prog);
                        $p_array = [];
                        foreach ($user_prog_result as $rows) {
                            $p_array[] = $rows['p_id'];
                        }
                    }
            ?>
                    <input type="checkbox" id="program" name="program[]" value="<?php echo $program['p_id']; ?>" <?php echo in_array($program['p_id'], $p_array) ? 'checked' : ''; ?>><?= $program['programs']; ?>
                    <br>
            <?php
                }
            }
            ?>
        </div>
        <!-- interest -->
        <div class="mb-3">
            <label for="interest">Interest:</label>
            <select name="interest[]" multiple id="interest" required>
                <?php

                $query2 = 'SELECT * FROM interest';
                $resultint = mysqli_query($conn, $query2);
                if (mysqli_num_rows($resultint) > 0) {
                    foreach ($resultint as $interest) {
                        if (isset($_GET['empid'])) {
                            $empid = $_GET['empid'];
                            $user_int = "SELECT i_id FROM employee_interest WHERE empid='$empid'";
                            $user_int_result = mysqli_query($conn, $user_int);
                            $i_array = [];
                            foreach ($user_int_result as $rows) {
                                $i_array[] = $rows['i_id'];
                            }
                        }

                ?>
                        <option value="<?php echo $interest['i_id'] ?>" <?php echo in_array($interest['i_id'], $i_array) ? 'selected' : ''; ?>><?php echo $interest['interest'] ?></option>
                        <br>
                <?php

                    }
                }
                ?>
            </select>
            <?php

            ?>

        </div>
        <!-- city -->
        <div class="mb-3">
            <?php
            $fetch = "SELECT * FROM `city`";
            $result = mysqli_query($conn, $fetch);
            $rowcounts = mysqli_num_rows($result);
            ?>
            <label for="city">City:</label>
            <select name="city" id="city" required>
                <option value="none" readonly>Select any one
                    <?php
                    for ($i = 1; $i <= $rowcounts; $i++) {
                        $row = mysqli_fetch_array($result);
                    ?>
                <option value="<?php echo $row["city"] ?>" <?php echo ($arrdata['city'] == $row['city']) ? 'selected' : ''; ?>><?php echo $row["city"] ?></option>
            <?php
                    }
            ?>
            </select>
        </div>
        <!-- address -->
        <div class="mb-3">
            <label for="address" class="form-label">Adress:</label>
            <textarea class="form-control" name="address" id="address" cols="" rows="3"></textarea>
        </div>

        <button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script>
    function validate() {
        var name = document.forms["insertform"]["fname"].value;
        if (name == "") {
            alert("Please enter the name");
            return false;
        }
        var email = document.forms["insertform"]["email"].value;
        if (email == "") {
            alert("Please enter the email");
            return false;
        } else {
            var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
            var x = re.test(email);
            if (x) {} else {
                alert("Email id not in correct format");
                return false;
            }
        }

        

        var contact = document.forms["insertform"]["contact"].value;
        if (contact == "") {
            alert("Please enter the contact number");
            return false;
        } else {
            var re = /^[1-9]\d{9}$/;
            var x = re.test(contact);
            if (x) {} else {
                alert("contact no is not in correct format");
                return false;
            }
        }



        // if (document.getElementById('program').checked == false) {
        //     alert("program : please check at least one of the options");
        // }


        if (document.getElementById('interest').value == '') {
                alert('Interest : Please select at least one value');
   
            }
        } 
</script>

</body>

</html>