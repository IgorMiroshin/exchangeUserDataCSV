<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates/teploset/cron/updateUser/class/exchangeUserDataCSV.php";

$fileName = 'export.csv';
$path = $_SERVER["DOCUMENT_ROOT"] . '/upload/users/';
if (file_exists($path . $fileName)) {

    $exchangeUserDataCSVClass = new exchangeUserDataCSV($path . $fileName);
    $result = $exchangeUserDataCSVClass->update();
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
} else {
    echo "Файл export.csv не существует";
}