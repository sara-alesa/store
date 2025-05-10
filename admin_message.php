<?php
include 'connection.php';
session_start();

$admin_id = $_SESSION['admin_name'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// تسجيل الخروج
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit();
}

// حذف منتج من قائمة الرغبات (مثال)
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid='$delete_id'") or die('فشل الحذف من wishlist');
    header('location:admin_products.php');
    exit();
}

// حذف رسالة
if (isset($_GET['delete_message'])) {
    $message_id = $_GET['delete_message'];
    mysqli_query($conn, "DELETE FROM `message` WHERE id='$message_id'") or die('فشل حذف الرسالة');
    header('location:admin_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - المنتجات</title>
    <!-- <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<div class="container my-5">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-center text-success">إضافة منتج جديد</h3>

        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '<div class="alert alert-info">' . $msg . '</div>';
            }
        }
        ?>
    </div>
</div>

<hr class="my-5">

<section class="message-cintainer title container my-5">
    <h1 class="mb-4 text-center">الرسائل غير المقروءة</h1>

    <div class="box-container row">
        <?php 
        $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('فشل عرض الرسائل');
        if (mysqli_num_rows($select_message) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($select_message)) {
        ?>
        <div class="box col-md-6 mb-3">
            <div class="card p-3">
                <p><strong>User ID:</strong> <?php echo $fetch_message['id']; ?></p>
                <p><strong>الاسم:</strong> <?php echo $fetch_message['name']; ?></p>
                <p><strong>الإيميل:</strong> <?php echo $fetch_message['email']; ?></p>
                <p><strong>الرسالة:</strong> <?php echo $fetch_message['message']; ?></p>
                <a href="admin_products.php?delete_message=<?php echo $fetch_message['id']; ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('هل ترغب بحذف الرسالة؟');">حذف</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p class='text-center w-100'>لا توجد رسائل.</p>";
        }
        ?>
    </div>
</section>
</body>
</html>
