<?php
header('Access-Control-Allow-Origin: *');
error_reporting(0);
require_once 'api/prepare.php';

$wrtc = new PDO('localhost', 'root');


$go = false;
$uuid = '';
if (isset($_REQUEST['ext']) && $_REQUEST['ext'] != '') {
    $uuid = $ext_uuid[$_REQUEST['ext']];
    $go = true;
}
if (isset($_REQUEST['uuid']) && $_REQUEST['uuid'] != '') {
    $uuid = $_REQUEST['uuid'];
    $go = true;
}

$go == true ?: die('No parameter specified');


switch ($_REQUEST['command']) {
    case 'hangup':
        $command = 'uuid_kill ' . $uuid;
        break;
    case 'bridge':
        if (!$_REQUEST['ext_bridge']) {
            echo 'Missing second extension!';
            exit;
        }
        $command = 'uuid_bridge ' . $uuid . ' ' . $ext_uuid[$_REQUEST['ext_bridge']];
        break;
    case 'hold':
        $command = 'uuid_hold ' . $uuid;
        break;
    case 'unhold':
        $command = 'uuid_hold off ' . $uuid;
        break;
    case 'autovm':
        $command = 'uuid_dual_transfer ' . $uuid . ' * 666' . $_REQUEST['file'];
        break;
    case 'transfer':
        if (isset($_REQUEST['transferto'])) {
            $command = 'uuid_dual_transfer ' . $uuid . ' - ' . $_REQUEST['transferto'];
            break;
        } else {
            echo 'Missing extension to transfer to!';
            exit;
            break;
        }
        break;
    case 'conference':
        if (isset($_REQUEST['transferto'])) {
            $command = 'uuid_dual_transfer ' . $uuid . ' 666 666';
            break;
        }
        break;
    case 'active':
        $_REQUEST['ext'] ?: die('No extension specified');
        $query = <<<MYSQL
SELECT COUNT(presence_id) active, uuid FROM `freeswitch`.`basic_calls` FROM `freeswitch`.`basic_calls` WHERE presence_id REGEXP '{$_REQUEST['ext']}'
MYSQL;
        echo json_encode($wrtc->query($query)->fetchAll(PDO::FETCH_ASSOC)[0]);
        break;

}
echo json_encode(array('response' => trim(event_socket_request($fp, $command))));