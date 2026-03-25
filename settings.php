<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_settings'])) {
    $db->query("UPDATE settings SET config_value = '".$_POST['cold']."' WHERE config_key = 'temp_cold'");
    $db->query("UPDATE settings SET config_value = '".$_POST['hot']."' WHERE config_key = 'temp_hot'");
    $db->query("UPDATE settings SET config_value = '".$_POST['freq']."' WHERE config_key = 'frequency_min'");
    $msg = "Settings updated successfully!";
}

$s_res = $db->query("SELECT * FROM settings");
while($row = $s_res->fetch_assoc()) { $s[$row['config_key']] = $row['config_value']; }

$freqs = [1, 2, 5, 10, 30, 60, 120, 240, 480, 720, 1440];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Settings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-normal">
    <div class="dashboard">
        <div class="card settings-card">
            <h2>⚙️ Config Menu</h2>
            <?php if(isset($msg)) echo "<p class='alert'>$msg</p>"; ?>
            
            <form method="POST">
                <div class="input-group">
                    <label>Cold Level (°C):</label>
                    <input type="number" name="cold" value="<?php echo $s['temp_cold']; ?>" step="0.5">
                </div>

                <div class="input-group">
                    <label>Hot Level (°C):</label>
                    <input type="number" name="hot" value="<?php echo $s['temp_hot']; ?>" step="0.5">
                </div>

                <div class="input-group">
                    <label>Update Frequency:</label>
                    <select name="freq">
                        <?php foreach($freqs as $f): ?>
                            <option value="<?php echo $f; ?>" <?php echo $s['frequency_min'] == $f ? 'selected' : ''; ?>>
                                <?php 
                                    if($f < 60) echo "$f mins";
                                    else echo ($f/60) . " hour" . ($f>60 ? 's' : '');
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="actions">
                    <button type="submit" name="save_settings" class="btn-save">Save Changes</button>
                    <a href="index.php" class="btn-back">Go Back</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
