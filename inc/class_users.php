<?php
/**
 * Author: Nayar(njoolfoo@gmail.com)
 */

class Users {
	
	public $current = array();
	
	function __construct() {
		global $db,$_SESSION;
		if(isset($_SESSION['user_id'])) {
			$result = $db->query("SELECT * FROM users WHERE user_id = '{$_SESSION['user_id']}'");
			$user = mysql_fetch_array($result);
			$this->current = $user;
		}
		else {
			$this->current['user_id'] = 0;
			$this->current['is_admin'] = 0;
		}
	}
	
	function register($newuser) {
		global $db;
		$allowreg = true;
		$results = $db->query("SELECT count(*) c FROM users WHERE username = '{$newuser['username']}' OR email = '{$newuser['email']}'");
		
		while ($result = mysql_fetch_array($results)) {
			if($result['c']>0) {
				$allowreg = false;
			}
		}
		
		if ($allowreg) {
			$newuser['password'] = md5($newuser['password']);
			$db->insert("users",$newuser);
			return true;
		}
		else {
			return false;
		}
	}
	
	function login($username,$password) {
		global $db,$_SESSION;
		$password = md5($password);
		$result = $db->query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'");
		$user = mysql_fetch_array($result);
		if(!$user) {
			return false;
		}
		else {
			$_SESSION['user_id'] = $user['user_id'];
			return $user['user_id'];
		}
	}
	
	function logout() {
		global $_SESSION;
		unset($_SESSION['user_id']);
	}
}
?>