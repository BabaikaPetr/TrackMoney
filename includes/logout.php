<?php
session_start();
session_unset();
session_destroy();
header('Location: ../index.html');
exit();

if (isset($_SESSION['error'])) {
    echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>