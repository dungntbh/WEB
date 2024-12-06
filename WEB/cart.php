<?php
include "Connect.php";
session_start();
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    
</head>
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        width: 80%;
        margin: 0 auto;
    }

    /* Header Styles */
    header {
        background-color: #333;
        color: white;
        padding: 20px 0;
    }

    header h1 {
        text-align: center;
        margin: 0;
    }

    header nav ul {
        list-style: none;
        padding: 0;
        text-align: center;
    }

    header nav ul li {
        display: inline;
        margin-right: 15px;
    }

    header nav ul li a {
        color: white;
        text-decoration: none;
    }

    /* Search Section */
    .search-section {
        background-color: #fff;
        padding: 20px 0;
        margin-top: 20px;
    }

    .search-section form {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .search-section input[type="text"] {
        padding: 10px;
        width: 50%;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .search-section button {
        padding: 10px 20px;
        background-color: #333;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-section button:hover {
        background-color: #555;
    }

    /* Product List Section */
    .product-list .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 40px;
        margin-top: 30px;
    }

    .product {
        background-color: white;
        padding: 20px;
        width: calc(28.33% - 20px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        transition: transform 0.3s ease-in-out;
    }

    .product:hover {
        transform: translateY(-5px);
    }

    .product button {
        margin: 0 15px;
    }

    .product img {
        width: 100%;
        height: 200px;
        border-radius: 5px;
    }

    .product h3 {
        font-size: 18px;
        color: #333;
    }

    .product p {
        font-size: 14px;
        color: #666;
    }

    .product input {
        width: 100px;
        height: 31px;
    }

    .product .btn {
        display: inline-block;
        padding: 10px 15px;
        background-color: #333;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
    }

    .product .btn:hover {
        background-color: #555;
    }

    /* Product Detail Section */
    .product-detail {
        display: flex;
        align-items: center;
        gap: 20px;
        /* Khoảng cách giữa ảnh và thông tin */
        margin-top: 20px;
    }

    .product-detail .product-image {
        width: 300px;
        height: auto;
        border-radius: 8px;
    }

    .product-detail .product-info {
        flex: 1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product {
            width: calc(50% - 20px);
            /* 2 sản phẩm mỗi hàng trên màn hình vừa */
        }

        .product-detail {
            flex-direction: column;
            text-align: center;
        }

        .product-detail .product-image {
            width: 80%;
        }
    }

    @media (max-width: 480px) {
        .product {
            width: 100%;
            /* 1 sản phẩm mỗi hàng trên màn hình nhỏ */
        }

        .product-detail {
            flex-direction: column;
            text-align: center;
            margin: 30px;
        }

        .product-detail .product-image {
            width: 100%;
        }
    }

    .product-detail .product-info {
        margin: 1px 30px;
    }

    /* Footer Styles */
    footer {
        background-color: #333;
        color: white;
        padding: 20px 0;
        text-align: center;
        margin-top: 30px;
    }
</style>
<body>
    <header>
        <div class="container">
            <h1>Welcome to Our Store</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="productdetail.php">Product Detail</a></li>
                    <li><a href="#cart.php">My Cart</a></li>
                    <li><a href="productmanagement.php">Product Management</a></li>
                    <li><a href="login.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="search-section">
        <div class="container">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for products...">
                <button type="submit">Search</button>
            </form>
        </div>
    </section>

    <section class="product-list">
        <div class="container">
            <?php
            // Check if the form has been submitted and if the user is logged in
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
                $userID = $_SESSION['user_id'];
                $productID = $_POST['ProductID'];
                $quantity = $_POST['Quantity'];

                // Check if the product already exists in the cart
                $checkCartSql = "SELECT * FROM Cart WHERE UserID = ? AND ProductID = ?";
                $stmt = mysqli_prepare($conn, $checkCartSql);
                mysqli_stmt_bind_param($stmt, "ii", $userID, $productID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    // If product exists in cart, update the quantity
                    $updateSql = "UPDATE Cart SET Quantity = Quantity + ? WHERE UserID = ? AND ProductID = ?";
                    $stmt = mysqli_prepare($conn, $updateSql);
                    mysqli_stmt_bind_param($stmt, "iii", $quantity, $userID, $productID);
                } else {
                    // If product does not exist in cart, insert it into the cart
                    $insertSql = "INSERT INTO Cart (UserID, ProductID, Quantity) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $insertSql);
                    mysqli_stmt_bind_param($stmt, "iii", $userID, $productID, $quantity);
                }

                // Execute the query and redirect if successful
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to cart page after successfully adding the product
                    header("Location: cart.php");
                    exit(); // Stop further execution after header redirection
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }

            // Displaying cart contents
            if (isset($_SESSION['user_id'])) {
                $userID = $_SESSION['user_id'];

                // Query to fetch cart items
                $sql = "SELECT p.ProductName, p.Image, c.Quantity, p.Price
                        FROM Cart c
                        INNER JOIN Products p ON c.ProductID = p.ProductID
                        WHERE c.UserID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $userID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // Check if there are any products in the cart
                if (mysqli_num_rows($result) > 0) {
                    // Display the cart items
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='product'>";
                        echo "<h3>" . htmlspecialchars($row['ProductName']) . "</h3>";
                        echo "<img src='image/" . htmlspecialchars($row['Image']) . "' alt='" . htmlspecialchars($row['ProductName']) . "'>";
                        echo "<p>Quantity: " . $row['Quantity'] . "</p>";
                        echo "<p>Price: " . number_format($row['Price'], 0, ',', '.') . " USD</p>";
                        echo "<p>Total: " . number_format($row['Price'] * $row['Quantity'], 0, ',', '.') . " USD</p>";
                        echo "</div>";
                    }
                } else {
                    // If cart is empty, display a message
                    echo "<p>Your cart is empty. Please add items to your cart.</p>";
                }
            } else {
                echo "Please <a href='login.php'>login</a> to view your cart.";
            }
            ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Our Store. All Rights Reserved.</p>
    </footer>
</body>

</html>
