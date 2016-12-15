<?php

function gamification_leaderboard_show(){
	require "include_conn.php";
		
	$sql = "SELECT * FROM v_leaderboard";
	$res = mysqli_query($con,$sql);
	
	$list = array();
	
	while($row = mysqli_fetch_assoc($res)){
		
		$list[] = $row;
		
	}
			
	
	require "include_close.php";
	
	return $list;
}