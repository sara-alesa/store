<?php
// تضمين ملف الاتصال بقاعدة البيانات
include 'connection.php';
// بدء الجلسة للمستخدم
session_start();

// التأكد من تسجيل دخول المشرف
$admin_id = $_SESSION['admin_name'];
if (!isset($admin_id)) {
    header('location:login.php'); // إذا لم يكن المشرف مسجلاً دخول، يتم إعادة توجيههم إلى صفحة تسجيل الدخول
    exit();
}

// تسجيل الخروج
if (isset($_POST['logout'])) {
    session_destroy(); // تدمير الجلسة
    header('location:login.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
}

// إضافة منتج جديد
if (isset($_POST['add_product'])) {
    // جلب البيانات المدخلة من المستخدم
    $product_name = mysqli_real_escape_string($conn, $_POST['name']);
    $product_price = mysqli_real_escape_string($conn, $_POST['price']);
    $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);

    // التعامل مع صورة المنتج
    $image = $_FILES['image']['name']; 
    $image_tmp_name = $_FILES['image']['tmp_name']; 
    $image_size = $_FILES['image']['size']; 
    $image_folder = 'image/' . $image;

    // التحقق مما إذا كان اسم المنتج موجود بالفعل في قاعدة البيانات
    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$product_name'") or die('فشل التحقق');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'اسم المنتج موجود مسبقًا'; // رسالة إذا كان المنتج موجوداً مسبقًا
    } else {
        // إدخال المنتج الجديد في قاعدة البيانات
        $insert_product = mysqli_query($conn, "INSERT INTO `products` (`name`, `price`, `product_detail`, `image`) 
        VALUES ('$product_name', '$product_price', '$product_detail', '$image')") or die('فشل الإضافة');

        if ($insert_product) {
            if ($image_size > 2000000) {
                $message[] = 'حجم الصورة كبير جدًا'; // رسالة إذا كان حجم الصورة أكبر من 2 ميغابايت
            } else {
                move_uploaded_file($image_tmp_name, $image_folder); // نقل الصورة إلى المجلد المحدد
                $message[] = 'تمت إضافة المنتج بنجاح'; // رسالة تفيد بنجاح إضافة المنتج
            }
        }
    }
}

// حذف منتج
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete']; // جلب معرف المنتج الذي سيتم حذفه
    // حذف الصورة المرتبطة بالمنتج من المجلد
    $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id='$delete_id'") or die('فشل اختيار الصورة');
    $fetch_image = mysqli_fetch_assoc($select_delete_image);
    unlink('image/' . $fetch_image['image']); // حذف الصورة من الخادم

    // حذف المنتج من الطاولة الرئيسية وكذلك من الطاولات الأخرى مثل cart وwishlist
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid='$delete_id'") or die('فشل الحذف من cart');
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid='$delete_id'") or die('فشل الحذف من wishlist');
    mysqli_query($conn, "DELETE FROM `products` WHERE id='$delete_id'") or die('فشل الحذف من products');

    header('location:admin_products.php'); // إعادة التوجيه إلى صفحة المنتجات بعد الحذف
    exit();
}

// تحديث منتج
if (isset($_POST['update_product'])) {
    $update_id = $_POST['update_id']; // جلب معرف المنتج المراد تحديثه
    $update_name = mysqli_real_escape_string($conn, $_POST['name']);
    $update_price = mysqli_real_escape_string($conn, $_POST['price']);
    $update_detail = mysqli_real_escape_string($conn, $_POST['detail']);

    $update_query = "UPDATE `products` SET name='$update_name', price='$update_price', product_detail='$update_detail'";

    // إذا تم إدخال صورة جديدة
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = 'image/' . $image;

        // حذف الصورة القديمة من المجلد
        $get_old = mysqli_query($conn, "SELECT image FROM `products` WHERE id='$update_id'");
        $fetch_old = mysqli_fetch_assoc($get_old);
        unlink('image/' . $fetch_old['image']); // حذف الصورة القديمة

        move_uploaded_file($image_tmp_name, $image_folder); // نقل الصورة الجديدة إلى المجلد
        $update_query .= ", image='$image'"; // إضافة العمود الجديد للصورة في الاستعلام
    }

    // تنفيذ عملية التحديث في قاعدة البيانات
    $update_query .= " WHERE id='$update_id'";
    mysqli_query($conn, $update_query) or die('فشل التحديث');

    header('location:admin_products.php'); // إعادة التوجيه إلى صفحة المنتجات بعد التحديث
    exit();
}
?>

<!-- HTML للأجزاء الخاصة بإضافة وتحديث وعرض المنتجات -->

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - المنتجات</title>
    <!-- إضافة ملف CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- تضمين الهيدر للمشرف -->
<?php include 'admin_header.php'; ?>

<!-- قسم إضافة منتج جديد -->
<div class="container my-5">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-center text-success">إضافة منتج جديد</h3>
        <?php
        // عرض الرسائل (إن وجدت)
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '<div class="alert alert-info">' . $msg . '</div>';
            }
        }
        ?>
        <!-- نموذج إضافة المنتج -->
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
        </form>
    </div>
</div>

<!-- عرض جميع المنتجات -->
<section class="show-products my-5">
    <div class="container">
        <div class="row">
            <?php
            // جلب جميع المنتجات من قاعدة البيانات
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('فشل الاتصال');
            if (mysqli_num_rows($select_products) > 0) {
                while ($product = mysqli_fetch_assoc($select_products)) {
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="image/<?php echo $product['image']; ?>" class="card-img-top" height="200">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text"><?php echo $product['product_detail']; ?></p>
                            <p class="text-success">السعر: $<?php echo $product['price']; ?></p>
                            <a href="admin_products.php?edit=<?php echo $product['id']; ?>" class="btn btn-warning">تعديل</a>
                            <a href="admin_products.php?delete=<?php echo $product['id']; ?>" onclick="return confirm('هل أنت متأكد؟')" class="btn btn-danger">حذف</a>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>لا توجد منتجات.</p>";
            }
            ?>
        </div>
    </div>
</section>

<!-- عرض نموذج تعديل المنتج إذا كان موجوداً -->
<?php
// نموذج التعديل إذا كان edit موجود في GET
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id='$edit_id'") or die('فشل التعديل');
    if (mysqli_num_rows($edit_query) > 0) {
        $fetch_edit = mysqli_fetch_assoc($edit_query);
?>
    <section class="container my-5">
        <div class="card shadow p-4">
            <h4 class="text-center text-primary">تعديل المنتج</h4>
            <form method="POST" enctype="multipart/form-data">
                <img src="image/<?php echo $fetch_edit['image']; ?>" alt="" width="150" class="mb-3">
                <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="<?php echo $fetch_edit['name']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>السعر</label>
                    <input type="number" name="price" value="<?php echo $fetch_edit['price']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea name="detail" class="form-control" rows="4"><?php echo $fetch_edit['product_detail']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>تغيير الصورة</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                </div>
                <button type="submit" name="update_product" class="btn btn-primary">تحديث</button>
                <a href="admin_products.php" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </section>
<?php
    }
}
?>

</body>
</html>
