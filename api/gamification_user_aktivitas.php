<?php

function gamification_user_aktivitas_add($user_id,$aktivitas_id,$poin){
	require "include_conn.php";
	
	$sql = "INSERT INTO `user_aktivitas` (user_id,aktivitas_id,poin)
			VALUES ('$user_id','$aktivitas_id','$poin')";
	mysqli_query($con,$sql);
	
	require "include_close.php";
}

function gamification_user_aktivitas_cek_poin($user_id){
	require "include_conn.php";
	
	$sql = "SELECT sum(poin) as sum_poin
			FROM user_aktivitas 
			WHERE user_id = '$user_id'";
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
	
	require "include_close.php";
	
	return $row['sum_poin'];
}

function gamification_user_aktivitas_cek_poin_detail($user_id,$aktivitas_id){
	require "include_conn.php";
	
	$sql = "SELECT sum(poin) as sum_poin
			FROM user_aktivitas 
			WHERE user_id = '$user_id'
			AND aktivitas_id = '$aktivitas_id'";
	$res = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($res);
	
	require "include_close.php";
	
	return $row['sum_poin'];
}