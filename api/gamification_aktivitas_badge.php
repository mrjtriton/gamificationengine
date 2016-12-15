<?php

function gamification_aktivitas_badge_get_min_poin($aktivitas_id,$badge_id){
	require "include_conn.php";
	
	
	$sql = "SELECT min_poin FROM `aktivitas_badge` 
			WHERE aktivitas_id = '$aktivitas_id' AND badge_id = '$badge_id'";
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
			
	
	require "include_close.php";
	
	return $row['min_poin'];
}

function gamification_aktivitas_badge_add($aktivitas_id,$badge_id,$min_poin){
	require "include_conn.php";
	
	$sql = "SELECT COUNT(*) as jml FROM `aktivitas_badge` 
			WHERE aktivitas_id = '$aktivitas_id' AND $badge_id = '$badge_id' ";
			
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
	
	if($row['jml'] == 0){
		$sql = "INSERT INTO `aktivitas_badge` (aktivitas_id,badge_id,min_poin)
				VALUES ('$aktivitas_id','$badge_id','$min_poin')";
		mysqli_query($con,$sql);
	}else{
		$sql = "UPDATE aktivitas_badge
				SET min_poin = '$min_poin'
				WHERE aktivitas_id = '$aktivitas_id'
				AND badge_id = '$badge_id'";
		mysqli_query($con,$sql);
	}
	require "include_close.php";
}

function gamification_aktivitas_badge_delete($aktivitas_id,$badge_id){
	require "include_conn.php";
	
	
	$sql = "DELETE FROM `aktivitas_badge` WHERE aktivitas_id = '$aktivitas_id' AND badge_id = '$badge_id'";
	mysqli_query($con,$sql);
	
	
	require "include_close.php";
}