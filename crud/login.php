<?php
session_start();
include "config.php";
if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);
    $sql = "SELECT * FROM `user` WHERE `username`='$username' AND `password`='$password'";
    $query = mysqli_query($conn, $sql);
    $res = mysqli_num_rows($query);

    if ($res == 1) {
        $_SESSION['user'] = $username;
        header('location:retrive.php');
    } else {
        echo' <script>alert("Invalid credentials")</script>';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <section class="vh-100" style="background-color: #508bfc;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Sign in</h3>
                            <form action="#" method="POST">
                                <div class="form-outline mb-4">
                                    <input type="text" name="username" id="username" class="form-control form-control-lg" required />
                                    <label class="form-label" for="username">Username</label>
                                </div>
                                <div class="form-outline mb-4">
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" required />
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <button class="btn btn-primary btn-lg btn-block" name="login" id="login" type="submit">Login</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->

    <!-- <script>


        if (document.getElementById('username').value == '') {
                alert('Username : Username cant be blanked');
   
            }
            if (document.getElementById('password').value == '') {
                alert('password : password cant be blanked');
   
            }
    </script> -->
</body>

</html>