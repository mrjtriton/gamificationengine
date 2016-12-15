<?php


function gamification_user_badge_add($user_id,$badge_id){
	require "include_conn.php";
	
	$sql = "INSERT INTO `user_badge` (user_id,badge_id)
			VALUES ('$user_id','$badge_id')";
	mysqli_query($con,$sql);
	
	require "include_close.php";
}

function gamification_user_badge_list($user_id){
	require "include_conn.php";
		
	$sql = "SELECT * FROM user_badge WHERE user_id='$user_id'";
	$res = mysqli_query($con,$sql);
	
	$list = array();
	
	while($row = mysqli_fetch_assoc($res)){
		
		$list[] = $row;
		
	}
			
	
	require "include_close.php";
	
	return $list;
}