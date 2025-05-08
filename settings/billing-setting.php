<?php
ob_start();
require_once '../includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['billing_setting'])) {
    $billedByName = $conn->real_escape_string(trim($_POST['billedByName']));
    $billedByPan = $conn->real_escape_string(trim($_POST['billedByPan']));
    $billedByAddress = $conn->real_escape_string(trim($_POST['billedByAddress']));

    $settingsToUpdate = [
        'billed_by_name' => $billedByName,
        'billed_by_pan' => $billedByPan,
        'billed_by_address' => $billedByAddress
    ];

    foreach ($settingsToUpdate as $key => $value) {
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        $stmt->bind_param("ss", $key, $value);
        $stmt->execute();
        $stmt->close();
    }
    echo '<div class="alert alert-success">Billing Settings saved successfully.</div>';
}


$settings = [
    'billed_by_name' => '',
    'billed_by_pan' => '',
    'billed_by_address' => '',
];

$result = $conn->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('billed_by_name', 'billed_by_pan', 'billed_by_address')");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
?>



<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Billing Setting</h4>
        </div>
    </div>
</div>

<div class="card p-3">
    <form method="post">
        <div class="row">
            <div class="col-md-5 ps-md-3 mb-3">
                <h6 class="mb-2"><b class="fs-5">Billed By</b></h6>
                <div class="mb-3">
                    <label for="billedByName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="billedByName" name="billedByName"
                    value="<?= isset($settings['billed_by_name']) ? htmlspecialchars($settings['billed_by_name']) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="billedByPan" class="form-label">PAN Code</label>
                    <input type="text" class="form-control" id="billedByPan" name="billedByPan"
                        value="<?=($settings['billed_by_pan']) ? htmlspecialchars($settings['billed_by_pan']) : '' ?>" >
                </div>
                <div class="">
                    <label for="billedByAddress" class="form-label">Address</label>
                    <textarea class="form-control" id="billedByAddress" name="billedByAddress"
                        rows="2"><?=($settings['billed_by_address']) ? htmlspecialchars($settings['billed_by_address']) : '' ?><?= htmlspecialchars($settings['billed_by_address']) ?></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="billing_setting">Save</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
