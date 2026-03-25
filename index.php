<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pi 5 Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
// Get Settings
$s_res = $db->query("SELECT * FROM settings");
while($row = $s_res->fetch_assoc()) { $s[$row['config_key']] = $row['config_value']; }

// Get Last Measurement
$m_res = $db->query("SELECT * FROM measurements ORDER BY timestamp DESC LIMIT 1");
$last = $m_res->fetch_assoc();

$status = $last['status'] ?? 'normal';
$last_time = strtotime($last['timestamp'] ?? 'now');
$is_online = (time() - $last_time) < ($s['frequency_min'] * 60 + 30);
?>
<body class="bg-<?php echo $status; ?>">
    <div class="dashboard">
        <header>
            <h1>Pi 5 Eco-Monitor</h1>
            <div class="status-indicator <?php echo $is_online ? 'online' : 'offline'; ?>">
                <?php echo $is_online ? 'System Live' : 'System Offline'; ?>
            </div>
        </header>

        <main class="card">
            <p class="timestamp">Last Update: <?php echo date('H:i:s - M dS', $last_time); ?></p>
            
            <div class="metrics">
                <div class="metric">
                    <span class="value"><?php echo round($last['temperature'], 1); ?>°C</span>
                    <span class="label">Temperature</span>
                </div>
                <div class="metric">
                    <span class="value"><?php echo round($last['humidity'], 0); ?>%</span>
                    <span class="label">Humidity</span>
                </div>
            </div>

            <div class="badge <?php echo $status; ?>">
                Environment: <?php echo strtoupper($status); ?>
            </div>

            <form action="manual_trigger.php" method="POST">
                <button type="submit" name="force" class="btn-measure">Measure Now ⚡</button>
            </form>
        </main>

        <footer>
            <a href="settings.php" class="link-settings">⚙️ Settings Dashboard</a>
        </footer>
    </div>
</body>
</html>
