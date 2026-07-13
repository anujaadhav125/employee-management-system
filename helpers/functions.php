<?php

/**
 * Redirect user to another page.
 */

function redirect($location)
{
    header("Location: $location");
    exit();
}

/**
 * Sanitize user input.
 */

function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

/**
 * Check if user is logged in.
 */

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Generate Employee Code
 */

function generateEmployeeCode($id)
{
    return "EMP-" . date("Y") . "-" . str_pad($id, 3, "0", STR_PAD_LEFT);
}

?>