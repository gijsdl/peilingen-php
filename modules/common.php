<?php
function addFlash($class, $message){
    $_SESSION['flash']['class'] = $class;
    $_SESSION['flash']['message'] = $message;
}