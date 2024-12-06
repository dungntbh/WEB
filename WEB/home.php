<?php
session_start();
include "Connect.php";

// Truy vấn danh sách sản phẩm
$sql = "SELECT * FROM Products LIMIT 12";
$result = mysqli_query($conn, $sql);
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
    /* Product List Section */
    /* Product List Section */
    .product-list .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        /* Tạo khoảng cách đều giữa các hàng */
        gap: 40px;
        /* Khoảng cách giữa các sản phẩm */
        margin-top: 30px;
    }

    .product {
        background-color: white;
        padding: 20px;
        width: calc(28.33% - 20px);
        /* Đảm bảo 3 sản phẩm mỗi hàng */
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
        height: 170px;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .product {
            width: calc(50% - 20px);
            /* 2 sản phẩm mỗi hàng trên màn hình vừa */
        }
    }

    @media (max-width: 480px) {
        .product {
            width: 100%;
            /* 1 sản phẩm mỗi hàng trên màn hình nhỏ */
        }
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
                    <li><a href="#home.php">Home</a></li>
                    <li><a href="productdetail.php">Product Detail</a></li>
                    <li><a href="cart.php">My Cart</a></li>
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
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row_product = mysqli_fetch_assoc($result)) {
                    $ProductID = $row_product['ProductID'];
                    $ProductName = $row_product['ProductName'];
                    $Price = $row_product['Price'];
                    $Stock = $row_product['Stock'];
                    $Description = $row_product['Description'];
                    $Image = $row_product['Image'];

                    // Lấy thông tin danh mục (nếu có)
                    $CategoryID = $row_product['CategoryID'];
                    $category_sql = "SELECT CategoryName FROM Categories WHERE CategoryID = $CategoryID";
                    $category_result = mysqli_query($conn, $category_sql);
                    $CategoryName = $category_result && mysqli_num_rows($category_result) > 0 ? mysqli_fetch_assoc($category_result)['CategoryName'] : 'N/A';
            ?>
                    <div class="product">
                        <h3><?php echo htmlspecialchars($ProductName); ?></h3>
                        <img src="<?php echo file_exists("image/$Image") ? htmlspecialchars("image/$Image") : 'image/default.jpg'; ?>" alt="<?php echo htmlspecialchars($ProductName); ?>">
                        <p><b>Category: <?php echo htmlspecialchars($CategoryName); ?></b></p>
                        <p><b>Price: <?php echo number_format($Price, 0, ',', '.'); ?> USD</b></p>
                        <p><b>Stock: <?php echo $Stock; ?> available</b></p>
                        <p><b>Description:</b> <?php echo htmlspecialchars($Description); ?></p>
                        <a href="productdetail.php?id=<?php echo $ProductID ?>" class="btn btn-info">Details</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="UserID" value="<?php echo $_SESSION['user_id']; ?>">
                                <input type="hidden" name="ProductID" value="<?php echo $ProductID; ?>">
                                <input type="number" name="Quantity" value="1" min="1">
                                <button type="submit" class="btn btn-primary ">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <p><a href="login.php">Login</a> to add products to your cart.</p>
                        <?php endif; ?>
                    </div>
            <?php
                }
            } else {
                echo "<p>No products found.</p>";
            }
            mysqli_close($conn);
            ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Our Store. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>