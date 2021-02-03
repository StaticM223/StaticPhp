<?php 


function q($sql,$arg,$fetch = false){
	require "config.php";
	$q = $db->prepare($sql);
	$q->execute($arg);
	return $fetch ? $q->fetch(2) : $q;
}
class Main{
/////////////////////////////////////////////////////////////////////
	function EXIST($user){
		return q("SELECT username FROM users WHERE username = ?",array($user),true);
	}
	function PASSWORD($user){
		return q("SELECT password FROM users WHERE username = ?",array($user),true)['password'];
	}
	function BANNED($user){
		if(q("SELECT banned FROM users WHERE username = ?",array($user),true)['banned'] != "0")
			return false;
		return true;
	}
	function PREMIUM($user){
		if(q("SELECT user_type FROM users WHERE username = ?",array($user),true)['user_type'] == "1")
			return true;
		if(q("SELECT user_type FROM users WHERE username = ?",array($user),true)['user_type'] == "2")
			return true;
		return false;
	}
	function HWID($user,$hwid){
		if(q("SELECT hwid FROM users WHERE username = ?",array($user),true)['hwid'] == ""){
			q("UPDATE users SET hwid = ? WHERE username = ?",array($hwid,$user),true);
			return true;
		}
		if(q("SELECT hwid FROM users WHERE username = ?",array($user),true)['hwid'] != $hwid)
			return false;
		return true;
	}
	function DATE($user){
		return q("SELECT expiration FROM users WHERE username = ?",array($user),true)['expiration'];
	}
	function UID($user){
		return q("SELECT uid FROM users WHERE username = ?",array($user),true)['uid'];
	}
	function EXPIRATION($user){
		if(strtotime(q("SELECT expiration FROM users WHERE username = ?",array($user),true)['expiration']) < strtotime(date('Y/m/d H:i:s')))
			return false;
		return true;
	}
	function ADM($user){
		if(q("SELECT user_type FROM users WHERE username = ?",array($user),true)['user_type'] == "2")
			return true;

		return false;
	}
	function DAYS_LEFT($user){
		$future = date('Y/m/d H:i:s');
		$timefromdb = q("SELECT expiration FROM users WHERE username = ?",array($user),true)['expiration'];
		$timeleft = strtotime($timefromdb)-strtotime($future);
		$daysleft = round((($timeleft/24)/60)/60); 
		return $daysleft;
	}

//////////////////////////////// MAIN /////////////////////////////////////
	function LOGIN($user,$pass,$hwid){
		if(!Main::EXIST($user)) 
			return json_encode(array('user' => $user,'response' => '400'));
		if(!password_verify($pass, Main::PASSWORD($user)))
			return json_encode(array('user' => $user,'response' => '401'));
		if(!Main::BANNED($user)) 
			return json_encode(array('user' => $user,'response' => '402'));
		if(!Main::PREMIUM($user)) 
			return json_encode(array('user' => $user,'response' => '403'));
		if(!Main::HWID($user,$hwid)) 
			return json_encode(array('user' => $user,'response' => '405'));
		if(!Main::EXPIRATION($user)) 
			return json_encode(array('user' => $user,'response' => '406'));
		
		
		return json_encode(array('user' => $user,'response' => '1','date' => Main::DATE($user),'uid' => Main::UID($user),'remain_days' => Main::DAYS_LEFT($user)));
	}

	function ADMIN($user,$pass){
		if(!Main::EXIST($user)) 
			return json_encode(array('user' => $user,'response' => '400'));
		if(!password_verify($pass, Main::PASSWORD($user)))
			return json_encode(array('user' => $user,'response' => '401'));
		if(!Main::BANNED($user)) 
			return json_encode(array('user' => $user,'response' => '402'));
		if(!Main::PREMIUM($user)) 
			return json_encode(array('user' => $user,'response' => '403'));
		if(!Main::EXPIRATION($user)) 
			return json_encode(array('user' => $user,'response' => '406'));
		if(!Main::ADM($user)) 
			return json_encode(array('user' => $user,'response' => '407'));
		
		
		return json_encode(array('user' => $user,'response' => '3','date' => Main::DATE($user),'uid' => Main::UID($user)));
	}

	function REGISTER($user,$pass,$hwid){
		if(Main::EXIST($user)) 
			return json_encode(array('user' => $user,'response' => '407'));
		$pass2 = password_hash($pass, PASSWORD_BCRYPT,['cost' => '12',]);
		q("INSERT INTO users(username,password,hwid,expiration) VALUES(?,?,?,?)",array($user,$pass2,$hwid,"0000/00/00 00:00:00"),true);
		return json_encode(array('user' => $user,'response' => '4'));
	}

	function UPDATE($user,$ban,$hwid,$date,$type){
		if(!Main::EXIST($user)) 
			return json_encode(array('user' => $user,'response' => '408'));
		if($ban == "true")
			q("UPDATE users SET banned = '1' WHERE username = ?",array($user),true);
		else
			q("UPDATE users SET banned = '0' WHERE username = ?",array($user),true);
		if($hwid == "true")
			q("UPDATE users SET hwid = '' WHERE username = ?",array($user),true);
		q("UPDATE users SET expiration = ? WHERE username = ?",array($date,$user),true);
		q("UPDATE users SET user_type = ? WHERE username = ?",array($type,$user),true);

		
		return json_encode(array('user' => $user,'response' => '4'));
	}
/////////////////////////////////////////////////////////////////////
}