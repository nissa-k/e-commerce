<?php
require __DIR__ . "/includes/auth.php";
echo "strict_mode=" . ini_get('session.use_strict_mode') . "<br>";
echo "session_id=" . session_id() . "<br>";
?>