<?php
session_start();
require '../koneksi.php';

// Function to handle image upload
function upload_image($image) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($image['type'], $allowed_types)) {
        if ($image['size'] < 5000000) { // 5MB max size
            $target = '../img/products/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $target);
            return basename($image['name']);
        } else {
            echo "File size exceeds the limit (5MB).";
            return false;
        }
    } else {
        echo "Invalid file type. Only JPEG, PNG, and GIF allowed.";
        return false;
    }
}

// Add product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $_FILES['image'];

    $uploaded_image = upload_image($image);
    if ($uploaded_image) {
        $query = "INSERT INTO products (name, price, description, image) VALUES ('$name', '$price', '$description', '$uploaded_image')";
        mysqli_query($conn, $query);
    }
}

// Delete product
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $query = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: produk-admin.php");
}

// Edit product
if (isset($_GET['edit_id'])) {
    $id = (int)$_GET['edit_id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($result);
}

// Update product
if (isset($_POST['update_product'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $_FILES['image']['name'] ? $_FILES['image'] : null;
    $old_image = $_POST['old_image'];
    $uploaded_image = $image ? upload_image($image) : $old_image;

    if ($uploaded_image) {
        $query = "UPDATE products SET name='$name', price='$price', description='$description', image='$uploaded_image' WHERE id=$id";
        mysqli_query($conn, $query);
        header("Location: produk-admin.php");
    }
}

// Pagination setup
$products_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

$query = "SELECT * FROM products LIMIT $products_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

// Total products
$total_query = "SELECT COUNT(*) FROM products";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_array($total_result);
$total_products = $total_row[0];
$total_pages = ceil($total_products / $products_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Products</title>
  <link rel="stylesheet" href="style/produk.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <!-- Navbar start -->
  <nav class="navbar">
    <a href="#" class="navbar-logo">kenangan<span>senja</span>.</a>
    <div class="navbar-nav">
      <a href="produk-admin.php">Produk</a>
      <a href="pesanan-admin.php">Pesanan</a>
      <a href="user-admin.php">User</a>
    </div>
    <div class="navbar-extra">
      <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
          <a href="../login/logout.php"><i data-feather="user"></i></a>
      <?php else: ?>
          <a href="../login/login.php"><i data-feather="user-x"></i></a>
      <?php endif; ?>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>
  </nav>

  <!-- Add/Edit product form -->
  <div class="content">
    <div class="tambah-produk">
        <h1>Manajemen Produk</h1>
        <h2>Tambah Produk</h2>
        <form action="produk-admin.php" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Produk" required><br>
            <input type="text" name="price" placeholder="Harga Produk" required><br>
            <textarea name="description" placeholder="Deskripsi Produk" required></textarea><br>
            <input type="file" name="image" accept="image/*" required><br>
            <button type="submit" name="add_product">Tambah</button>
        </form>
    </div>

    <div class="edit-produk">
        <?php if (isset($product)): ?>
        <style>
            .tambah-produk { display: none; }
        </style>
        <h2>Ubah Produk</h2>
        <form action="produk-admin.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
            <input type="text" name="price" value="<?= $product['price'] ?>" required><br>
            <textarea name="description" required><?= $product['description'] ?></textarea><br>
            <input type="file" name="image"><br>
            <input type="hidden" name="old_image" value="<?= $product['image'] ?>">
            <button type="submit" name="update_product">Ubah</button>
        </form>
        <?php endif; ?>
    </div>
  
    <h2>Produk</h2>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $product['name'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['description'] ?></td>
            <td><img src="../img/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="100"></td>
            <td>
                <a href="produk-admin.php?edit_id=<?= $product['id'] ?>"><button id="btn-ubah">Ubah</button></a>
                <a href="produk-admin.php?delete_id=<?= $product['id'] ?>" onclick="return confirm('Apakah kamu yakin?')"><button id="btn-hapus">Hapus</button></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="prev">Sebelumnya</a>
    <?php endif; ?>

    <?php
    // Display a range of 3 pagination links around the current page
    $start = max(1, $page - 1);  // Ensure at least 1 is shown
    $end = min($total_pages, $page + 1);  // Ensure we don't exceed the total pages

    // Display the pages
    for ($i = $start; $i <= $end; $i++) {
        echo "<a href='?page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
    }
    ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>" class="next">Selanjutnya</a>
    <?php endif; ?>
</div>

  </div>

  <script>
    feather.replace();
  </script>
</body>
</html>
