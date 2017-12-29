<?php

require_once 'dids.php';

//var_dump($did);
$db = new PDO('mysql:host=localhost', 'root', '');

function generateQuery($extToCheck) {
    $query = <<<MYSQL
SELECT c.`presence_id` FROM `freeswitch`.`channels` c WHERE `presence_id` REGEXP '^{$extToCheck}@.*'
MYSQL;
    return $query;
}

if (isset($did[$_REQUEST['did']])) {
    $ext = $did[$_REQUEST['did']];
} else {
    echo 'nope';
}

$first = $db->query(generateQuery($ext))->fetch();
$second = $db->query(generateQuery('999' . $ext))->fetch();
$third = $db->query(generateQuery('888' . $ext))->fetch();

//if (!$first) {
//    echo $ext;
//    exit;
//}
if (!$second) {
    echo '999' . $ext;
    exit;
}
if (!$third) {
    echo '888' . $ext;
    exit;
}