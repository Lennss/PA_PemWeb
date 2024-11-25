<?php
session_start();
require "../koneksi.php";  // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $nomor_hp = trim($_POST['nomor_hp']);

    if (strlen($username) < 3 || strlen($username) > 20) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Username harus antara 3 dan 20 karakter.'];
        header("Location: register.php");
        exit;
    }

    if (!preg_match("/^\+?\d{10,15}$/", $nomor_hp)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Format nomor HP tidak valid.'];
        header("Location: register.php");
        exit;
    }

    // Set role based on username
    $role = ($username === 'admin') ? 'Admin' : 'User';

    // Hash the password before saving to the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $checkQuery = "SELECT * FROM users WHERE username = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "s", $username);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Username sudah digunakan. Silakan pilih username lain.'];
        header("Location: register.php");
        exit;
    }

    // Insert the user data into the database
    $query = "INSERT INTO users (username, password, nomor_hp, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $username, $hashed_password, $nomor_hp, $role);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Registrasi berhasil!'];
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan. Silakan coba lagi.'];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="../css/login.css" />
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form id="register-form" class="form" method="POST" action="register.php">
                <h2>Register</h2>
                
                <!-- Display notifications if there are any -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert <?php echo ($_SESSION['message']['type'] == 'error') ? 'alert-error' : 'alert-success'; ?>" id="alert-message">
                        <?php echo $_SESSION['message']['text']; ?>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <input type="text" placeholder="Username" id="register-username" name="username" required />
                <input type="tel" placeholder="Nomor Handphone" id="register-phone" name="nomor_hp" required />
                <div class="password-container">
                    <input type="password" placeholder="Password" id="register-password" name="password" required />
                    <span class="toggle-password" onclick="togglePassword('register-password', this)">
                        <svg class="icon" id="eye-icon">
                            <use xlink:href="#mdi--eye-outline"></use>
                        </svg>
                    </span>
                </div>
                <button type="submit">Register</button>
                <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
            </form>
        </div>
    </div>

    <!-- SVG Symbol Definitions -->
    <svg style="display: none">
        <symbol id="mdi--eye-outline" viewBox="0 0 24 24">
            <path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0" />
        </symbol>
        <symbol id="mdi--eye-off-outline" viewBox="0 0 24 24">
            <path fill="currentColor" d="M2 5.27L3.28 4L20 20.72L18.73 22L16 19.27c-1.28.47-2.63.73-4 .73c-5 0-9.27-3.11-11-7.5c.95-2.39 2.67-4.39 4.78-5.73L2 5.27m9.22 4.95l1.83 1.83A3 3 0 0 0 12 9c-.26 0-.5.04-.74.1l-.04.04M12 4.5c2.34 0 4.54.75 6.35 2l-1.43 1.43C15.35 6.42 13.73 6 12 6c-3.63 0-6.8 2.06-8.32 5c.63 1.29 1.6 2.4 2.77 3.24l-1.43 1.42c-1.09-.85-2-1.96-2.63-3.24c1.73-4.39 6-7.5 11-7.5Z" />
        </symbol>
    </svg>

    <script src="../js/login.js"></script>
</body>
</html>
