<?php
require 'config.php';
require_login();

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    $label = trim($_POST['label'] ?? '');
    $password = $_POST['password_to_save'] ?? '';
    $score = intval($_POST['score'] ?? 0);
    $strengthLabel = trim($_POST['strength_label'] ?? 'Unknown');

    if ($label === '' || $password === '') {
        $errors[] = 'Please provide both a label and a password to save.';
    } else {
        $enc = encrypt_string($password);
        $stmt = $conn->prepare(
            'INSERT INTO saved_passwords (user_id, label, encrypted_password, iv, strength_score, strength_label)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('isssis', $user_id, $label, $enc['encrypted'], $enc['iv'], $score, $strengthLabel);
        if ($stmt->execute()) {
            $success = 'Password saved to your vault.';
        } else {
            $errors[] = 'Could not save the password. Please try again.';
        }
        $stmt->close();
    }
}

//Fetch password
$stmt = $conn->prepare(
    'SELECT id, label, encrypted_password, iv, strength_score, strength_label, created_at
     FROM saved_passwords WHERE user_id = ? ORDER BY created_at DESC'
);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$saved = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function badge_color($label) {
    switch ($label) {
        case 'Strong':
        case 'Very Strong': return 'success';
        case 'Medium': return 'warning';
        default: return 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Password Vault</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-vault navbar-expand">
    <div class="container">
        <span class="navbar-brand">🔒 Password Vault</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-dim">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-accent">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-4">

    <?php if ($success): ?>
        <div class="alert alert-vault"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Strength checker -->
        <div class="col-lg-6">
            <div class="panel-card h-100">
                <h5 class="mb-3">Check a password's strength</h5>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" id="passwordInput" class="form-control" placeholder="Type a password to test">
                        <button class="btn btn-outline-accent" type="button" data-toggle-password="passwordInput">Show</button>
                    </div>
                    <div class="strength-meter" id="checkMeter">
                        <div class="bar"></div><div class="bar"></div><div class="bar"></div>
                        <div class="bar"></div><div class="bar"></div>
                    </div>
                    <div class="strength-label" id="checkLabel">Enter a password</div>
                    <ul class="criteria-list" id="checkCriteria"></ul>
                </div>

                <hr style="border-color: var(--line);">

                <form method="POST" action="dashboard.php">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="password_to_save" id="hiddenPassword">
                    <input type="hidden" name="score" id="hiddenScore">
                    <input type="hidden" name="strength_label" id="hiddenStrengthLabel">

                    <label class="form-label">Save this password as</label>
                    <div class="input-group">
                        <input type="text" name="label" class="form-control" placeholder="e.g. Gmail, College Portal" required>
                        <button type="submit" class="btn btn-accent">Save to Vault</button>
                    </div>
                    <div class="form-text text-dim">Saved passwords are encrypted before being stored.</div>
                </form>
            </div>
        </div>

        <!-- Saved passwords -->
        <div class="col-lg-6">
            <div class="panel-card h-100">
                <h5 class="mb-3">Your saved passwords</h5>

                <?php if (empty($saved)): ?>
                    <p class="text-dim">You haven't saved any passwords yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vault align-middle">
                            <thead>
                                <tr>
                                    <th>Label</th>
                                    <th>Password</th>
                                    <th>Strength</th>
                                    <th>Saved</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($saved as $row): ?>
                                    <?php $plain = decrypt_string($row['encrypted_password'], $row['iv']); ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['label']) ?></td>
                                        <td>
                                            <span class="font-monospace masked" data-full="<?= htmlspecialchars($plain) ?>">
                                                ••••••••
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-accent reveal-btn">Show</button>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= badge_color($row['strength_label']) ?> badge-strength">
                                                <?= htmlspecialchars($row['strength_label']) ?>
                                            </span>
                                        </td>
                                        <td class="text-dim"><?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?></td>
                                        <td>
                                            <form method="POST" action="delete_password.php" data-confirm="Delete this saved password?">
                                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
    const checker = initStrengthChecker({
        inputId: 'passwordInput',
        meterId: 'checkMeter',
        labelId: 'checkLabel',
        listId: 'checkCriteria'
    });

    // Keep the hidden save-form fields in sync with the live checker
    const passwordInput = document.getElementById('passwordInput');
    passwordInput.addEventListener('input', () => {
        const { score } = checker.evaluate();
        document.getElementById('hiddenPassword').value = passwordInput.value;
        document.getElementById('hiddenScore').value = score;
        document.getElementById('hiddenStrengthLabel').value =
            document.getElementById('checkLabel').textContent;
    });

    // Reveal/hide saved passwords in the table
    document.querySelectorAll('.reveal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const span = btn.previousElementSibling;
            const isMasked = span.textContent.trim() === '••••••••';
            span.textContent = isMasked ? span.dataset.full : '••••••••';
            btn.textContent = isMasked ? 'Hide' : 'Show';
        });
    });
</script>
</body>
</html>