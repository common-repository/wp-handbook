<?php
session_start();

unset($_SESSION['handbook']);

foreach($_POST['listItem'] as $key => $value) {
    $_SESSION['handbook'][$key] = $value;
}
