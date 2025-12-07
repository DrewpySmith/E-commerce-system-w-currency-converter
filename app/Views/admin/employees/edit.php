<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card">
  <h2 style="margin-top:0">Edit Employee</h2>
  <form method="post" action="/admin/employees/update/<?= $employee['id'] ?>">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Name<br><input name="name" value="<?= esc($employee['name']) ?>" required /></label>
      <label>Email<br><input name="email" type="email" value="<?= esc($employee['email']) ?>" required /></label>
      <label>Position<br><input name="position" value="<?= esc($employee['position']) ?>" required /></label>
      <label>Salary (USD)<br><input name="salary" type="number" step="0.01" value="<?= esc($employee['salary']) ?>" /></label>
    </div>
    <div style="margin-top:12px" class="no-print">
      <button class="btn" type="submit">Update</button>
      <a href="/admin/employees" class="btn secondary">Cancel</a>
      <a href="javascript:printPage()" class="btn secondary">Print</a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>
