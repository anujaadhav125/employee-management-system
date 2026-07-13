<?php

function redirect($location)
{
    header("Location: $location");
    exit();
}

function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function getLoggedInUser()
{
    return $_SESSION['user'] ?? null;
}

function generateEmployeeCode($id)
{
    return "EMP-" . date("Y") . "-" . str_pad($id, 3, "0", STR_PAD_LEFT);
}