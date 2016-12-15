<?php

function gamification_aktivitas_list(){
	require "include_conn.php";
		
	$sql = "SELECT * FROM aktivitas";
	$res = mysqli_query($con,$sql);
	
	$list = array();
	
	while($row = mysqli_fetch_assoc($res)){
		
		$list[] = $row;
		
	}
			
	
	require "include_close.php";
	
	return $list;
}

function gamification_aktivitas_add($nama,$poin){
	
	require "include_conn.php";
	
	$sql = "SELECT COUNT(*) as jml FROM `aktivitas` 
			WHERE nama = '$nama'";
			
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
	
	if($row['jml'] == 0){
		$sql = "INSERT INTO `aktivitas` (nama,poin)
				VALUES ('$nama','$poin')";
		mysqli_query($con,$sql);
	}
	
	$sql = "SELECT aktivitas_id FROM `aktivitas` 
			WHERE nama = '$nama'";
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
			
	
	require "include_close.php";
	
	return $row['aktivitas_id'];
	
}

function gamification_aktivitas_delete($aktivitas_id){
	require "include_conn.php";
	
	
	$sql = "DELETE FROM `aktivitas` WHERE aktivitas_id = '$aktivitas_id'";
	mysqli_query($con,$sql);
	
	
	require "include_close.php";
}