<?php
session_start();

$megabyte = 8000000;

function isValidFileType($type){
	$type = strtolower($type);
	$types = array('jpeg', 'jpg', 'png');
	for($i = 0; $i < count($types); $i++){
		if($type === $types[$i]){
			return true;
		}
	}
	return false;
}

if(isset($_POST["submit"])) {
	
	$valid = true;
	$id = uniqid();
	$type = strtolower(pathinfo(basename($_FILES["upload"]["name"]), PATHINFO_EXTENSION));
	$uploadFileDest = "img/pending/t6e/$id.$type";
	$error = '';
	
    if(getimagesize($_FILES["upload"]["tmp_name"]) === false) {
        $error .= "Main image not an image, ";
        $valid = false;
    } elseif(!isValidFileType($type)){
		$error .= "Not a valid filetype, ";
        $valid = false;
	} elseif ($_FILES["upload"]["size"] > 10 * $megabyte) {
		$error .= "File larger than 10 MB, ";
		$valid = false;
	}
	
	if($valid){
		$_SESSION['activeTemplate'] = "$id.$type";
		$_SESSION['activeCode'] = $id;
	}
	
	$type = strtolower(pathinfo(basename($_FILES["overlay"]["name"]), PATHINFO_EXTENSION));
	$overlayFileDest = "img/pending/t6e/$id-overlay.$type";
	
	if($_FILES["overlay"]["tmp_name"] == ''){
		//$valid = false;
	}elseif(getimagesize($_FILES["overlay"]["tmp_name"]) === false) {
        $error .= "Overlay not an image, ";
        $valid = false;
    } elseif($type != 'png'){
		$error .= "Overlay not a valid filetype, ";
        $valid = false;
	} elseif ($_FILES["upload"]["size"] > 10 * $megabyte) {
		$error .= "Overlay larger than 10 MB, ";
		$valid = false;
	}
	
	if($valid){
		move_uploaded_file($_FILES["upload"]["tmp_name"], $uploadFileDest);
		move_uploaded_file($_FILES["overlay"]["tmp_name"], $overlayFileDest);
		header('Location: designer.php');
	} else{
		header('Location: submit.php?e='.urlencode(substr($error, 0, strlen($error) - 2)));
	}
	
}
?>