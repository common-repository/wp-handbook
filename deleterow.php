<?php
session_start();
$val = $_POST['keyN'];

foreach($_SESSION['handbook'] as $key => $value) {
    if($value==$val) unset($_SESSION['handbook'][$key]);
}
