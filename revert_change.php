<?php
session_start();

if (isset($_SESSION['session_expire'])) {
    if (time() - $_SESSION['session_expire'] > (60 * 5)) {
        session_destroy();
    } else {
        $_SESSION['session_expire'] = time();
    }
}

?><h5><?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
?></h5><?php
include_once "classes/Page.php";
include_once "classes/Db.php";
include_once "classes/Pdo.php";
include_once "classes/Filter.php";
//require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
Page::display_header("Edit message");

if (!isset($_POST['activity_id'])) {
    header("Location: activity.php");
}

if (empty($_SESSION['permissions'][21]) && $message_author != $_SESSION['login']) {
    die;
}


$pdo = new Pdo_();

$activity = $pdo->get_single_activity($_POST['activity_id']);

if (empty($activity)) {
    header("Location: activity.php");
}

$previous_content = explode("|", $activity[0]['previous_data']);

$message_id = Filter::sanitizeData($activity[0]['row_number'], 'num');


$message_title = Filter::sanitizeData($previous_content[1], 'str');
$message_type = Filter::sanitizeData($previous_content[2], 'str');
$message_content = Filter::sanitizeData($previous_content[3], 'str');


$db = new Db("localhost", "news", "Emily", "haslo");
$res = $db->getSingleMessage($message_id);
$msg = $res->fetch(PDO::FETCH_ASSOC);

$db->updateMessage($message_id, $message_title, $message_type, $message_content);

$prev_data = $message_id."|".$msg['name']."|". $msg['type']."|". $msg['message'];
$present_data = $message_id."|".$message_title."|". $message_type."|". $message_content;
$pdo->register_user_activity('edit_msg',$message_id,$prev_data, $present_data, 'mesage');
   

header("Location: messages.php");
exit();