<?php
include 'db.php';

if (isset($_POST['force'])) {
    // We set force_read to 1. The Python script will see this and act immediately.
    $db->query("UPDATE settings SET config_value = '1' WHERE config_key = 'force_read'");
}

header("Location: index.php");
exit();
?>
