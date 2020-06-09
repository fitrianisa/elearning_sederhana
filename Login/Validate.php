<?php
	function validateNomer(&$errors, $field_list, $field_name)
	{
		$numeric = "/^[0-9]+$/";
		$panjang = "/^[0-9]{12}$/";
		if (!preg_match($numeric, $field_list[$field_name])){
			$errors[$field_name] = 'Phone number must contain numeric only';
		}
		else if (!preg_match($panjang, $field_list[$field_name])){
			$errors[$field_name] = 'Phone number entered 12 digits long';
		}
		else{
			$errors[$field_name] = ' '; 
		}
	}
	function validateEmail(&$errors, $field_list, $field_name)
	{
		$mail = "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/";
		if (!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Field is required'; 
		}
		else if (!preg_match($mail, $field_list[$field_name])){
			$errors[$field_name] = 'Invalid email address';
		}
		else{
			$errors[$field_name] = ' ';
		}
	}
	function validatePassword(&$errors, $field_list, $field_name)
	{
		$p = "/^[a-z]+[0-9]+$/";
		$password = "/^[a-z0-9]{8,15}$/";
		if (!isset($field_list[$field_name]) || empty($field_list[$field_name])){    
			$errors[$field_name] = 'Field is required'; 
		}
		else if (!preg_match($p, $field_list[$field_name])){
			if ($field_name == 'password_baru'){
				$errors[$field_name] = 'Password must combine alphanumeric';
			}
		}
		if (preg_match($p, $field_list[$field_name])){
			if (!preg_match($password, $field_list[$field_name])){
				if ($field_name == 'password_baru'){
					$errors[$field_name] = 'Password entered min 8 digits long';
				}
			}
			if (preg_match($password, $field_list[$field_name])){
				if ($field_name == 'password_baru'){
					$errors[$field_name] = ' '; 
				}
			}
		}
		if (!isset($field_list[$field_name]) || empty($field_list[$field_name])){
			$errors[$field_name] = 'Field is required'; 
		}
		else if ($field_list['password_baru'] == $field_list['confirm_password']){
			if ($field_name == 'confirm_password'){
				$errors[$field_name] = ' ';
			}
		}
		if ($field_list['password_baru'] != $field_list['confirm_password']){
			if ($field_name == 'confirm_password'){
				$errors[$field_name] = 'Passwords do not match';
			}
		}
	}
?>
