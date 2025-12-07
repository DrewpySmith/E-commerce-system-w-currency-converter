<?php /* Basic admin layout with print support */ ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= esc($title ?? 'Admin') ?></title>
  <link rel="stylesheet" href="<?= base_url('css/print.css') ?>" media="print" />
  <style>
    body{font-family:Arial,Helvetica,sans-serif;margin:0;padding:0;background:#f8fafc;color:#111827}
    header{background:#111827;color:#fff;padding:12px 16px;display:flex;justify-content:space-between;align-items:center}
    nav a{color:#93c5fd;margin-right:12px;text-decoration:none}
    .container{max-width:1100px;margin:20px auto;padding:0 16px}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin-bottom:16px}
    .btn{display:inline-block;background:#2563eb;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none}
    .btn.secondary{background:#6b7280}
    .btn.danger{background:#dc2626}
    table{width:100%;border-collapse:collapse}
    th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .actions a{margin-right:8px}
    @media print{
      header, .no-print{display:none!important}
      body{background:#fff}
      .card{border:none}
    }
  </style>
  <script>
    function printPage(){ window.print(); }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
  <div><strong>Admin</strong></div>
  <nav class="no-print">
    <a href="<?= site_url('admin') ?>">Dashboard</a>
    <a href="<?= site_url('admin/employees') ?>">Employees</a>
    <a href="<?= site_url('admin/products') ?>">Products</a>
    <a href="<?= site_url('/') ?>" class="secondary">Storefront</a>
    <a href="javascript:printPage()" class="secondary">Print</a>
  </nav>
</header>
<div class="container">
  <?= $this->renderSection('content') ?>
</div>
</body>
</html>
