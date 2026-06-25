<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        if(password_verify($password, $user['password'])){

            $_SESSION['id_user'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];

            header("Location: dashboard.php");
            exit;

        }else{
            $error = "Password salah!";
        }

    }else{
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <style>
        body{
            font-family:Arial;
            background:#f4f4f4;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .card{
            background:white;
            width:350px;
            padding:20px;
            border-radius:8px;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:10px;
        }

        button{
            width:100%;
            padding:10px;
        }

        .error{
            color:red;
        }
    </style>
</head>
<body>

<div class="card">

    <h2>Login</h2>

    <?php
    if(isset($error)){
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button
            type="submit"
            name="login"
        >
            Login
        </button>

    </form>

</div>

</body>
</html>