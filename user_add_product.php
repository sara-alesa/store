<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['name']);
    $product_price = mysqli_real_escape_string($conn, $_POST['price']);
    $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'image/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$product_name'") or die('فشل التحقق');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message = 'اسم المنتج موجود مسبقًا';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO `products` (`name`, `price`, `product_detail`, `image`) 
        VALUES ('$product_name', '$product_price', '$product_detail', '$image')") or die('فشل الإضافة');

        if ($insert_product) {
            if ($image_size > 2000000) {
                $message = 'حجم الصورة كبير جدًا';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message = 'تمت إضافة المنتج بنجاح';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة منتج</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-center text-primary">إضافة منتج جديد</h3>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>السعر</label>
                <input type="text" name="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="detail" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>صورة المنتج</label>
                <input type="file" name="image" class="form-control-file" accept="image/*" required>
            </div>

            <button type="submit" name="add_product" class="btn btn-success btn-block">إضافة المنتج</button>
         <a href="index.php" class="btn btn-secondary btn-block mt-3">رجوع إلى الرئيسية</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
