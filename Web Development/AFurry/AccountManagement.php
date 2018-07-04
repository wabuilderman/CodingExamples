<?php
	session_start();

	function generateSalt($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++)
		{
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function createAccount($username,$password)
	{
		$filename = 'AccountData.data';
		$handle = fopen($filename, 'a')
					or die('Cannot open file: '.$filename);
		$salt = generateSalt(20);
		$data = $username . ' ' . hash("md5",hash("md5",$password) . $salt) . ' ' . $salt . "\n";
		fwrite($handle, $data);
		fclose($handle);
	}
	function checkForAccount($username)
	{
		$filename = 'AccountData.data';
		$handle = fopen($filename, 'r')
					or die('Cannot open file: '.$filename);
		while (($line = fgets($handle)) !== false)
		{
			$i = 0;
			$length = strlen($line);
			$testUser = '';
			while ($i < $length)
			{
				if($line[$i] == ' '){break;}
				$testUser .= $line[$i];
				$i++;
			}
			if($testUser == $username)
			{
				fclose($handle);
				return true;
			}
		}
		fclose($handle);
		return false;
	}

	function login($username, $password)
	{
		$filename = 'AccountData.data';
		$handle = fopen($filename, 'r')
					or die('Cannot open file: '.$filename);
		while (($line = fgets($handle)) !== false)
		{
			$i = 0;
			$length = strlen($line) - 1;
			$testUser = '';
			while ($i < $length)
			{
				if($line[$i] == ' '){$i++;break;}
				$testUser .= $line[$i];
				$i++;
			}
			if($testUser == $username)
			{
				$testPassword = '';
				$testSalt = '';
				while ($i < $length)
				{
					if($line[$i] == ' '){$i++;break;}
					$testPassword .= $line[$i];
					$i++;
				}
				while ($i < $length)
				{
					$testSalt .= $line[$i];
					$i++;
				}

				if($testPassword == hash("md5",hash("md5",$password) . $testSalt))
				{
					fclose($handle);
					return true;
				}
				fclose($handle);
				return false;
			}
		}
		fclose($handle);
		return false;
	}

	function exitToHome($username)
	{
		$_SESSION['username'] = $username;
		header("location: http://".$_SERVER['HTTP_HOST']);
		exit();
	}

	if(isset($_POST['logout']))
	{
		exitToHome("");
	}

	if(isset($_POST['Username']) &&
	   isset($_POST['Password']))
	{
		if(isset($_POST['Password2']))
		{
			if($_POST['Password'] != $_POST['Password2'])
			{
				exitToHome("");
			}
			if(checkForAccount($_POST['Username']))
			{
				exitToHome("");
			}
			createAccount($_POST['Username'],$_POST['Password']);
			exitToHome($_POST['Username']);
		}
		else
		{
			if(login($_POST['Username'],$_POST['Password']))
			{
				exitToHome($_POST['Username']);
			}
			exitToHome("");
		}
	}

	exitToHome("");
?>
