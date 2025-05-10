<?php
include 'connection.php';
session_start();

// التأكد من تسجيل دخول الأدمن
$admin_id = $_SESSION['admin_name'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// تنفيذ حذف المستخدم إذا تم طلب ذلك
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']); 
    $delete_query = mysqli_query($conn, "DELETE FROM `users` WHERE id = $user_id") or die('فشل حذف المستخدم');
    header('Location: admin_user.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - المستخدمين</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<div class="container my-5">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-center text-primary">حسابات المستخدمين</h3>

        <div class="row">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('فشل في جلب الطلبات');

            if (mysqli_num_rows($select_orders) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
            ?>
            <div class="col-md-6 mb-4">
                <div class="card border rounded shadow-sm p-3">
                    <p><strong> المستخدم:</strong> <?php echo $fetch_orders['name']; ?></p>
                    <p><strong>id:</strong> <?php echo $fetch_orders['user_id']; ?></p>
                    <p><strong>placed_on :</strong> <?php echo $fetch_orders['placed_on']; ?></p>
                    <p><strong>الرقم:</strong> <?php echo $fetch_orders['number']; ?></p>
                    <p><strong>الايميل:</strong> <?php echo $fetch_orders['email']; ?></p>
                    <p><strong>السعر الاجمالي:</strong> <?php echo $fetch_orders['total_price']; ?></p>
                    <p><strong>العنوان:</strong> <?php echo $fetch_orders['addres']; ?></p>
                    <p><strong>جميع المنتجات:</strong> <?php echo $fetch_orders['total_price']; ?></p>
                    <form method="post">
                        <input type='hidden' name='order_id' value="<?php echo $fetch_orders['id']; ?>">
                        <select name="update_payment">
                        <option></option>
                        <option></option>
                        </select>
                    </form>
                    <a href="admin_order.php?delete_user=<?php echo $fetch_orders['order']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('هل ترغب بحذف هذا المستخدم؟');">حذف المستخدم</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>لا توجد طلبات .</p></div>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
