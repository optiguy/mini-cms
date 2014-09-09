<?php
	function check_min_role($role=2)
	{
		if(isset($_SESSION['user']) and $_SESSION['user']['rolle_id'] <= $role)
		{
			return true;
		}
		return false;
	}

	function redirect_to($url)
	{
		header('location:'.$url);
	}

	function redirect_if_user()
	{
		if(!check_min_role())
		{
			header('location:'.BASE_URL);
		}
	}

	function show_message($message_name)
	{
		if(!isset($_SESSION['message'][$message_name])) return false;
		$html = '<div class="alert alert-warning" role="alert">'.$_SESSION['message'][$message_name].'</div>';
		unset($_SESSION['message'][$message_name]);
		return $html;
	}

	function show_messages()
	{
		$html = '';
		if(!isset($_SESSION['message'])) return false;
		  foreach($_SESSION['message'] as $type=>$message)
		  {
			$html .= '<div class="alert alert-warning" role="alert"><strong>'.$type.'</strong> '.$message.'</div>';
		  }
		unset($_SESSION['message']);
	  	return $html;
	}

	function set_message($type, $message)
	{
		$_SESSION['message'][$type] = $message;
	}

	function set_page($page)
	{
		switch ($page) {
			case 'contact':
				require_once 'inc/_contact.php';
				break;

			case 'dashboard':
				require_once 'inc/admin/_dashboard.php';
				break;

			case 'admin_user':
				require_once 'inc/admin/_admin_user.php';
				break;
			
			default:
				require_once 'inc/_home.php';
				break;
		}
	}
?>