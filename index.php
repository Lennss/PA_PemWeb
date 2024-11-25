<?php
session_start();
require 'koneksi.php';

$products = mysqli_query($conn, "SELECT * FROM products");

// Tentukan jumlah produk per halaman
$products_per_page = 4;

// Tentukan halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;

// Hitung offset
$offset = ($page - 1) * $products_per_page;

// Query produk dengan paginasi
$products = mysqli_query($conn, "SELECT * FROM products LIMIT $offset, $products_per_page");

// Hitung total produk
$total_products_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$total_products = mysqli_fetch_assoc($total_products_result)['total'];

// Hitung total halaman
$total_pages = ceil($total_products / $products_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kopi Kenangan Senja</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet">

  <script src="https://unpkg.com/feather-icons"></script>

  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <!-- Navbar start -->
  <nav class="navbar">
    <a href="#" class="navbar-logo">kenangan<span>senja</span>.</a>

    <div class="navbar-nav">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#products">Produk</a>
      <a href="#contact">Kontak</a>
      <a href="riwayat-pemesanan.php">Riwayat Pemesanan</a>
    </div>

    <div class="navbar-extra">
      <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
          <a href="login/logout.php"><i data-feather="user"></i></a>
      <?php else: ?>
          <a href="login/login.php"><i data-feather="user-x"></i></a>
      <?php endif; ?>

      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>
  </nav>
  <!-- Navbar end -->

  <!-- Hero Section start -->
  <section class="hero" id="home">
    <div class="mask-container">
      <main class="content">
        <h1>Mari Nikmati Secangkir <span>Kopi</span></h1>
        <p>Rasakan Kenikmatan Sejati dalam Setiap Tegukan Kopi Kami</p>
      </main>
    </div>
  </section>
  <!-- Hero Section end -->

  <!-- About Section start -->
  <section id="about" class="about">
    <h2><span>Tentang</span> Kami</h2>

    <div class="row">
      <div class="about-img">
        <img src="img/tentang-kami.png" alt="Tentang Kami">
      </div>
      <div class="content">
        <h3>Kenapa memilih kopi kami?</h3>
        <p>Kopi kami dipilih dari biji kopi terbaik yang diproses dengan hati-hati untuk memastikan rasa yang sempurna di setiap cangkir</p>
        <p>Setiap tegukan kopi kami menawarkan aroma yang kaya dan rasa yang unik, memberikan pengalaman minum kopi yang tak terlupakan</p>
      </div>
    </div>
  </section>
  <!-- About Section end -->

  <!-- Products Section start -->
  <section class="products" id="products">
    <h2><span>Produk</span> Kami</h2>

    <?php while ($product = mysqli_fetch_assoc($products)): ?>
      <div class="row">
      <div class="product-card">
        <div class="product-icons">
          <a href="pembayaran.php?product_id=<?= $product['id'] ?>&name=<?= urlencode($product['name']) ?>&price=<?= urlencode($product['price']) ?>&image=<?= urlencode($product['image']) ?>" class="item-detail-button">
            <i data-feather="shopping-cart"></i>
          </a>
          <!-- Add onclick event to show product details -->
          <a href="#" class="item-detail-button" onclick="showProductDetails('<?= $product['name'] ?>', '<?= $product['price'] ?>', '<?= $product['description'] ?>', 'img/products/<?= $product['image'] ?>')">
            <i data-feather="eye"></i>
          </a>
        </div>
        <div class="product-image">
          <img src="img/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        </div>
        <div class="product-content">
          <h3><?= $product['name'] ?></h3>
          <div class="product-price"><?= $product['price'] ?></div>
        </div>
      </div>
      </div>
    <?php endwhile; ?>

 <!-- Paginasi -->
<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=<?= $page - 1 ?>" class="prev">Sebelumnya</a>
  <?php endif; ?>

  <?php
  // Tentukan batas maksimal halaman yang akan ditampilkan
  $max_links = 4;

  // Tentukan halaman awal dan akhir yang akan ditampilkan
  $start_page = max(1, $page - 2); // Dua halaman sebelumnya
  $end_page = min($total_pages, $page + 2); // Dua halaman berikutnya

  // Jika jumlah total halaman lebih banyak dari batas maksimal, pastikan tidak melampaui
  if ($total_pages > $max_links) {
    if ($start_page < 1) {
      $start_page = 1;
      $end_page = min($max_links, $total_pages);
    }
    if ($end_page > $total_pages) {
      $end_page = $total_pages;
      $start_page = max(1, $total_pages - $max_links + 1);
    }
  }

  // Menampilkan link halaman
  for ($i = $start_page; $i <= $end_page; $i++):
  ?>
    <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>

  <?php if ($page < $total_pages): ?>
    <a href="?page=<?= $page + 1 ?>" class="next">Seterusnya</a>
  <?php endif; ?>
</div>

  </section>
  <!-- Products Section end -->

<!-- Popup -->
<script src="product-details.js"></script>
<div id="product-popup" class="product-popup">
  <div class="popup-content">
    <span class="close-popup" onclick="hideProductDetails()">&times;</span>
    <div class="image">
      <img id="popup-image" src="" alt="Product Image" class="popup-image">
    </div>
    <div class="desc">
      <h3 id="popup-name"></h3>
      <div id="popup-price" class="popup-price"></div>
      <p id="popup-description" class="popup-description"></p>
    </div>
  </div>
</div>



  <!-- Contact Section start -->
  <section id="contact" class="contact">
    <h2><span>Kontak</span> Kami</h2>

<!-- INI PETA SAMARINDA -->
    <div class="row">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5328.641157632895!2d117.11787776094653!3d-0.5009898076205274!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67f9e3a5b4857%3A0xd9d9678dade6eae3!2sSamarinda%2C%20Samarinda%20City%2C%20East%20Kalimantan!5e0!3m2!1sen!2sid!4v1731240935290!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      <div class="text">
          <h3 class="judul">Kenangan <span> Senja</span></h3>
          <h3>Jl. Kenangan Senja No. 123, Samarinda</h3>
          <h3>0812-3456-7890</h3>
          <h3>Kenangan@gmail.com</h3>
      </div>
    </div>
  </section>
  <!-- Contact Section end -->

  <!-- Footer start -->
  <footer>
    <div class="socials">
      <a href="#"><i data-feather="instagram"></i></a>
      <a href="#"><i data-feather="twitter"></i></a>
      <a href="#"><i data-feather="facebook"></i></a>
    </div>

 
    <div class="links">
      <!-- NO CSS OUTER FILE -->
      <a href="#home" style= "font-size: 1.3rem;">Home</a>
      <a href="#about"  style= "font-size: 1.3rem;">Tentang Kami</a>
      <a href="#menu"  style= "font-size: 1.3rem;">Menu</a>
      <a href="#contact"  style= "font-size: 1.3rem;">Kontak</a>
      <a href="riwayat-pemesanan.php"  style= "font-size: 1.3rem;">Riwayat Pemesanan</a>
    </div>

    <div class="credit">
      <p>Created by <a href="">Kenangan Coffee</a>. | &copy; 2024.</p>
    </div>
  </footer>
  <!-- Footer end -->

  <!-- Feather Icons Script -->
  <script>
      feather.replace();
  </script>

  <!-- JavaScript -->
  <script src="js/script.js"></script>

</body>

</html>
