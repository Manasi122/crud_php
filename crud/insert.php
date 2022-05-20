<?php
// error_reporting(0);
session_start();
if (!isset($_SESSION['user'])) {
    header('location:login.php');
}
include "base.php";
include "config.php";
?>


<div class="container">
    <?php

    if (isset($_POST['submit'])) {
        $fullname = $_POST['fname'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $gender = $_POST['gender'];
        $filename = $_FILES['files']['name'];
        // print_r($filename);
        $fpath = $_FILES['files']['tmp_name'];
        // print_r($fpath);
        $ferror = $_FILES['files']['error'];
        // print_r($ferror);
        $program = isset($_POST['program']) ? $_POST['program'] : '';
        // $chkprogram = implode(",", $program);
        $interest = $_POST['interest'];
        // $chkinterest = implode(",", $interest);
        $city = $_POST['city'];
        $address = $_POST['address'];

        $duplicate = mysqli_query($conn, "select `email`,`contact no` from employee where `email`='$email' or `contact no`='$contact'");
        if (mysqli_num_rows($duplicate) > 0) {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> Email or contact no already exists.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>';
        } else {
            try {


                if (!empty($fullname) && !empty($email) && !empty($contact) && !empty($city) && !empty($gender) && !empty($interest)  && !empty($program)) {
                    if ($ferror == 0) {
                        $desc = 'img/' . $filename;
                        move_uploaded_file($fpath, $desc);
                        $insert = "INSERT INTO `employee` (`full name`, `email`, `contact no`, `gender`, `images`,`city`, `address`) VALUES ('$fullname', '$email', '$contact', '$gender', '$desc','$city','$address');";
                        $result = mysqli_query($conn, $insert);

                        if ($result == true) {
                            $id = $conn->insert_id;
                            if (isset($_POST['submit'])) {
                                $all_interest = $_POST['interest'];
                                // print_r($all_interest);
                                $all_programs = $_POST['program'];
                                foreach ($all_interest as $value) {
                                    $data = "INSERT INTO `employee_interest`(empid,i_id)VALUES('$id','$value')";
                                    $data_result = mysqli_query($conn, $data);
                                }
                                foreach ($all_programs as $value1) {
                                    $data = "INSERT INTO `employee_program`(empid,p_id)VALUES('$id','$value1')";
                                    $data_result = mysqli_query($conn, $data);
                                }
                            }
                        }
                    }
                    header("Location: http://localhost/test/day_5/crud/retrive.php");
                } else {
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> all fields are required
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            } catch (Exception $e) {
                echo $insert . "
                    " . $e->getMessage();
            }
        }
    }
    ?>

    <form action="insert.php" onsubmit="return validate()" name="insertform" method="post" enctype="multipart/form-data">
        <!-- name -->
        <div class="mb-3">
            <label for="fname" class="form-label">Name:</label>
            <input type="text" class="form-control" id="fname" name="fname" required>
        </div>
        <!-- email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email address:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <!-- contact -->
        <div class="mb-3">
            <label for="contact" class="form-label">Contact No:</label>
            <input type="number" class="form-control" id="contact" name="contact" required>
        </div>
        <!-- gender -->
        <div class="mb-3">
            <label class="form-label" for="gender">Gender:</label>
            <?php
            $sql = "SELECT * FROM `gender`";
            $result = mysqli_query($conn, $sql);
            // print_r($result);
            $rowcount = mysqli_num_rows($result);
            // echo $rowcount;
            ?>
            <!--radiobutton-->
            <?php
            for ($i = 1; $i <= $rowcount; $i++) {
                $row = mysqli_fetch_array($result);
            ?>
                <input class="form-check-input" type="radio" id="gender" name="gender" value="<?php echo $row["gender"]; ?>" required><?php echo $row["gender"]; ?>
            <?php
            }
            ?>
        </div>
        <!-- image -->
        <div class="mb-3">
            <label for="file" class="form-label">Image:</label>
            <input type="file" class="form-control" id="files" accept="image/*" name="files" required>
        </div>
        <!-- programs -->
        <div class="mb-3">
            <?php
            $fetch = "SELECT * FROM `program`";
            $result = mysqli_query($conn, $fetch);
            $rowcounts = mysqli_num_rows($result);
            ?>
            <label for="program">Programming language</label><br>
            <?php
            for ($i = 1; $i <= $rowcounts; $i++) {
                $row = mysqli_fetch_array($result);
            ?>
                <input type="checkbox" id="program" name="program[]" value="<?php echo $row["p_id"] ?>"><?php echo $row["programs"] ?>
                <br>
            <?php
            }
            ?>
        </div>
        <!-- interest -->
        <div class="mb-3">
            <?php
            $fetch = "SELECT * FROM `interest`";
            $result = mysqli_query($conn, $fetch);
            $rowcounts = mysqli_num_rows($result);
            ?>
            <label for="interest">Interest:</label>
            <select name="interest[]" multiple id="interest " required>
                <?php
                for ($i = 1; $i <= $rowcounts; $i++) {
                    $row = mysqli_fetch_array($result);

                ?>
                    <option value="<?php echo $row["i_id"] ?>"><?php echo $row["interest"] ?></option>
                <?php
                }
                ?>
            </select>
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
                <!-- <option value="none" readonly>Select any one -->
                <?php
                for ($i = 1; $i <= $rowcounts; $i++) {
                    $row = mysqli_fetch_array($result);

                ?>
                    <option value="<?php echo $row["city"] ?>"><?php echo $row["city"] ?></option>
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

        <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
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

        if (document.insertform.gender.value == '') {
            alert("Gender : please check one of the options");
            return false;
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