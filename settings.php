<?php
require_once './includes/db.php';
require_once './includes/header.php';

$settings = [
    'site_logo' => 'uploads/my_logo.png',
    'site_title' => 'PM Tool',
    'site_favicon' => 'assets/images/default-favicon.ico'
];

// Fetch settings from the database
$result = $conn->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('site_logo', 'site_title', 'site_favicon')");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$logo = $settings['site_logo'];
$page_title = $settings['site_title'];
$favicon_path = $settings['site_favicon'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['site_title'])) {
        $newTitle = trim($_POST['site_title']);
        $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'site_title'");
        $stmt->bind_param("s", $newTitle);
        $stmt->execute();
    }

    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . basename($_FILES['site_logo']['name']);
        $targetPath = $uploadDir . $fileName;
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (in_array($_FILES['site_logo']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $targetPath)) {
                $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'site_logo'");
                $stmt->bind_param("s", $targetPath);
                $stmt->execute();
            } else {
                echo "<p style='color:red;'>Failed to upload logo.</p>";
            }
        }
    }


    if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] == 0) {
        $uploadDir = 'uploads/favicons/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . basename($_FILES['site_favicon']['name']);
        $targetPath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/x-icon'];

        if (in_array($_FILES['site_favicon']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], $targetPath)) {
                $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'site_favicon'");
                $stmt->bind_param("s", $targetPath);
                $stmt->execute();
            } else {
                echo "<p style='color:red;'>Failed to upload favicon.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid file type for favicon. Only .jpg, .png, .webp, .gif, and .ico are allowed.</p>";
        }
    }
}
?>
<h4 class="mb-0">Site Settings</h4>

<div class="card mt-3">
    <div class="card-body">
        <form method="POST" name="employee-form" id="employee-form" class="p-3" enctype="multipart/form-data">
            <div class="row">

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="site_title" class="form-label fw-semibold">Site Title</label>
                        <input type="text" class="form-control" id="site_title" name="site_title"
                            placeholder="Enter new title" value="<?= htmlspecialchars($page_title) ?>">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="site_favicon" class="form-label fw-semibold">Site Favicon</label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="<?= htmlspecialchars($favicon_path) ?>" alt="Favicon" class="rounded border" style="max-height: 32px;">
                        </div>
                        <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/*">


                    </div>

                    <div class="col-md-6">
                        <label for="site_logo" class="form-label fw-semibold">Site Logo</label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="<?= htmlspecialchars($logo) ?>" alt="Site Logo" class="rounded border" style="max-height: 33px;">
                        </div>
                        <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                    </div>
                </div>

                <div class="text-start">
                    <button type="submit" name="save_settings" class="btn btn-primary px-4">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>




<?php require_once './includes/footer.php'; ?>