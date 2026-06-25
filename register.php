```php
<?php

include 'koneksi.php';

if(isset($_POST['register'])){

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $password = $_POST['password'];

    if(!preg_match('/^[0-9]{14}$/', $no_hp)){

        $error = "Nomor HP harus terdiri dari 14 digit angka.";

    }else{

        $cek = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE email='$email'"
        );

        if(mysqli_num_rows($cek) > 0){

            $error = "Email sudah digunakan";

        }else{

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $query = mysqli_query(
                $conn,
                "INSERT INTO users
                (nama,email,no_hp,password)
                VALUES
                ('$nama','$email','$no_hp','$password_hash')"
            );

            if($query){

                header("Location: login.php");
                exit;

            }else{

                $error = "Register gagal";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

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
            width:350px;
            background:white;
            padding:20px;
            border-radius:8px;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:10px;
            box-sizing:border-box;
        }

        button{
            width:100%;
            padding:10px;
            cursor:pointer;
        }

        .error{
            color:red;
            margin-bottom:10px;
        }

    </style>

</head>
<body>

<div class="card">

    <h2>Register</h2>

    <?php
    if(isset($error)){
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST">

        <input
            type="text"
            name="nama"
            placeholder="Nama"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="text"
            name="no_hp"
            maxlength="14"
            minlength="14"
            pattern="[0-9]{14}"
            placeholder="Nomor HP (14 digit)"
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
            name="register"
        >
            Daftar
        </button>

    </form>

</div>

</body>
</html>
```
