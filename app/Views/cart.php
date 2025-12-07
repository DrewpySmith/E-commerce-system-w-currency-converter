<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">GlobalShop</a>
        
        <!-- Currency Selector in Cart -->
        <div class="d-flex align-items-center">
            <form action="/cart" method="get" class="d-flex me-3">
                <select name="currency" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="USD" <?= $currency == 'USD' ? 'selected' : '' ?>>USD ($)</option>
                    <option value="PHP" <?= $currency == 'PHP' ? 'selected' : '' ?>>PHP (₱)</option>
                    <option value="EUR" <?= $currency == 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                    <option value="JPY" <?= $currency == 'JPY' ? 'selected' : '' ?>>JPY (¥)</option>
                </select>
            </form>
            <a href="/" class="btn btn-light btn-sm">Continue Shopping</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Shopping Cart</h2>
        <span class="badge bg-secondary">Rate: 1 USD = <?= $rate ?> <?= $currency ?></span>
    </div>

    <?php if (!empty($cartItems)): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price (<?= $currency ?>)</th>
                            <th>Quantity</th>
                            <th>Total (<?= $currency ?>)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 0.8rem;">IMG</div>
                                    <div><?= esc($item['name']) ?></div>
                                </div>
                            </td>
                            <td>
                                <?= number_format($item['price_usd'] * $rate, 2) ?>
                            </td>
                            <td><?= $item['quantity'] ?></td>
                            <td class="fw-bold">
                                <?= number_format(($item['price_usd'] * $item['quantity']) * $rate, 2) ?>
                            </td>
                            <td>
                                <a href="/cart/remove/<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                            <td class="fw-bold fs-4 text-primary">
                                <?= number_format($totalUSD * $rate, 2) ?> <?= $currency ?>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="<?= site_url('checkout') ?>" class="btn btn-success btn-lg">
                    Checkout (<?= number_format($totalUSD * $rate, 2) ?> <?= $currency ?>)
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-5">
            <h4>Your cart is empty</h4>
            <p>Looks like you haven't added anything yet.</p>
            <a href="/" class="btn btn-primary mt-2">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
