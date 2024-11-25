<?php
session_start();
include('koneksi.php'); 

if (!isset($_SESSION['user_id'])) {
    echo "<script>document.location.href = 'login/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

$product_id = $_GET['product_id'];
$product_name = $_GET['name'];
$product_price = floatval(str_replace(['.', ','], '', $_GET['price']));
$product_image = $_GET['image'];

$random_number = rand(100000, 999999);

$payment_data = "https://example.com/payment?transaction_id=$random_number&product_id=$product_id&name=" . urlencode($product_name) . "&price=$product_price"; 
$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($payment_data) . "&size=200x200";

$query = "INSERT INTO riwayat_pesanan (user_id, product_id, product_name, product_price, qr_code_url, transaction_id) 
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("iissss", $user_id, $product_id, $product_name, $product_price, $qr_code_url, $random_number);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="css/pembayaran.css">
</head>
<body>
    <div class="container">
        <h2>Metode Pembayaran</h2>
        <div class="content">
            <img src="img/products/<?= htmlspecialchars($product_image) ?>" alt="<?= htmlspecialchars($product_name) ?>" id="img-produk">
            <div class="data">
                <div class="left-side">
                    <div class="item">
                        <div class="item-details">
                            <h3><?= htmlspecialchars($product_name) ?></h3>
                            <p>Harga: Rp <?= number_format($product_price, 2) ?></p>
                        </div>
                    </div>
            
                    <div class="total">
                        <p>Total: Rp <?= number_format($product_price, 2) ?></p>
                    </div>
                </div>
                <div class="right-side">
                    <div class="qr-code">
                        <img src="<?= htmlspecialchars($qr_code_url) ?>" alt="QR Code for Payment" id="qr-code">
                    </div>
                </div>
        
            </div>
            <div class="buttons">
                <div class="button" id="cnc-btn" onclick="cancelPayment()">Batal</div>
                <div class="button" id="buy-btn" onclick="processPayment()">Bayar</div>
            </div>
        </div>


    </div>

    <script>
        function cancelPayment() {
            alert("Pembayaran dibatalkan.");
            location.href = "index.php"; 
        }
        
        function processPayment() {
            alert("Pembayaran diproses.");
            location.href = "riwayat-pemesanan.php";
        }
    </script>
</body>
</html>
