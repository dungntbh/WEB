<?php
// Connect to the database
include "Connect.php";

// Check if the request is to add, edit, or delete a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        // Add product
        addProduct($conn);
    } elseif (isset($_POST['edit_product'])) {
        // Edit product
        editProduct($conn);
    }
}

if (isset($_GET['delete_id'])) {
    // Delete product
    deleteProduct($conn, $_GET['delete_id']);
}

// Add product function
function addProduct($conn)
{
    $productName = $_POST['product_name'];
    $categoryID = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Move image
    move_uploaded_file($_FILES['image']['tmp_name'], "image/" . $image);

    // Insert product into database
    $sql = "INSERT INTO Products (ProductName, CategoryID, Price, Stock, Description, Image)
            VALUES ('$productName', '$categoryID', '$price', '$stock', '$description', '$image')";

    if (mysqli_query($conn, $sql)) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Edit product function
function editProduct($conn)
{
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $categoryID = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // If there's a new image, move it to the storage directory
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "image/" . $image);
        $imageQuery = ", Image='$image'";
    } else {
        $imageQuery = "";
    }

    // Update product information
    $sql = "UPDATE Products 
            SET ProductName='$productName', CategoryID='$categoryID', Price='$price', Stock='$stock', Description='$description' $imageQuery
            WHERE ProductID='$productID'";

    if (mysqli_query($conn, $sql)) {
        echo "Product updated!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete product function
function deleteProduct($conn, $productID)
{
    $sql = "DELETE FROM Products WHERE ProductID='$productID'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product deleted successfully!')</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Simple CSS for styling */
       
        form input,
        form textarea {
            width: 980px;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form select {
            width: 1000px;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f8f9fa;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1>Welcome to Our Store</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="productdetail.php">Product Detail</a></li>
                    <li><a href="cart.php">My Cart</a></li>
                    <li><a href="#productmanagement.php">Product Management</a></li>
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
    <div class="container">

        <!-- Add Product Form -->
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <select name="category_id">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM Categories");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['CategoryID']}'>{$row['CategoryName']}</option>";
                }
                ?>
            </select>
            <input type="number" name="price" placeholder="Product Price" required>
            <input type="number" name="stock" placeholder="Stock Quantity" required>
            <textarea name="description" placeholder="Product Description"></textarea>
            <input type="file" name="image" required>
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <!-- Edit Product Form (only shows when a product ID is set for editing) -->
        <?php
        if (isset($_GET['id'])) {
            $productID = $_GET['id'];
            $result = mysqli_query($conn, "SELECT * FROM Products WHERE ProductID='$productID'");
            $product = mysqli_fetch_assoc($result);
        ?>
            <h2>Edit Product</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                <input type="text" name="product_name" value="<?php echo $product['ProductName']; ?>" required>
                <select name="category_id">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM Categories");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['CategoryID']}' " . ($row['CategoryID'] == $product['CategoryID'] ? 'selected' : '') . ">{$row['CategoryName']}</option>";
                    }
                    ?>
                </select>
                <input type="number" name="price" value="<?php echo $product['Price']; ?>" required>
                <input type="number" name="stock" value="<?php echo $product['Stock']; ?>" required>
                <textarea name="description"><?php echo $product['Description']; ?></textarea>
                <input type="file" name="image">
                <button type="submit" name="edit_product">Update Product</button>
            </form>
        <?php } ?>

        <!-- Product List with delete functionality -->
        <h2>Product List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM Products");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['ProductID']}</td>
                    <td>{$row['ProductName']}</td>
                    <td>{$row['Price']}</td>
                    <td>{$row['Stock']}</td>
                    <td>
                        <a href='productmanagement.php?id={$row['ProductID']}'>Edit</a> | 
                        <a href='productmanagement.php?delete_id={$row['ProductID']}' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                    </td>
                  </tr>";
            }
            ?>
        </table>

    </div>
    <footer>
        <div class="container">
            <p>&copy; 2024 Our Store. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>
