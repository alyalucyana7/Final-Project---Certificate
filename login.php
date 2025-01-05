<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Tivicate-DKM Jamie Su'ada</title>
    <link rel="stylesheet" href="login-style.css">

    <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
    <style>

        /* Global Styling */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Login Container */
.login-container {
    width: 100%;
    max-width: 400px;
    margin: auto;
}

.login-box {
    background: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}


/* Logo Styling */
/* Logo Styling */
.logo img {
    width: 150px; /* Sesuaikan ukuran logo */
    height: auto;
    margin-bottom: 20px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.logo-text {
    text-transform: lowercase;
}

/* Form Styling */
.login-box h2 {
    margin-bottom: 20px;
    font-size: 20px;
    color: #333;
}

form input[type="text"],
form input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 14px;
}

form button {
    width: 100%;
    padding: 10px;
    background-color: #2ecc71;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

form button:hover {
    background-color: #27ae60;
}

/* Additional Links */
.additional-links {
    margin-top: 15px;
}

.additional-links a {
    font-size: 14px;
    color: #007bff;
    text-decoration: none;
}

.additional-links a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- Logo Gambar -->
            <div class="logo">
               <img src="./images/dkm.jpeg" alt="Tivicate Logo">
            </div>
            <h2>Login Penyelenggara</h2>
            <form action="./backend/login.php" method="POST">
                <input type="text" name="username" placeholder="Masukkan Username Anda" required>
                <input type="password" name="password" placeholder="Masukkan Password Anda" required>
                <button type="submit" name="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

