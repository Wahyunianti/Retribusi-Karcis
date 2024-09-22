<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('image/tickets.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

</head>

<body>
    <section class="sec-login">
        <div class="wrapp">

        </div>
        <div class="data-ctn">
            <div class="kiri-lgn">
                <p class="judul">Aplikasi e-Rp Karcis</p>
                <img class="gbr-sampul" src="image/login_page.png" alt="">
            </div>
            <div class="knn-lgn">
                <div class="card-lgn">
                    <div class="form-lgn">
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <p>Login</p>
                            <p class="p-karcis">Aplikasi e-Rp Karcis</p>
                            @include('Layout.warning')
                            <input class="input-t" type="text" name="username" id="" placeholder="Username">
                            <div class="pw2">
                                <input class="input-t password-input" type="password" id="password" name="password"
                                    placeholder="Password">
                                <a href="#" id="togglePassword" class="toggle-link"><i class="fa-solid fa-eye"></i></a>
                            </div>
                            <img class="gbr-kunci" src="image/Access.png" alt="">
                            <button class="button-sm" type="submit">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const togglePasswordLink = document.getElementById("togglePassword");
            const togglePasswordIcon = togglePasswordLink.querySelector("i");

            togglePasswordLink.addEventListener("click", function (event) {
                event.preventDefault();

                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);

                if (type === "password") {
                    togglePasswordIcon.classList.remove("fa-eye-slash");
                    togglePasswordIcon.classList.add("fa-eye");
                } else {
                    togglePasswordIcon.classList.remove("fa-eye");
                    togglePasswordIcon.classList.add("fa-eye-slash");
                }
            });
        });
    </script>

</body>

</html>