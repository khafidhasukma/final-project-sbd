<?php
session_start();
session_unset();
session_destroy();
header("Location: /final-project-sbd/login/index.php");
exit;
