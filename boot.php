<?php
if (false === strpos($_SERVER['REQUEST_URI'], '/setup/install')) {
    header("Location: " . '/setup/install');
    exit;
}
