<?php
// استدعاء ملف الاتصال بقاعدة البيانات
include 'connection.php';

// بدء الجلسة لتخزين بيانات المستخدم
session_start();

// التحقق إذا تم إرسال نموذج الدخول
if (isset($_POST['submit-btn'])) {
    // تنظيف البيانات المدخلة (البريد الإلكتروني وكلمة المرور) لتجنب هجمات SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // استعلام قاعدة البيانات للتحقق من وجود المستخدم بناءً على البريد الإلكتروني
    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die("فشل الاستعلام");

    // التحقق إذا كان هناك مستخدم بنفس البريد الإلكتروني
    if (mysqli_num_rows($select_user) > 0) {
        // جلب بيانات المستخدم من قاعدة البيانات
        $row = mysqli_fetch_assoc($select_user);

        // التحقق من تطابق كلمة المرور المدخلة مع كلمة المرور المخزنة في قاعدة البيانات
        if ($password == $row['password']) {
            // إذا كان المستخدم من نوع "admin" (مدير النظام)
            if ($row['user_type'] == 'admin') {
                // تخزين بيانات الجلسة الخاصة بالمدير
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                // إعادة التوجيه إلى لوحة التحكم الخاصة بالمدير
                header('location:admin_pannel.php');
                exit; 

            } 
            // إذا كان المستخدم من نوع "user" (مستخدم عادي)
            elseif ($row['user_type'] == 'user') {
                // تخزين بيانات الجلسة الخاصة بالمستخدم العادي
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_type'] = $row['user_type'];
                // إعادة التوجيه إلى الصفحة الرئيسية للمستخدم
                header('location:index.php');
                exit; // مغادرة السكربت بعد التوجيه
            }

        } else {
            // إذا كانت كلمة المرور غير صحيحة، عرض رسالة خطأ
            $message[] = 'كلمة المرور غير صحيحة.';
        }
    } else {
        // إذا لم يتم العثور على المستخدم، عرض رسالة خطأ
        $message[] = 'المستخدم غير موجود.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"> <!-- رابط مكتبة Boxicons للأيقونات -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">  <!-- رابط ملف الـ CSS للتصميم -->
</head>
<body>
    <!-- عرض الاخطاء إذا كانت موجودة -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . $msg . '</span>  <!-- عرض الرسالة -->
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>  <!-- أيقونة لإغلاق الرسالة -->
            </div>';
        }
    }
    ?>
    <!-- قسم نموذج تسجيل الدخول -->
    <section class="form-container">
        <form method="post" action="">
            <h1>تسجيل الدخول</h1>  <!-- عنوان نموذج تسجيل الدخول -->
            <div class="input-field">
                <label>البريد الإلكتروني</label><br>
                <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>  
                <label>كلمة المرور</label><br>
                <input type="password" name="password" placeholder="أدخل كلمة المرور" required>  
            </div>
            <input type="submit" name="submit-btn" value="دخول الآن" class="btn">  
            <p>ليس لديك حساب؟ <a href="register.php">سجّل هنا</a></p>  <!-- رابط إلى صفحة التسجيل للمستخدمين الجدد -->
        </form>
    </section>
</body>
</html>
