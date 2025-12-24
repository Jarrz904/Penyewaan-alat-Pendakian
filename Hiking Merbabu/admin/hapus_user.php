<?php
session_start();
include '../includes/db.php';

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM users WHERE id = $id");
header("Location: admin_users.php");
exit;
?>
