<?php
session_start();


$_SESSION = array();


session_destroy();


header("Location: /bd2/app/index.html");
exit();

