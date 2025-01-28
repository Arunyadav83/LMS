<?php
require_once 'config.php';

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}


function get_user_role() {
    return $_SESSION['user_role'] ?? null;
}

// New admin-specific functions
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function require_admin_login() {
    if (!is_admin_logged_in()) {
        redirect('admin/login.php');
    }
}

// Tutor-specific function
function is_tutor_logged_in() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'tutor';
}

// You can add more functions here as needed
?>