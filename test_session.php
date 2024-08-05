<?php
if (headers_sent($file, $line)) {
    die("Headers already sent in $file on line $line");
}

session_start();
echo "Session started successfully.";
?>
