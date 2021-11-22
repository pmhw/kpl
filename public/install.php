<?php
    //判断php版本
    if (version_compare(PHP_VERSION,'5.6','<')) die(' PHP 版本必须要 > 5.6');
    
    if (!is_file('./install/install.lock')) {
        header("location: install/install.php");
        exit;
    }
?>