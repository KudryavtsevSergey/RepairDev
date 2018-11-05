<?php

function dm($arr) {
    $arr = (array)$arr;
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function dmf($arr, $fileAppend = false){
    $arr = (array)$arr;

    ob_start();
    print_r($arr);
    echo "\n";
    $result = ob_get_clean();
    if($fileAppend) {
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/filename.txt", $result, FILE_APPEND);
    }
    else{
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/filename.txt", $result);
    }
}