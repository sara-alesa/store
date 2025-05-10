<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشروباتنا</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
<header class="bg-warning bg-gradient shadow-sm">
    <div class="container d-flex flex-wrap justify-content-between align-items-center py-3">
        <a href="index.php" class="d-flex align-items-center text-dark text-decoration-none fs-4 fw-bold">
            <img src="images/logo.png" alt="Logo" style="height: 40px; margin-right: 10px;">
            مشروباتنا
        </a>
<!-- العناوين -->
        <nav class="d-flex align-items-center">
        <a href="admin_pannel.php" class="nav-link custom-link">الرئيسية</a>
            <a href="admin_products.php" class="nav-link text-dark fw-semibold mr-3">المنتجات</a>
            <!-- <a href="admin_order.php" class="nav-link text-dark fw-semibold">الطلبات</a> -->
            <a href="admin_user.php" class="nav-link text-dark fw-semibold">المستخدمون</a>
            <!-- <a href="admin_message.php" class="nav-link text-dark fw-semibold">الرسائل</a> -->
        </nav>
        </nav>

        <style>
        nav a.nav-link {
            color: #4b6043;
            font-weight: bold;
            padding: 10px 15px;
            margin-left: 10px;
            border-radius: 12px;
            transition: 0.3s ease;
            display: inline-block;
        }
        nav a.nav-link:hover {
            background-color: #f0e5cf;
            color: #3c4d37;
            text-decoration: none;
        }
        </style>

        <!-- تسجيل الخروج -->
        <div class="d-flex align-items-center gap-3">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="text-dark fw-bold">
                    <i class="bi bi-person-circle"></i>
                    <?php echo $_SESSION['username']; ?>
                </span>
                <form method="post" class="m-0">
                    <button type="submit" name="logout" class="btn btn-danger btn-sm">تسجيل الخروج</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</header>

<footer class="bg-dark text-white py-4 text-center">
    جميع الحقوق محفوظة &copy; <?php echo date("Y"); ?> مشروباتنا
</footer>
</body>
</html>
