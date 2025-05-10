<?php
session_start();

// تحديث الكمية
if (isset($_POST['update_quantity'])) {
    $pid = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$pid])) {
        if ($action === 'plus') {
            $_SESSION['cart'][$pid]['quantity']++;
        } elseif ($action === 'minus') {
            $_SESSION['cart'][$pid]['quantity']--;
            // حذف المنتج إذا الكمية صارت صفر أو أقل
            if ($_SESSION['cart'][$pid]['quantity'] <= 0) {
                unset($_SESSION['cart'][$pid]);
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// حذف منتج
if (isset($_POST['remove_product'])) {
    $pid = $_POST['product_id'];
    unset($_SESSION['cart'][$pid]);
    header("Location: cart.php");
    exit;
}
?>

<h2 class="text-center my-4">سلة المشتريات</h2>

<div class="container">
  <?php if (!empty($_SESSION['cart'])): ?>
    <table class="table table-bordered text-center">
      <thead class="thead-light">
        <tr>
          <th>الصورة</th>
          <th>المنتج</th>
          <th>السعر</th>
          <th>الكمية</th>
          <th>الإجمالي</th>
          <th>إزالة</th>
        </tr>
      </thead>
      <tbody>
        <?php $total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $pid => $item): ?>
          <tr>
            <td><img src="image/<?php echo $item['image']; ?>" width="70"></td>
            <td><?php echo $item['name']; ?></td>
            <td>$<?php echo $item['price']; ?></td>
            <td>
              <form method="POST" class="d-inline">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <input type="hidden" name="action" value="minus">
                <button type="submit" name="update_quantity" class="btn btn-sm btn-secondary">-</button>
              </form>
              <?php echo $item['quantity']; ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <input type="hidden" name="action" value="plus">
                <button type="submit" name="update_quantity" class="btn btn-sm btn-secondary">+</button>
              </form>
            </td>
            <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <button type="submit" name="remove_product" class="btn btn-danger btn-sm">X</button>
              </form>
            </td>
          </tr>
          <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endforeach; ?>
        <tr class="table-info">
          <td colspan="4" class="text-right font-weight-bold">الإجمالي الكلي:</td>
          <td colspan="2">$<?php echo $total; ?></td>
        </tr>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info text-center">السلة فارغة حالياً</div>
  <?php endif; ?>
</div>
