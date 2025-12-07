<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card">
  <h2 style="margin-top:0">Create Employee</h2>
  <form method="post" action="/admin/employees/store">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Name<br><input name="name" required /></label>
      <label>Email<br><input name="email" type="email" required /></label>
      <label>Position<br><input name="position" required /></label>
      <label>Salary (USD)<br><input name="salary" type="number" step="0.01" /></label>
    </div>
    <div style="margin-top:12px" class="no-print">
      <button class="btn" type="submit">Save</button>
      <a href="/admin/employees" class="btn secondary">Cancel</a>
      <a href="javascript:printPage()" class="btn secondary">Print</a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>
