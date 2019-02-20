<?php
/**
 * @param int $time
 */
function startLimit($time = 5){
    session_start();
    if (isset($_SESSION['LAST_CALL'])) {
        $last = strtotime($_SESSION['LAST_CALL']);
        $curr = strtotime(date("Y-m-d h:i:s"));
        $sec =  abs($last - $curr);
        if ($sec <= $time) {
            header("Location: limit.html");
            exit;
        }
    }
    $_SESSION['LAST_CALL'] = date("Y-m-d h:i:s");
}