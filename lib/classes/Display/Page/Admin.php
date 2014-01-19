<?php
namespace Display\Page;
/**
 * Description of Admin
 *
 * @author Neithan
 */
class Admin extends \Display\Page
{
	public function __construct()
	{
		parent::__construct('admin');
	}

	public function process()
	{
		// Check if the user has admin access
		$user = \Display\Model\User::getUserById($_SESSION['userId']);

		if (!$user->getAdmin())
			redirect('index.php?page=Index');

		if ($_GET['setAdmin'])
		{
			$this->changeAdminStatus(intval($_GET['setAdmin']), true);
			redirect('index.php?page=Admin&action=list');
		}

		if ($_GET['removeAdmin'])
		{
			$this->changeAdminStatus(intval($_GET['removeAdmin']), false);
			redirect('index.php?page=Admin&action=list');
		}

		if ($_GET['delete'])
		{
			$this->deleteUser(intval($_GET['delete']));
			redirect('index.php?page=Admin&action=list');
		}

		if ($_POST['createUser'])
		{
			$this->createUser($_POST['createUser'], $_POST['username'], $_POST['email']);
		}

		$this->template->assign('userList', $this->getUserList());
	}

	/**
	 * Get a list with all users that are not deleted.
	 *
	 * @return array
	 */
	protected function getUserList()
	{
		$sql = '
			SELECT userId
			FROM users
			WHERE !deleted
		';
		$users = query($sql, true);

		$userList = array();
		foreach ($users as $user)
			$userList[] = \Display\Model\User::getUserById($user['userId']);

		return $userList;
	}

	/**
	 * Set the admin status of the given user to $status.
	 *
	 * @param integer $userId
	 * @param boolean $status
	 */
	protected function changeAdminStatus($userId, $status)
	{
		$user = \Display\Model\User::getUserById($userId);
		$user->setAdmin($status);
	}

	/**
	 * Create a new user and send him an email with the generated password and the username.
	 *
	 * @param string $salt
	 * @param string $username
	 * @param string $email
	 */
	protected function createUser($salt, $username, $email)
	{
		if (!$salt || $salt != $_SESSION['formSalts']['createUser'])
			return;

		if (!$username || !$email)
		{
			$this->template->assign('error', 'registerEmpty');
			return;
		}

		if (\Display\Model\User::checkUsername($username))
		{
			$this->template->assign('error', 'usernameAlreadyInUse');
			return;
		}

		$password = \Display\Model\User::createPassword(12);

		if (\Display\Model\User::createUser($username, $password, $email))
		{
			$this->template->assign('message', 'registrationSuccessful');
			$_GET['action'] = 'list';
		}
		else
			$this->template->assign('error', 'registrationUnsuccessful');
	}

	/**
	 * Delete the user with the given user id.
	 *
	 * @param integer $userId
	 */
	protected function deleteUser($userId)
	{
		\Display\Model\User::deleteUser($userId);
	}
}
