<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card">
  <h2 style="margin-top:0">Create Product</h2>
  <form method="post" action="/admin/products/store">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Name<br><input name="name" required /></label>
      <label>Price (USD)<br><input name="price_usd" type="number" step="0.01" required /></label>
      <label>Description<br><textarea name="description" rows="3"></textarea></label>
      <label>Image URL<br><input name="image_url" /></label>
    </div>
    <div style="margin-top:12px" class="no-print">
      <button class="btn" type="submit">Save</button>
      <a href="/admin/products" class="btn secondary">Cancel</a>
      <a href="javascript:printPage()" class="btn secondary">Print</a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>
