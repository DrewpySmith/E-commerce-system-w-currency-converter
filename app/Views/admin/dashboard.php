<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px" class="no-print">
    <h2 style="margin:0">Dashboard</h2>
    <form method="get" action="<?= site_url('admin') ?>" style="display:flex;gap:8px;align-items:center">
      <label>From
        <input type="date" name="start" value="<?= esc($start ?? '') ?>" />
      </label>
      <label>To
        <input type="date" name="end" value="<?= esc($end ?? '') ?>" />
      </label>
      <button class="btn" type="submit">Apply</button>
      <a href="<?= site_url('admin') ?>" class="btn secondary">Reset</a>
      <a href="javascript:printPage()" class="btn secondary">Print</a>
    </form>
  </div>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:12px">
    <div class="card"><div>Total Products</div><div style="font-size:24px;font-weight:bold"><?= number_format((int)($totalProducts ?? 0)) ?></div></div>
    <div class="card"><div>Total Users</div><div style="font-size:24px;font-weight:bold"><?= number_format((int)($totalUsers ?? 0)) ?></div></div>
    <div class="card"><div>Orders (Paid)</div><div style="font-size:24px;font-weight:bold"><?= number_format((int)($totalOrders ?? 0)) ?></div></div>
    <div class="card"><div>Revenue (USD)</div><div style="font-size:24px;font-weight:bold">$<?= number_format((float)($sumRevenue ?? 0),2) ?></div></div>
  </div>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:12px">
    <div class="card"><div>Avg Order Value</div><div style="font-size:24px;font-weight:bold">$<?= number_format((float)($avgOrder ?? 0),2) ?></div></div>
    <div class="card"><div>Active Customers</div><div style="font-size:24px;font-weight:bold"><?= number_format((int)($activeCustomers ?? 0)) ?></div></div>
    <div class="card"><div>Date From</div><div style="font-size:18px;opacity:.8"><?= esc($start ?? '') ?></div></div>
    <div class="card"><div>Date To</div><div style="font-size:18px;opacity:.8"><?= esc($end ?? '') ?></div></div>
  </div>
</div>

<div class="card">
  <h3 style="margin-top:0">Orders & Revenue (Daily)</h3>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;min-height:280px">
    <div style="position:relative;height:260px"><canvas id="ordersChart"></canvas></div>
    <div style="position:relative;height:260px"><canvas id="revenueChart"></canvas></div>
  </div>
  <script>
    const labels = <?= json_encode($chartLabels ?? []) ?>;
    const orders = <?= json_encode($chartOrders ?? []) ?>;
    const revenue = <?= json_encode($chartRevenue ?? []) ?>;

    const ordersCtx = document.getElementById('ordersChart');
    const revenueCtx = document.getElementById('revenueChart');

    if (window._ordersChart) window._ordersChart.destroy();
    if (window._revenueChart) window._revenueChart.destroy();

    window._ordersChart = new Chart(ordersCtx, {
      type: 'line',
      data: { labels, datasets: [{ label: 'Orders', data: orders, borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.15)', tension: 0.3 }] },
      options: { responsive: true, maintainAspectRatio: false }
    });

    window._revenueChart = new Chart(revenueCtx, {
      type: 'bar',
      data: { labels, datasets: [{ label: 'Revenue (USD)', data: revenue, backgroundColor: 'rgba(16,185,129,0.5)', borderColor: '#10b981' }] },
      options: { responsive: true, maintainAspectRatio: false }
    });
  </script>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px">
  <div class="card">
    <h3 style="margin-top:0">Recent Orders</h3>
    <table>
      <thead><tr><th>ID</th><th>User</th><th>Total (USD)</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach(($recentOrders ?? []) as $o): ?>
          <tr>
            <td>#<?= (int)$o['id'] ?></td>
            <td><?= esc($o['user_id']) ?></td>
            <td>$<?= number_format((float)$o['total_usd'],2) ?></td>
            <td><?= esc($o['status']) ?></td>
            <td><?= esc($o['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($recentOrders)): ?>
          <tr><td colspan="5" class="text-muted">No orders in selected range.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="card">
    <h3 style="margin-top:0">Top Products</h3>
    <table>
      <thead><tr><th>Product</th><th>Qty</th><th>Revenue</th></tr></thead>
      <tbody>
        <?php foreach(($topProducts ?? []) as $p): ?>
          <tr>
            <td><?= esc($p['product'] ?? 'Unknown') ?></td>
            <td><?= number_format((int)($p['qty'] ?? 0)) ?></td>
            <td>$<?= number_format((float)($p['revenue'] ?? 0),2) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($topProducts)): ?>
          <tr><td colspan="3" class="text-muted">No products in selected range.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
