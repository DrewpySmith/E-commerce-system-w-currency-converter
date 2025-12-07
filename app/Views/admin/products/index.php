<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2 style="margin:0">Products</h2>
    <div class="no-print">
      <a href="/admin/products/create" class="btn">Add Product</a>
      <a href="javascript:printPage()" class="btn secondary">Print</a>
    </div>
  </div>
  <table>
    <thead><tr><th>Name</th><th>Description</th><th>Price (USD)</th><th class="no-print">Actions</th></tr></thead>
    <tbody>
      <?php foreach(($products ?? []) as $p): ?>
        <tr>
          <td><?= esc($p['name']) ?></td>
          <td><?= esc($p['description']) ?></td>
          <td>$<?= number_format((float)$p['price_usd'],2) ?></td>
          <td class="actions no-print">
            <a href="/admin/products/edit/<?= $p['id'] ?>" class="btn secondary">Edit</a>
            <a href="/admin/products/delete/<?= $p['id'] ?>" class="btn danger" onclick="return confirm('Delete product?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
