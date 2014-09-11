<?php
	
	/**
	 * Return a strong one-way encrypted password with salt.
	 * @param  string  $input  The password to encrypt
	 * @param  integer $rounds The lenght of the salt
	 * @return string          The Encrypted password
	 */
	function better_crypt($input, $rounds = 7)
	{
		// Original PHP code by Chirp Internet: www.chirp.com.au
  		// Please acknowledge use of this code by including this header.
		$salt = "";
		$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
		for($i=0; $i < 22; $i++) {
		  $salt .= $salt_chars[array_rand($salt_chars)];
		}
		//Result : $2a$07$vY6x3F45HQSAiOs6N5wMuOwZQ7pUPoSUTBkU/DEF/YNQ2uOZflMIa
		return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
	}

	/**
	 * Check the user's role
	 * @param  integer $role the role to check - 1 is Super moderator
	 * @return bool        	 true if user has rights
	 */
	function check_min_role($role=2)
	{
		if(isset($_SESSION['user']) and $_SESSION['user']['rolle_id'] <= $role)
		{
			return true;
		}
		return false;
	}
	/**
	 * Redirect the user to a given url
	 * @param  string $url The url to go to
	 * @return 302      Redirect
	 */
	function redirect_to($url)
	{
		header('location:'.$url);
	}

	/**
	 * Redirect the user if not administrator
	 * @return bool 	true if user has the the nessecasry rights
	 */
	function redirect_if_user()
	{
		if(!check_min_role())
		{
			redirect_to(BASE_URL);
		}
		return true;
	}

	/**
	 * Show a single flash message and remove it afterwards
	 * Returns false if message doesn't exist
	 * @param  string $message_name Name og the message to display
	 * @return string 				Html message to display
	 */
	function show_message($type)
	{
		if(!isset($_SESSION['message'][$type])) return false;
		$html = '';
		foreach($_SESSION['message'][$type] as $message)
			$html .= '<div class="alert alert-warning" role="alert">'.$message.'</div>';
		unset($_SESSION['message'][$type]);
		return $html;
	}
	/**
	 * Show all flash message and remove them afterwards
	 * Returns false if message doesn't exist
	 * @return string 				Html message to display
	 */
	function show_messages()
	{
		$html = '';
		if(!isset($_SESSION['message'])) return false;
		  foreach($_SESSION['message'] as $type=>$messages)
		  {
	  		foreach($messages as $message)
				$html .= '<div class="alert alert-warning" role="alert"><strong>'.$type.'</strong> '.$message.'</div>';
		  }
		unset($_SESSION['message']);
	  	return $html;
	}

	/**
	 * Set a flash message
	 * @param string $type    Title for the message
	 * @param string $message Message to display
	 */
	function set_message($type, $message)
	{
		$_SESSION['message'][$type][] = $message;
	}

	/**
	 * Set the requested page or default to home
	 * @param string $page the page to display
	 */
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

			case 'articles':
				require_once 'inc/_articles.php';
				break;
			
			default: 
				//Todo : Set to 404 if page don't exist
				require_once 'inc/_home.php';
				break;
		}
	}
?>