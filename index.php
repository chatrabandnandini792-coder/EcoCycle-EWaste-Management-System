<?php
session_start();
require_once __DIR__ . '/includes/db.php';
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $waste_type = trim($_POST['waste_type'] ?? '');
    $quantity = (float)($_POST['quantity'] ?? 0);
    $pickup_date = trim($_POST['pickup_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $phone === '' || $address === '' || $waste_type === '' || $quantity <= 0 || $pickup_date === '') {
        $error = 'Please complete all required fields with valid information.';
    } else {
        $stmt = $conn->prepare("INSERT INTO pickup_requests (name,email,phone,address,waste_type,quantity_kg,pickup_date,notes) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssdss", $name, $email, $phone, $address, $waste_type, $quantity, $pickup_date, $notes);
        if ($stmt->execute()) {
            $success = 'Your e-waste pickup request has been submitted! Keep your request ID: #' . $stmt->insert_id;
        } else {
            $error = 'Could not save your request. Please check the database setup and try again.';
        }
        $stmt->close();
    }
}
$stats = ['total'=>0, 'pending'=>0, 'recycled'=>0];
$r = $conn->query("SELECT COUNT(*) total, SUM(status='Pending') pending, SUM(status='Recycled') recycled FROM pickup_requests");
if ($r && $row = $r->fetch_assoc()) { foreach ($stats as $k=>$v) $stats[$k] = (int)($row[$k] ?? 0); }
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcoCycle | E-Waste Management</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="index.php"><span class="brand-mark">♻</span><span>Eco<span class="brand-accent">Cycle</span><small>E-WASTE MANAGEMENT</small></span></a>
  <nav><a href="#how">How it works</a><a href="#impact">Our impact</a><a href="#pickup">Request pickup</a><a class="nav-admin" href="admin/login.php">Admin</a></nav>
</header>
<main>
<section class="hero">
  <div class="hero-content">
    <span class="eyebrow"><span class="pulse"></span> MAKE TECHNOLOGY CIRCULAR</span>
    <h1>Old electronics.<br><em>New possibilities.</em></h1>
    <p>Give your unused electronics a responsible next step. Request a pickup and help keep hazardous e-waste out of landfills.</p>
    <div class="hero-actions"><a class="btn btn-primary" href="#pickup">Schedule a pickup <span>↗</span></a><a class="text-link" href="#how">Explore our process <span>↓</span></a></div>
    <div class="hero-proof"><div class="proof-avatars"><span>✓</span><span>♻</span><span>●</span></div><div><strong>Every device matters.</strong><small>Reuse more. Waste less.</small></div></div>
  </div>
  <div class="hero-art" aria-label="Illustration of electronic waste recycling">
    <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
    <div class="device device-phone"><div class="phone-screen">eco<br><b>mode</b><span>↻</span></div></div>
    <div class="device device-laptop"><div class="laptop-screen"><div class="screen-leaf">♻</div><small>REBOOT THE FUTURE</small></div><div class="laptop-base"></div></div>
    <div class="recycle-bubble">♻</div><div class="floating-tag tag-one">E-WASTE → RESOURCE</div><div class="floating-tag tag-two">REPAIR · REUSE · RECYCLE</div>
    <div class="hero-art-caption"><span>01 /</span> A cleaner digital tomorrow</div>
  </div>
</section>
<section class="ticker"><span>RECYCLE RESPONSIBLY</span><b>✳</b><span>PROTECT OUR PLANET</span><b>✳</b><span>KEEP E-WASTE CIRCULAR</span><b>✳</b><span>RECYCLE RESPONSIBLY</span></section>
<section class="section intro" id="how">
  <div class="section-kicker">01 — SIMPLE BY DESIGN</div>
  <div class="section-heading"><h2>Make the right thing<br><em>the easy thing.</em></h2><p>Responsible disposal shouldn't be complicated. We make it easier to connect your old devices with a better destination.</p></div>
  <div class="steps">
    <article class="step-card"><div class="step-number">01</div><div class="step-icon">⌁</div><h3>Tell us what you have</h3><p>Share the type and estimated quantity of your electronic waste.</p><span class="step-line"></span></article>
    <article class="step-card"><div class="step-number">02</div><div class="step-icon">⌖</div><h3>Arrange a pickup</h3><p>Send your address and preferred date so the team can coordinate.</p><span class="step-line"></span></article>
    <article class="step-card"><div class="step-number">03</div><div class="step-icon">↻</div><h3>Give it a new life</h3><p>Items can be sorted for reuse, refurbishment, or responsible recycling.</p><span class="step-line"></span></article>
  </div>
</section>
<section class="impact-section" id="impact"><div class="impact-copy"><div class="section-kicker light">02 — SMALL ACTION, BIGGER IMPACT</div><h2>The future isn't<br>disposable.</h2><p>Electronics can contain valuable recoverable materials as well as substances that require careful handling. Choosing responsible collection is a practical step toward a cleaner circular economy.</p><a class="impact-link" href="#pickup">Be part of the change <span>↗</span></a></div><div class="impact-stats"><div><strong><?= number_format($stats['total']) ?></strong><span>Pickup requests received</span></div><div><strong><?= number_format($stats['pending']) ?></strong><span>Requests awaiting review</span></div><div><strong><?= number_format($stats['recycled']) ?></strong><span>Requests marked recycled</span></div><small>Live figures from this local demo database.</small></div></section>
<section class="pickup-section" id="pickup">
  <div class="pickup-info"><div class="section-kicker">03 — TAKE THE FIRST STEP</div><h2>Let's collect your<br><em>old electronics.</em></h2><p>Fill in the form and our team can review your collection request. Fields marked * are required.</p><div class="info-note"><span>✦</span><div><strong>Before your pickup</strong><p>Back up your data and remove personal accounts from phones, computers, and storage devices where possible.</p></div></div><div class="info-note"><span>⌁</span><div><strong>Handle with care</strong><p>Do not place damaged or swollen batteries in household waste. Keep them separate and mention them in the notes.</p></div></div></div>
  <form class="pickup-form" method="post" action="#pickup">
    <div class="form-top"><div><span>COLLECTION FORM</span><h3>Request a pickup</h3></div><span class="form-badge">FREE TO REQUEST</span></div>
    <?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <div class="form-grid">
      <label>Full name *<input name="name" required placeholder="Your name" value="<?= e($_POST['name'] ?? '') ?>"></label>
      <label>Email address *<input name="email" type="email" required placeholder="you@example.com" value="<?= e($_POST['email'] ?? '') ?>"></label>
      <label>Phone number *<input name="phone" required placeholder="+91 98765 43210" value="<?= e($_POST['phone'] ?? '') ?>"></label>
      <label>Type of e-waste *<select name="waste_type" required><option value="">Select category</option><?php foreach (['Computers & laptops','Mobile phones & tablets','TVs & monitors','Small household electronics','Cables & accessories','Batteries','Mixed e-waste','Other'] as $type): ?><option <?= (($_POST['waste_type'] ?? '')===$type?'selected':'') ?>><?= e($type) ?></option><?php endforeach; ?></select></label>
      <label>Estimated quantity (kg) *<input name="quantity" type="number" min="0.1" step="0.1" required placeholder="e.g. 3.5" value="<?= e($_POST['quantity'] ?? '') ?>"></label>
      <label>Preferred pickup date *<input name="pickup_date" type="date" min="<?= date('Y-m-d') ?>" required value="<?= e($_POST['pickup_date'] ?? '') ?>"></label>
      <label class="full">Pickup address *<textarea name="address" required rows="2" placeholder="Building, street, area, city, PIN code"><?= e($_POST['address'] ?? '') ?></textarea></label>
      <label class="full">Additional notes <textarea name="notes" rows="2" placeholder="Device condition, quantity details, battery information…"><?= e($_POST['notes'] ?? '') ?></textarea></label>
    </div>
    <button class="btn btn-primary submit-btn" name="submit_request" type="submit">Submit pickup request <span>↗</span></button>
    <p class="form-foot">Your information is used to manage this demo pickup request.</p>
  </form>
</section>
<section class="closing"><span>♻</span><h2>Let's make e-waste<br><em>matter less.</em></h2><a href="#pickup">Start with one device ↗</a></section>
</main>
<footer><a class="brand footer-brand" href="#"><span class="brand-mark">♻</span><span>Eco<span class="brand-accent">Cycle</span><small>E-WASTE MANAGEMENT</small></span></a><span>Designed for a more circular future.</span><span>© <?= date('Y') ?> EcoCycle · Student project</span></footer>
<script src="assets/js/script.js"></script>
</body></html>