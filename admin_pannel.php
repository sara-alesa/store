<?php
// استدعاء ملف الاتصال بقاعدة البيانات
include 'connection.php';

// بدء الجلسة للتحقق من حالة تسجيل الدخول
session_start();

// التحقق إذا كان المستخدم ليس مشرفًا (admin)، إذا كان غير مسجل، سيتم تحويله إلى صفحة تسجيل الدخول
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}

// إذا تم الضغط على زر "تسجيل الخروج"، يتم تدمير الجلسة وتحويل المستخدم إلى صفحة تسجيل الدخول
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
}

// استعلام للحصول على إجمالي قيمة الطلبات المكتملة
$total_completes = 0;
$select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status='completes'") or die('query failed');
while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
    $total_completes += $fetch_completes['total_price']; // جمع إجمالي الأسعار
}

// استعلام للحصول على عدد الطلبات
$select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
$num_of_orders = mysqli_num_rows($select_orders); // عدد الطلبات

// استعلام للحصول على عدد المشروبات
$select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
$num_of_products = mysqli_num_rows($select_products); // عدد المنتجات

// استعلام للحصول على عدد المستخدمين العاديين
$select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type='user'") or die('query failed');
$num_of_users = mysqli_num_rows($select_users); // عدد المستخدمين العاديين

// استعلام للحصول على عدد المشرفين
$select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type='admin'") or die('query failed');
$num_of_admins = mysqli_num_rows($select_admins); // عدد المشرفين

// استعلام للحصول على عدد جميع المستخدمين (مشرفين وغير مشرفين)
$select_all_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
$num_of_all_users = mysqli_num_rows($select_all_users); // إجمالي عدد المستخدمين

// استعلام للحصول على عدد الرسائل الجديدة
$select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
$num_of_messages = mysqli_num_rows($select_messages); // عدد الرسائل الجديدة
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>لوحة تحكم المشروبات</title>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

  <!-- ربط ملف الستايل (CSS) -->
  <link rel="stylesheet" href="style.css"> 
</head>
<body>

<?php include 'admin_header.php'; ?> <!-- استدعاء الترويسة (الهيدر) الخاصة باللوحة -->

<div class="container py-5">
  <div class="text-center mb-5">
    <!-- عنوان الصفحة مع وصف صغير -->
    <h1 class="dashboard-header">لوحة تحكم المشروبات</h1>
    <p class="text-muted">تابع الإحصائيات والمبيعات بكل سهولة</p>
  </div>

  <div class="row">
    <!-- البطاقات التي تحتوي على الإحصائيات -->
    
    <!-- بطاقة إجمالي الطلبات المكتملة -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-dollar-circle text-success'></i> <!-- أيقونة الدائرة المالية -->
          <h3 class="text-success">$<?php echo $total_completes; ?></h3> <!-- عرض إجمالي المبلغ للطلبات المكتملة -->
          <p>إجمالي الطلبات المكتملة</p>
        </div>
      </div>
    </div>

    <!-- بطاقة عدد الطلبات -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-cart text-warning'></i> <!-- أيقونة عربة التسوق -->
          <h3 class="text-warning"><?php echo $num_of_orders; ?></h3> <!-- عرض عدد الطلبات -->
          <p>عدد الطلبات</p>
        </div>
      </div>
    </div>

    <!-- بطاقة عدد المشروبات -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-cube text-primary'></i> <!-- أيقونة المكعب -->
          <h3 class="text-primary"><?php echo $num_of_products; ?></h3> <!-- عرض عدد المشروبات -->
          <p>عدد المشروبات</p>
        </div>
      </div>
    </div>

    <!-- بطاقة عدد المستخدمين -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-user text-info'></i> <!-- أيقونة المستخدم -->
          <h3 class="text-info"><?php echo $num_of_users; ?></h3> <!-- عرض عدد المستخدمين -->
          <p>عدد المستخدمين</p>
        </div>
      </div>
    </div>

    <!-- بطاقة عدد المشرفين -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-user-circle text-dark'></i> <!-- أيقونة المستخدم المحاط بالدائرة -->
          <h3 class="text-dark"><?php echo $num_of_admins; ?></h3> <!-- عرض عدد المشرفين -->
          <p>عدد المشرفين</p>
        </div>
      </div>
    </div>

    <!-- بطاقة إجمالي المستخدمين (مشرفين وغير مشرفين) -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-group text-secondary'></i> <!-- أيقونة المجموعة -->
          <h3 class="text-secondary"><?php echo $num_of_all_users; ?></h3> <!-- عرض إجمالي عدد المستخدمين -->
          <p>إجمالي المستخدمين</p>
        </div>
      </div>
    </div>

    <!-- بطاقة عدد الرسائل الجديدة -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class='bx bx-envelope text-danger'></i> <!-- أيقونة الرسائل -->
          <h3 class="text-danger"><?php echo $num_of_messages; ?></h3> <!-- عرض عدد الرسائل الجديدة -->
          <p>الرسائل الجديدة</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>
</body>
</html>
