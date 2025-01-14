<?php
require_once('../include/db_info.inc.php');

if (
	!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])
	|| !isset($_SESSION[$OJ_NAME . '_' . 'uploadkey'])
	|| !isset($_POST['uploadkey'])
	|| $_SESSION[$OJ_NAME . '_' . 'uploadkey'] != $_POST['uploadkey']
)
	exit(1);

$imageFolder = "../upload/files/";
if (!file_exists($imageFolder)) {
	mkdir($imageFolder, 0755);
}

reset($_FILES);
$temp = current($_FILES);
if (!is_uploaded_file($temp['tmp_name'])) {
	header("HTTP/1.1 500 Server Error");
	exit;
}

if (in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("php"))) {
	header("HTTP/1.1 400 Invalid extension.");
	exit;
}

$file = explode(".", $temp['name']);
$suffix = array_pop($file);
$new_name = md5_file($temp['tmp_name']) . "." . $suffix;
$save_path = $imageFolder . date("Ymd") . "/";
if (!file_exists($save_path)) {
	mkdir($save_path, 0755);
}
$filetowrite = $save_path . $new_name;
move_uploaded_file($temp['tmp_name'], $filetowrite);
chmod($filetowrite, 0644);

echo json_encode(array('location' => "/upload/files/" . date("Ymd") . "/" . $new_name));
