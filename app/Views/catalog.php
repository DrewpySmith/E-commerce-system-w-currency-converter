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
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Currency Selector -->
                <li class="nav-item">
                     <form action="/" method="get" class="d-flex mt-1">
                        <select name="currency" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="USD" <?= $currency == 'USD' ? 'selected' : '' ?>>USD ($)</option>
                            <option value="PHP" <?= $currency == 'PHP' ? 'selected' : '' ?>>PHP (₱)</option>
                            <option value="EUR" <?= $currency == 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                        </select>
                    </form>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item me-3 text-white">
                        <i class="bi bi-person-circle"></i> Welcome, <strong><?= session()->get('username') ?></strong>
                    </li>
                    <li class="nav-item me-3">
                        <a href="/cart" class="btn btn-light btn-sm position-relative">
                            <i class="bi bi-cart-fill"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="btn btn-outline-light btn-sm">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="/login" class="nav-link text-white">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-light btn-sm ms-2">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <div class="row mb-4">
        <div class="col-12">
            <h3>Product Catalog</h3>
            <p class="text-muted">Current Exchange Rate: 1 USD = <?= $rate ?> <?= $currency ?></p>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span>Product IMG</span>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($product['name']) ?></h5>
                            <p class="card-text text-truncate"><?= esc($product['description']) ?></p>
                            
                            <h4 class="text-primary">
                                <?php 
                                    $localPrice = $product['price_usd'] * $rate;
                                    echo number_format($localPrice, 2) . ' ' . $currency;
                                ?>
                            </h4>
                            <small class="text-muted">Base: $<?= $product['price_usd'] ?></small>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0">
                            <form action="/cart/add" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-outline-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12"><p class="text-center">No products found.</p></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>