<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="/">GlobalShop</a>
  </div>
</nav>
<div class="container">
  <h2 class="mb-3">Checkout</h2>
  <?php if (!empty($items)): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table align-middle">
        <thead><tr><th>Product</th><th>Price (USD)</th><th>Qty</th><th>Total</th></tr></thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= esc($it['name']) ?></td>
              <td>$<?= number_format((float)$it['price_usd'],2) ?></td>
              <td><?= (int)$it['quantity'] ?></td>
              <td>$<?= number_format((float)$it['price_usd'] * (int)$it['quantity'],2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr class="table-active">
            <td colspan="3" class="text-end fw-bold">Grand Total:</td>
            <td class="fw-bold">$<?= number_format((float)$totalUSD,2) ?></td>
          </tr>
        </tfoot>
      </table>
      <form action="/checkout/place" method="post" class="text-end">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-success btn-lg">Place Order</button>
      </form>
    </div>
  </div>
  <?php else: ?>
    <div class="alert alert-info">No items to checkout.</div>
  <?php endif; ?>
</div>
</body>
</html>
