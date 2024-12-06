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
        height: 300px;
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
                    <li><a href="#productdetail.php">Product Detail</a></li>
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
            include "Connect.php";

            // Check if ProductID is passed in the URL
            if (isset($_GET['id'])) {
                $ProductID = $_GET['id'];

                // Fetch product and its detailed information from both Products and ProductDetails tables
                $sql = "
        SELECT p.ProductID, p.ProductName, p.Price, p.Description, p.Image AS ProductImage,
               pd.Brand, pd.Model, pd.Year, pd.Engine, pd.Mileage, pd.Image AS DetailImage
        FROM Products p
        LEFT JOIN ProductDetails pd ON p.ProductID = pd.ProductID
        WHERE p.ProductID = $ProductID
    ";
            } else {
                // Default product query if no ID is passed
                $sql = "
        SELECT p.ProductID, p.ProductName, p.Price, p.Description, p.Image AS ProductImage,
               pd.Brand, pd.Model, pd.Year, pd.Engine, pd.Mileage, pd.Image AS DetailImage
        FROM Products p
        LEFT JOIN ProductDetails pd ON p.ProductID = pd.ProductID
        LIMIT 1  -- Fetch the first product as default
    ";
            }

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row_product = mysqli_fetch_assoc($result);

                // Assign the fetched values to variables
                $ProductName = $row_product['ProductName'];
                $Price = $row_product['Price'];
                $Description = $row_product['Description'];
                $ProductImage = $row_product['ProductImage'];
                $Brand = $row_product['Brand'];
                $Model = $row_product['Model'];
                $Year = $row_product['Year'];
                $Engine = $row_product['Engine'];
                $Mileage = $row_product['Mileage'];
                $DetailImage = $row_product['DetailImage'];
            } else {
                echo "<p class='text-center'>Product not found.</p>";
            }

            mysqli_close($conn);
            ?>

            <!-- Product Detail Display -->
            <?php if (isset($ProductName)): ?>
                <div class="product-detail">
                    <img src="<?php echo htmlspecialchars("image/$ProductImage"); ?>" alt="<?php echo htmlspecialchars($ProductName); ?>" class="product-image">
                    <div class="product-info">
                        <h2><?php echo htmlspecialchars($ProductName); ?></h2>
                        <p><b>Price: <?php echo number_format($Price, 0, ',', '.'); ?> VNĐ</b></p>
                        <p><b>Description: </b><?php echo nl2br(htmlspecialchars($Description)); ?></p>
                    </div>
                    <div class="product-info">
                        <h3>Details</h3>
                        <p><b>Brand: </b><?php echo htmlspecialchars($Brand); ?></p>
                        <p><b>Model: </b><?php echo htmlspecialchars($Model); ?></p>
                        <p><b>Year: </b><?php echo htmlspecialchars($Year); ?></p>
                    </div>
                    <div class="product-info">
                        <p><b>Engine: </b><?php echo htmlspecialchars($Engine); ?></p>
                        <p><b>Mileage: </b><?php echo htmlspecialchars($Mileage); ?> km</p>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; 2024 Our Store. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>