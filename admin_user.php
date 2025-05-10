<?php
include 'connection.php';
session_start();

// التأكد من تسجيل دخول الأدمن
$admin_id = $_SESSION['admin_name'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// تنفيذ الحذف إذا تم طلب ذلك
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']); // تأمين ID
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
    <h3 class="mb-4 text-center admin-title">حسابات المستخدمين</h3>

        <div class="row">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('فشل في جلب المستخدمين');

            if (mysqli_num_rows($select_users) > 0) {
                while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
            <div class="col-md-6 mb-4">
            <div class="user-card">
            <p><strong>ID المستخدم:</strong> <?php echo $fetch_users['id']; ?></p>
                    <p><strong>الاسم:</strong> <?php echo $fetch_users['name']; ?></p>
                    <p><strong>البريد الإلكتروني:</strong> <?php echo $fetch_users['email']; ?></p>
                    <p><strong>النوع:</strong> <?php echo $fetch_users['user_type']; ?></p>
                    <a href="admin_user.php?delete_user=<?php echo $fetch_users['id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('هل ترغب بحذف هذا المستخدم؟');">حذف المستخدم</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>لا توجد حسابات مستخدمين.</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<script src="bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>
</body>
</html>
