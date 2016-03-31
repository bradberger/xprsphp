<?php

spl_autoload_register(function ($className) {
    $filename = sprintf('%s/src/%s.php', __DIR__, str_replace('\\', '/', $className));
    if (file_exists($filename)) {
        include($filename);
        if (class_exists($className)) {
            return true;
        }
    }
    return false;
});
