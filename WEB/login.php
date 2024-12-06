<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('image/anha.webp');
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
    <script>
        function validateForm() {
            var x = document.getElementById("email").value;
            if (x == null || x == "") {
                alert("Email can not be empty!");
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST" onsubmit="return validateForm()">
            Email: <input type="text" name="email" id="email"><br>
            Password: <input type="password" name="password"><br>
            <input type="submit" name="submit" value="Login">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
        <?php
        include "Connect.php";
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Truy vấn để lấy thông tin người dùng
            $sql = "SELECT * FROM Users WHERE Email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Kiểm tra người dùng
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                // So sánh mật khẩu trực tiếp (lưu ý là chưa sử dụng hash)
                if ($password === $user['Password']) {
                    $_SESSION['user_id'] = $user['UserID']; // Lưu user_id vào session
                    header("Location: home.php"); // Chuyển hướng đến trang chủ
                    exit();
                } else {
                    echo "<p class='error-message'>Invalid email or password.</p>";
                }
            } else {
                echo "<p class='error-message'>Invalid email or password.</p>";
            }
        }

        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
