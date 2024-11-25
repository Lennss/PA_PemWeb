<?php
session_start();
require '../koneksi.php';

// Handle user deletion
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $query = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $query);
    header("Location: user-admin.php");
    exit();
}

// Search
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Pagination logic
$users_per_page = 5; // Number of users per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$page = $page < 1 ? 1 : $page; // Ensure page is at least 1
$offset = ($page - 1) * $users_per_page; // Offset for SQL query

// Query for users with search and pagination
if (!empty($search)) {
    $query = "SELECT * FROM users WHERE username LIKE '%$search%' LIMIT $users_per_page OFFSET $offset";
    $count_query = "SELECT COUNT(*) AS total_users FROM users WHERE username LIKE '%$search%'";
} else {
    $query = "SELECT * FROM users LIMIT $users_per_page OFFSET $offset";
    $count_query = "SELECT COUNT(*) AS total_users FROM users";
}

$user_result = mysqli_query($conn, $query);
$count_result = mysqli_query($conn, $count_query);
$total_users = mysqli_fetch_assoc($count_result)['total_users'];
$total_pages = ceil($total_users / $users_per_page); // Calculate total pages
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Pengguna</title>
    <link rel="stylesheet" href="style/user.css">
</head>
<body>
    <!-- Navbar start -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">kenangan<span>senja</span>.</a>

        <div class="navbar-nav">
            <a href="produk-admin.php">Produk</a>
            <a href="pesanan-admin.php">Pesanan</a>
            <a href="#user-admin">User</a>
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

    <!-- Search Form -->
    <form action="user-admin.php" method="get" style="margin-bottom: 1rem;" class="search">
        <input type="text" id="search-input" name="search" placeholder="Cari berdasarkan username" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" id="search-btn">Cari</button>
    </form>

    <!-- User Management Section -->
    <div class="container">
        <h2 id="users">Akun Pengguna</h2>

        <!-- User Table -->
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nomor Handphone</th>
                <th>Aksi</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($user_result)): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['nomor_hp']) ?></td>
                <td>
                    <a href="user-admin.php?delete_user_id=<?= $user['id'] ?>" onclick="return confirm('Apakah kamu yakin?')"><button id="btn-hapus">Hapus</button></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?= htmlspecialchars($search) ?>&page=<?= $page - 1 ?>" class="prev">Sebelumnya</a>
            <?php endif; ?>

            <?php
            // Display a range of 3 pagination links around the current page
            $start = max(1, $page - 1);  // Ensure at least 1 is shown
            $end = min($total_pages, $page + 1);  // Ensure we don't exceed the total pages

            // Display the pages
            for ($i = $start; $i <= $end; $i++) {
                echo "<a href='?search=" . htmlspecialchars($search) . "&page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
            }
            ?>

            <?php if ($page < $total_pages): ?>
                <a href="?search=<?= htmlspecialchars($search) ?>&page=<?= $page + 1 ?>" class="next">Selanjutnya</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>feather.replace()</script>
</body>
</html>
