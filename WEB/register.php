<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        .register-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            height: 470px;
            text-align: center;
          
        }

        h1 {
            margin-bottom: 15px;
            color: #333;
            margin-top: -5px;
        }

        label {
            text-align: left;
            width: 100%;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 7px;
            margin: -4px 0;
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

        p {
            font-size: 14px;
            margin-top: 10px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateForm() {
            var x = document.getElementById("email").value;
            var y = document.getElementById("full_name").value;
            if (x == null || x == "" || y == null || y == "") {
                alert("FullName or Email cannot be empty!");
                return false;
            }
        }
    </script>
</head>

<body>
    <div class="register-container">
        <h1>Register</h1>
        <form action="" method="POST" onsubmit="return validateForm()">
            <label for="full_name">Full Name :</label>
            <input type="text" name="full_name" id="full_name" required><br><br>

            <label for="email">Email :</label>
            <input type="email" name="email" id="email"><br><br>

            <label for="password">Password :</label>
            <input type="password" name="password" required><br><br>

            <label for="phone_number">Phone Number :</label>
            <input type="text" name="phone_number" id="phone_number"><br><br>

            <label for="address">Address :</label>
            <input type="text" name="address" id="address"><br><br>

            <!-- Optional: Role selection (If necessary) -->
            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="Customer">Customer</option>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
            </select><br><br>

            <input type="submit" name="submit" value="Register">
            <p>Already have an account? <a href="login.php">Login Now</a></p>
        </form>
    </div>

    <?php
    include "Connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Receive data from the form
        $full_name = $_POST["full_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $phone_number = $_POST["phone_number"];
        $address = $_POST["address"];
        $role = $_POST["role"];  // Role field from form

        // Use prepared statements to avoid SQL injection
        $sql = "INSERT INTO Users (FullName, Email, Password, PhoneNumber, Address, Role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters to the SQL query
        mysqli_stmt_bind_param($stmt, "ssssss", $full_name, $email, $password, $phone_number, $address, $role);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

</body>

</html>
