<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<?php
include 'connection.php';

// حساب عدد المنتجات في السلة
$total_quantity = 0;
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_quantity += $item['quantity'];
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>منتجاتنا</title>
  <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (isset($_SESSION['user_name'])): ?>
      <span class="mr-3">مرحباً، <?php echo $_SESSION['user_name']; ?></span>
      <form method="POST" style="display:inline;">
        <button type="submit" name="logout" class="btn btn-outline-danger btn-sm">تسجيل الخروج</button>
      </form>
    <?php endif; ?>
  </div>
<nav class="navbar navbar-light bg-light justify-content-between px-4">
  <div class="d-flex align-items-center">

    <img src="images/logo.png" alt="الشعار" style="height: 40px; margin-right: 10px;">
    <h4 class="text-success">معرض المنتجات</h4>
  </div>
  <span class="mr-3">
  🛒 <a href="cart.php" class="text-dark font-weight-bold">سلة</a>
  <?php
    $count = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    echo " ($count)";
  ?>
</span>

</nav>
<div class="container mt-4">
</div>
</body>
</html>
