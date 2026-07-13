<?php

session_start();

require '../helpers/functions.php';

if (!isLoggedIn()) {

    redirect("../auth/login.php");

}

?>