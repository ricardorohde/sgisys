<?php
if (isset($_SESSION['debug'])) {
    if ($_SESSION['debug'] == 'S') {
        ini_set('display_errors', 'on');
    } else {
        ini_set('display_errors', 'off');
    }
}

