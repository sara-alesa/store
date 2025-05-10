<?php
session_start();
include 'header.php';
include 'connection.php'; // الاتصال بقاعدة البيانات

// تسجيل الخروج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// إضافة منتج للسلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $pid = $_POST['product_id'];
    $pname = $_POST['product_name'];
    $pprice = $_POST['product_price'];
    $pimage = $_POST['product_image'];

    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity']++;
    } else {
        $_SESSION['cart'][$pid] = [
            'name' => $pname,
            'price' => $pprice,
            'image' => $pimage,
            'quantity' => 1
        ];
    }
    header('Location: index.php');
    exit;
}
?>

<header>
  <div class="d-flex align-items-center">
    <!-- <?php if (isset($_SESSION['user_name'])): ?>
      <span class="mr-3">مرحبًا، <?php echo $_SESSION['user_name']; ?></span> -->
      <!-- زر تسجيل الخروج -->
      <!-- <form method="POST" action="" class="ml-3" id="logoutForm">
        <button type="submit" name="logout" class="btn btn-outline-danger btn-sm">تسجيل الخروج</button>
      </form>
    <?php endif; ?> -->
  </div>
</header>

<!-- قسم ترحيبي -->
<section class="text-center p-5 bg-light">
    <h1 class="display-4"> <strong>أكثر</strong> من مجرد رشفة...</h1>
    <p class="lead">اكتشف عالم النكهات →</p>
</section>

<!-- قسم من نحن -->
<section class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="images/tea.jpeg" alt="Tea" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h2>من نحن</h2>
            <p>نحن علامة متخصصة في تقديم أفضل المشروبات الساخنة والباردة، نصنع كل كوب بعناية فائقة ليمنحك تجربة فريدة من نوعها. الجودة، الطعم، والراحة هي أولوياتنا.</p>
        </div>
    </div>
</section>

<!-- قسم المنتجات الأكثر مبيعاً -->
<div class="container text-center">
    <h2 class="mb-4">الأكثر طلباً</h2>
    <div class="row">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('فشل الاتصال');
        if (mysqli_num_rows($select_products) > 0):
            while ($product = mysqli_fetch_assoc($select_products)):
        ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100">
                <img src="image/<?php echo $product['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                    <p class="card-text"><?php echo $product['product_detail']; ?></p>
                    <p class="text-success font-weight-bold">السعر: $<?php echo $product['price']; ?></p>
                    <!-- زر إضافة المنتج إلى السلة -->
                    <form method="POST" action="" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm">أضف إلى السلة</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
            echo '<p class="text-center w-100">لا توجد منتجات متاحة حالياً.</p>';
        endif;
        ?>
    </div>
</div>

<?php if (isset($_SESSION['user_name']) && $_SESSION['user_type'] === 'user'): ?>
  <a href="user_add_product.php" class="btn btn-primary">+ أضف منتجك</a>
<?php endif; ?>

<!-- قسم الطقوس -->
<section class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="images/ceremony.jpeg" alt="Ceremony" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h2>طقوسنا</h2>
            <p>كل مشروب لدينا يأتي مع طقوس خاصة من التقديم والمتعة. من لحظة التحضير وحتى آخر رشفة، نعدك برحلة مليئة بالنكهات والدفء.</p>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
  // التعامل مع عملية تسجيل الخروج بشكل منفصل
  document.getElementById("logoutForm").addEventListener("submit", function(event) {
    event.preventDefault(); // منع إرسال النموذج بشكل غير مقصود
    this.submit(); // سيتم إرسال النموذج بعد أن نمنع الإرسال
  });
</script>
