<?php

class SimpleClass
{
    public $name = 1;
}


$instance = new SimpleClass();
$file = $_SERVER['DOCUMENT_ROOT'] . '/datatables/pdo.php';
if (is_file($file)) {
    echo $file;
} else {
    echo "file notfound.";
}
