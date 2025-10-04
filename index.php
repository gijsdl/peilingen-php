<?php

require 'db.php';
require 'vendor/autoload.php';
require 'poll.php';

$request = $_SERVER['REQUEST_URI'];
$params = explode("/", $request);
$title = "Peilingen";

switch ($params[1]) {
    case 'new-poll' :

        if (isset($_POST["submit"])) {
            addPoll();
        }

        include_once "add-poll.php";
        break;
    default:

        break;
}