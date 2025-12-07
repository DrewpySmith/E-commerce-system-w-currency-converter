<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2 style="margin:0">Employees</h2>
    <div class="no-print">
      <a href="/admin/employees/create" class="btn">Add Employee</a>
      <a href="javascript:printPage()" class="btn secondary">Print</a>
    </div>
  </div>
  <table>
    <thead><tr><th>Name</th><th>Email</th><th>Position</th><th>Salary</th><th class="no-print">Actions</th></tr></thead>
    <tbody>
      <?php foreach(($employees ?? []) as $e): ?>
        <tr>
          <td><?= esc($e['name']) ?></td>
          <td><?= esc($e['email']) ?></td>
          <td><?= esc($e['position']) ?></td>
          <td><?= $e['salary'] !== null ? '$'.number_format((float)$e['salary'],2) : '-' ?></td>
          <td class="actions no-print">
            <a href="/admin/employees/edit/<?= $e['id'] ?>" class="btn secondary">Edit</a>
            <a href="/admin/employees/delete/<?= $e['id'] ?>" class="btn danger" onclick="return confirm('Delete employee?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
