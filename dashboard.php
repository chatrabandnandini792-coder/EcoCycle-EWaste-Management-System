<?php
session_start();
if (empty($_SESSION['ewaste_admin'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db.php';
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$allowed = ['Pending','Approved','Collected','Recycled','Cancelled'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['status'])) {
    $id = (int)$_POST['request_id']; $status = $_POST['status'];
    if (in_array($status, $allowed, true)) {
        $stmt = $conn->prepare('UPDATE pickup_requests SET status=? WHERE id=?');
        $stmt->bind_param('si', $status, $id); $stmt->execute(); $stmt->close();
    }
    header('Location: dashboard.php'); exit;
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: login.php'); exit; }
$filter = $_GET['status'] ?? '';
$sql = "SELECT * FROM pickup_requests";
if (in_array($filter, $allowed, true)) $sql .= " WHERE status='" . $conn->real_escape_string($filter) . "'";
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
$counts = ['total'=>0,'Pending'=>0,'Approved'=>0,'Collected'=>0,'Recycled'=>0,'Cancelled'=>0];
$cr = $conn->query("SELECT status, COUNT(*) n FROM pickup_requests GROUP BY status");
if ($cr) while ($r=$cr->fetch_assoc()) $counts[$r['status']] = (int)$r['n'];
$counts['total'] = array_sum(array_intersect_key($counts, array_flip($allowed)));
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Dashboard | EcoCycle</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="dashboard-page"><header class="site-header"><a class="brand" href="../index.php"><span class="brand-mark">♻</span><span>Eco<span class="brand-accent">Cycle</span><small>ADMIN DASHBOARD</small></span></a><nav><a href="../index.php">View website ↗</a><a class="nav-admin" href="?logout=1">Log out</a></nav></header>
<main class="dashboard-wrap"><div class="dashboard-title"><div><div class="section-kicker">OPERATIONS / OVERVIEW</div><h1>Pickup requests</h1><p>Review incoming collections and update their progress.</p></div><a class="btn btn-primary" href="../index.php#pickup">＋ New request</a></div>
<div class="dashboard-stats"><div><span>Total requests</span><strong><?= $counts['total'] ?></strong><small>All submitted requests</small></div><div><span>Pending</span><strong><?= $counts['Pending'] ?></strong><small>Waiting for review</small></div><div><span>Collected</span><strong><?= $counts['Collected'] ?></strong><small>Items picked up</small></div><div><span>Recycled</span><strong><?= $counts['Recycled'] ?></strong><small>Marked complete</small></div></div>
<section class="table-panel"><div class="table-heading"><div><h2>Collection list</h2><p>Latest requests appear first.</p></div><form method="get"><select name="status" onchange="this.form.submit()"><option value="">All statuses</option><?php foreach($allowed as $s): ?><option value="<?= e($s) ?>" <?= $filter===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select></form></div>
<div class="table-scroll"><table><thead><tr><th>Request</th><th>Contact</th><th>E-waste details</th><th>Pickup date</th><th>Status / update</th></tr></thead><tbody>
<?php if ($result && $result->num_rows): while($row=$result->fetch_assoc()): ?><tr><td><b>#<?= (int)$row['id'] ?></b><small><?= e(date('d M Y', strtotime($row['created_at']))) ?></small></td><td><b><?= e($row['name']) ?></b><small><?= e($row['email']) ?></small><small><?= e($row['phone']) ?></small><small><?= e($row['address']) ?></small></td><td><b><?= e($row['waste_type']) ?></b><small><?= e($row['quantity_kg']) ?> kg</small><?php if($row['notes']): ?><small>Note: <?= e($row['notes']) ?></small><?php endif; ?></td><td><?= e(date('d M Y', strtotime($row['pickup_date']))) ?></td><td><span class="status status-<?= strtolower(e($row['status'])) ?>"><?= e($row['status']) ?></span><form class="status-form" method="post"><input type="hidden" name="request_id" value="<?= (int)$row['id'] ?>"><select name="status"><?php foreach($allowed as $s): ?><option <?= $row['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="mini-btn">Save</button></form></td></tr><?php endwhile; else: ?><tr><td colspan="5" class="empty-state">No requests found yet. Submit a request from the website to see it here.</td></tr><?php endif; ?>
</tbody></table></div></section><p class="dashboard-foot">EcoCycle local demo · Keep your admin credentials private.</p></main></body></html>