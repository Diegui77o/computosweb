<?php
session_start();
session_destroy();
$rol = null;
header('Location: index.php');
