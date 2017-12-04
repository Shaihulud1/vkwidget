<?php
function checkNumber($inputData)
{
    return intval($inputData);
}

function checkString($inputData)
{
    $inputData = strip_tags($inputData);
    $inputData = htmlspecialchars($inputData);
    $inputData = mysql_escape_string($inputData);
    return $inputData;
}