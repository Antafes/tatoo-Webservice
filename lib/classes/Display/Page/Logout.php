<?php
namespace Display\Page;

/**
 * Description of EsLogout
 *
 * @author Neithan
 */
class Logout extends \Display\Page
{
	public function __construct()
	{
	}

	public function process()
	{
		session_destroy();
		redirect('index.php?page=Login');
	}
}
