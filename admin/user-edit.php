<?php
$pageTitle = 'Edit Administrator';
require_once 'includes/header.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) redirect('users.php');

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) redirect('users.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $role = $_POST['role'] ?? 'author';
    $new_password = $_POST['password'] ?? '';

    if (empty($username) || empty($email)) {
        $error = 'Username and email are required.';
    } else {
        // Check if username or email exists elsewhere
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username or email already exists.';
        } else {
            try {
                $params = [
                    'user' => $username,
                    'email' => $email,
                    'name' => $full_name,
                    'role' => $role,
                    'id' => $id
                ];
                
                $sql = "UPDATE users SET username = :user, email = :email, full_name = :name, role = :role";
                
                if (!empty($new_password)) {
                    $sql .= ", password = :pass";
                    $params['pass'] = password_hash($new_password, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE id = :id";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                
                $success = "Administrator account updated successfully!";
                
                // Update session if it's the current user
                if ($id === (int)$_SESSION['admin_id']) {
                    $_SESSION['admin_user'] = $username;
                    $_SESSION['admin_name'] = $full_name;
                    $_SESSION['admin_role'] = $role;
                }
                
                // Refresh user data
                $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $user = $stmt->fetch();
            } catch (Exception $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="mb-4">
    <a href="users.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Administrators
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Edit Account: <?= e($user['username']) ?></h6>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>

                <form action="user-edit.php?id=<?= $id ?>" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. John Doe" value="<?= e($user['full_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" <?= ((int)$user['id'] === (int)$_SESSION['admin_id'] && $_SESSION['admin_role'] === 'admin') ? '' : '' ?>>
                                <option value="author" <?= $user['role'] === 'author' ? 'selected' : '' ?>>Author</option>
                                <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required value="<?= e($user['username']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required value="<?= e($user['email']) ?>">
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="p-3 bg-light rounded">
                                <label class="form-label fw-bold">Update Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                                <small class="text-muted">Only fill this if you want to change the password.</small>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <hr>
                            <button type="submit" class="btn btn-gold px-5 py-2 fw-bold">
                                <i class="bi bi-save me-2"></i> Update Account
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>