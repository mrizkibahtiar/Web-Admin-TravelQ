<?php
require 'config.php';

// if (!empty($_SESSION["id"])) {
//     header("Location: index.html");
// }
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $duplicate = mysqli_query($conn, "SELECT * FROM tb_user WHERE email = '$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo
            "<script>alert('Email sudah digunakan')</script>";
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmpassword = $_POST["confirmpassword"];
    } else {
        if ($password == $confirmpassword) {
            $query = "INSERT INTO tb_user VALUES('', '$name', '$email', '$password')";
            mysqli_query($conn, $query);
            echo
                "<script>alert('Pendaftaran Sukses')</script>";
            header("refresh:1;url=signin.php");
        } else {
            echo
                "<script>alert('Password Tidak Cocok')</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="src/output.css">
</head>

<body class="font-poppins">
    <main class="w-full min-h-screen bg-gradient-to-b from-[#091321] to-[#0c8aee] ">
        <div class="w-full h-fit py-16 md:w-[50%] md:mx-auto lg:w-[40%]">
            <div>
                <div class="w-full h-fit flex justify-center items-center flex-col">
                    <h1 class="text-[#0c8aee] font-semibold text-5xl mb-4 text-center">Buat Akun Baru</h1>
                    <p class="text-white">Halo👋</p>
                </div>
                <form action="" method="post" class="mt-5 flex flex-col px-10" autocomplete="off">
                    <label for="name" class="text-white mb-1">Nama</label>
                    <input oninvalid="this.setCustomValidity('Masukkan nama dengan benar!')"
                        oninput="this.setCustomValidity('')" type="text" name="name" id="name"
                        class="name h-14 rounded-2xl mb-3 outline-none px-4 bg-[rgba(255,255,255)]/30 text-white"
                        required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    <label for="email" class="text-white mb-1">Email</label>
                    <input oninvalid="this.setCustomValidity('Masukkan email dengan benar!')"
                        oninput="this.setCustomValidity('')" type="email" name="email" id="email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        class="email h-14 rounded-2xl mb-3 outline-none px-4 bg-[rgba(255,255,255)]/30 text-white"
                        required>
                    <label for="password" class="text-white mb-1 ">Password</label>
                    <div class="w-full relative">
                        <input type="password" name="password" id="password"
                            class="password w-full h-14 outline-none rounded-2xl pl-4 pr-12 bg-[rgba(255,255,255)]/30 text-white"
                            required autocomplete="on"
                            oninvalid="this.setCustomValidity('Masukkan password dengan benar!')"
                            oninput="this.setCustomValidity('')">
                        <svg class="eye1 absolute top-4 right-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24">
                            <path d=" M12 4.998c-1.836 0-3.356.389-4.617.971L3.707 2.293 2.293 3.707l3.315 3.316c-2.613
                                1.952-3.543 4.618-3.557 4.66l-.105.316.105.316C2.073 12.382 4.367 19 12 19c1.835 0 3.354-.389
                                4.615-.971l3.678 3.678 1.414-1.414-3.317-3.317c2.614-1.952 3.545-4.618
                                3.559-4.66l.105-.316-.105-.316c-.022-.068-2.316-6.686-9.949-6.686zM4.074
                                12c.103-.236.274-.586.521-.989l5.867 5.867C6.249 16.23 4.523 13.035 4.074 12zm9.247
                                4.907-7.48-7.481a8.138 8.138 0 0 1 1.188-.982l8.055 8.054a8.835 8.835 0 0
                                1-1.763.409zm3.648-1.352-1.541-1.541c.354-.596.572-1.28.572-2.015
                                0-.474-.099-.924-.255-1.349A.983.983 0 0 1 15 11a1 1 0 0 1-1-1c0-.439.288-.802.682-.936A3.97
                                3.97 0 0 0 12 7.999c-.735 0-1.419.218-2.015.572l-1.07-1.07A9.292 9.292 0 0 1 12 6.998c5.351 0
                                7.425 3.847 7.926 5a8.573 8.573 0 0 1-2.957 3.557z">
                            </path>
                        </svg>
                    </div>
                    <label for="confirmpassword" class="text-white mb-1 mt-3">Konfirmasi Password</label>
                    <div class="w-full relative">
                        <input oninvalid="this.setCustomValidity('Masukkan konfirmasi password dengan benar!')"
                            oninput="this.setCustomValidity('')" type="password" name="confirmpassword"
                            id="confirmpassword"
                            class="confirmpassword w-full h-14 outline-none rounded-2xl pl-4 pr-12 bg-[rgba(255,255,255)]/30 text-white"
                            required autocomplete="on">
                        <svg class="eye2 absolute top-4 right-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24">
                            <path d=" M12 4.998c-1.836 0-3.356.389-4.617.971L3.707 2.293 2.293 3.707l3.315 3.316c-2.613
                                1.952-3.543 4.618-3.557 4.66l-.105.316.105.316C2.073 12.382 4.367 19 12 19c1.835 0 3.354-.389
                                4.615-.971l3.678 3.678 1.414-1.414-3.317-3.317c2.614-1.952 3.545-4.618
                                3.559-4.66l.105-.316-.105-.316c-.022-.068-2.316-6.686-9.949-6.686zM4.074
                                12c.103-.236.274-.586.521-.989l5.867 5.867C6.249 16.23 4.523 13.035 4.074 12zm9.247
                                4.907-7.48-7.481a8.138 8.138 0 0 1 1.188-.982l8.055 8.054a8.835 8.835 0 0
                                1-1.763.409zm3.648-1.352-1.541-1.541c.354-.596.572-1.28.572-2.015
                                0-.474-.099-.924-.255-1.349A.983.983 0 0 1 15 11a1 1 0 0 1-1-1c0-.439.288-.802.682-.936A3.97
                                3.97 0 0 0 12 7.999c-.735 0-1.419.218-2.015.572l-1.07-1.07A9.292 9.292 0 0 1 12 6.998c5.351 0
                                7.425 3.847 7.926 5a8.573 8.573 0 0 1-2.957 3.557z">
                            </path>
                        </svg>
                    </div>
                    <button name="submit" type="submit"
                        class="register mt-3 w-full bg-[#091321] text-white h-14 rounded-2xl hover:bg-white hover:text-black transition-all duration-300">Register</button>
                </form>
                <p class="mt-2 w-fit mx-auto">Sudah punya akun? <a href="signin.php"
                        class="hover:text-white transition-all duration-100">Sign
                        in</a></p>
            </div>
        </div>
    </main>
    <script>
        // logic for show password
        const eye1 = document.querySelector('.eye1');
        const eye2 = document.querySelector('.eye2');
        const password = document.querySelector('.password');
        const confirmpassword = document.querySelector('.confirmpassword');


        console.log(eye1, eye2, password, confirmpassword);


        eye1.addEventListener('click', function () {
            eye1.classList.toggle('fill-white');
            if (eye1.classList.contains('fill-white')) {
                password.setAttribute('type', 'text');
            } else {
                password.setAttribute('type', 'password');
            }
        })
        eye2.addEventListener('click', function () {
            eye2.classList.toggle('fill-white');
            if (eye2.classList.contains('fill-white')) {
                confirmpassword.setAttribute('type', 'text');
            } else {
                confirmpassword.setAttribute('type', 'password');
            }
        })

        // eye.addEventListener('click', () => {
        //     

        // })

        // logic for register
        const userName = document.querySelector('.name');
        const email = document.querySelector('.email');
        const register = document.querySelector('.register');

        register.addEventListener('click', function () {
            alert(eye);
        })
    </script>
</body>

</html>