<?php
require_once __DIR__ . '/../../Services/ProductService.php';

$productList = [];

if (isset($_POST['show_products'])) {
    $productService = new ProductService();
    $productList = $productService->fetchProductList();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Trang chủ</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/css/styles.css">
</head>

<body>
    <h2>Click để xem sản phẩm</h2>

    <form method="post">
        <button type="submit" name="show_products">Xem danh sách túi xách</button>
    </form>

    <?php
  if (!empty($productList)) {
    echo "<h3>Danh sách sản phẩm:</h3>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>ID</th><th>Tên</th><th>Giá</th></tr>";

    foreach ($productList as $row) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . number_format($row['price'], 0, ',', '.') . " VNĐ</td>";
      echo "</tr>";
    }

    echo "</table>";
  } elseif (isset($_POST['show_products'])) {
    echo "<p>Không có sản phẩm nào.</p>";
  }
  ?>
</body>

</html>