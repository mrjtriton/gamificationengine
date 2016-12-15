<?php

function gamification_badge_list(){
	require "include_conn.php";
		
	$sql = "SELECT * FROM badge";
	$res = mysqli_query($con,$sql);
	
	$list = array();
	
	while($row = mysqli_fetch_assoc($res)){
		
		$list[] = $row;
		
	}
			
	
	require "include_close.php";
	
	return $list;
}

function gamification_badge_add($nama,$logo){
	
	require "include_conn.php";
	
	$sql = "SELECT COUNT(*) as jml FROM `badge` 
			WHERE nama = '$nama'";
			
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
	
	if($row['jml'] == 0){
		$sql = "INSERT INTO `badge` (nama,logo)
				VALUES ('$nama','$logo')";
		mysqli_query($con,$sql);
	}
	
	$sql = "SELECT badge_id FROM `badge` 
			WHERE nama = '$nama'";
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
			
	
	require "include_close.php";
	
	return $row['badge_id'];
	
}

function gamification_badge_delete($badge_id){
	require "include_conn.php";
	
	
	$sql = "DELETE FROM `badge` WHERE badge_id = '$badge_id'";
	mysqli_query($con,$sql);
	
	
	require "include_close.php";
}