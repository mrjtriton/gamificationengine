<?php

function gamification_user_get_user_id($user_id_external){
	require "include_conn.php";
	
	$sql = "SELECT COUNT(*) as jml FROM `user` 
			WHERE user_id_external = '$user_id_external'";
			
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
	
	if($row['jml'] == 0){
		$sql = "INSERT INTO `user` (user_id_external)
				VALUES ('$user_id_external')";
		mysqli_query($con,$sql);
	}
	
	$sql = "SELECT user_id FROM `user` 
			WHERE user_id_external = '$user_id_external'";
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
			
	
	require "include_close.php";
	
	return $row['user_id'];
}
