<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location:login.php');
}
include "config.php";
include "base.php";
?>

<div class="table-responsive container-fluid ">
    <table border="2px solid"  class="table" style="background-color:#F6F6F6;">
        <thead>
            <th>Id</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Contact No</th>
            <th>Gender</th>
            <th>Image</th>
            <th>Programming knowledge</th>
            <th>Interest</th>
            <th>City</th>
            <th>Address</th>
            <th>action</th>
        </thead>
        <tbody>

            <?php
            $select = "SELECT * FROM `employee`";
            $result = mysqli_query($conn, $select);
            while ($arr = mysqli_fetch_assoc($result)) {

                


            ?>
                <tr>
                    <td><?php echo $arr['empid'] ?></td>
                    <td><?php echo $arr['full name'] ?></td>
                    <td><?php echo $arr['email'] ?></td>
                    <td><?php echo $arr['contact no'] ?></td>
                    <td><?php echo $arr['gender'] ?></td>
                    <td><img height="50px" width="50px" src="<?php echo $arr['images'] ?>" alt=""></td>
                    <td>
                        <?php 
                        $empid = $arr['empid'];
                        $sql1= "SELECT * FROM employee_program_view WHERE empid = $empid";
                        $result1 = mysqli_query($conn,$sql1);
                        $str1 = "" ;
                        while ($arr1 = mysqli_fetch_assoc($result1)) {
                            $str1 .=$arr1['programs'].",";
                        }
                        echo $str1;
                        ?>
                    </td>
                    <td>
                    <?php 
                        $empid = $arr['empid'];
                        $sql2= "SELECT * FROM employee_interest_view WHERE empid = $empid";
                        $result2 = mysqli_query($conn,$sql2);
                        $str2 = "" ;
                        while ($arr2 = mysqli_fetch_assoc($result2)) {
                            $str2 .=$arr2['interest'].",";
                        }
                        echo $str2;
                        ?>
                    </td>
                    <td><?php echo $arr['city'] ?></td>
                    <td><?php echo $arr['address'] ?></td>
                    <td><a href="./edit.php?empid=<?php echo $arr['empid'];?>"><i style="font-size: 30px;" class="bi bi-pencil-square text-warning"></i></a></td>
                    <td><a href="delete.php?empid=<?php echo $arr['empid'];?>"><i style="font-size: 30px;" class="bi bi-trash-fill text-danger"></i></a></td>

                </tr>
        </tbody>
    <?php
            }
    ?>
    </table>
</div>

</body>

</html>



<!-- select employee.empid,employee.`full name`, employee.email,employee.`contact no`,employee.`gender`,employee.images,employee.city,employee.address,interest_view.interest,program_view.programs from employee INNER JOIN interest_view ON employee.empid = interest_view.empid INNER JOIN program_view ON employee.empid = program_view.empid; -->


<!-- SELECT empid, GROUP_CONCAT(interest SEPARATOR ',') from interest_view GROUP BY empid; -->
