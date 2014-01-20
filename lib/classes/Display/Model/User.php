<?php
namespace Display\Model;

/**
 * Description of User
 *
 * @author Neithan
 */
class User
{
	/**
	 * @var integer
	 */
	protected $userId;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $salt;

	/**
	 * @var boolean
	 */
	protected $admin;

	/**
	 * @var integer
	 */
	protected $languageId;

	/**
	 * Get the user that wants to log in.
	 *
	 * @param string $name
	 * @param string $password
	 * @return boolean|\self
	 */
	public static function getUser($name, $password)
	{
		$sql = '
			SELECT *
			FROM users
			WHERE name = '.sqlval($name).'
				AND !deleted
		';
		$userData = query($sql);

		$encPassword = self::encryptPassword($password, $userData['salt']);

		if (strcasecmp($name, $userData['name']) === 0 && $encPassword == $userData['password'])
		{
			$object = new self();

			return self::fillUserData($object, $userData);
		}
		else
			return false;
	}

	/**
	 * Get the logged in user by userId.
	 *
	 * @param integer $userId
	 * @return \self
	 */
	public static function getUserById($userId)
	{
		$sql = '
			SELECT *
			FROM users
			WHERE userId = '.sqlval($userId).'
				AND !deleted
		';
		$userData = query($sql);

		$object = new self();

		return self::fillUserData($object, $userData);
	}

	/**
	 * Fill the user data from the database into the given User object.
	 *
	 * @param \self $object
	 * @param array $userData
	 * @return \self
	 */
	protected static function fillUserData($object, $userData)
	{
		$object->userId        = intval($userData['userId']);
		$object->name          = $userData['name'];
		$object->password      = $userData['password'];
		$object->password      = $userData['email'];
		$object->salt          = $userData['salt'];
		$object->admin         = !!$userData['admin'];
		$object->languageId    = intval($userData['languageId']);

		return $object;
	}

	/**
	 * Create a new user, save it into the database and send a mail to the user.
	 *
	 * @param string $name
	 * @param string $password
	 * @param string $email
	 * @return integer
	 */
	public static function createUser($name, $password, $email, $languageId)
	{
		$salt = uniqid();
		$sql = '
			INSERT INTO users
			SET name = '.sqlval($name).',
				password = '.sqlval(self::encryptPassword($password, $salt)).',
				email = '.sqlval($email).',
				salt = '.sqlval($salt).',
				languageId = '.sqlval($languageId).'
		';
		$id = query($sql);
		$user = self::getUserById($id);
		$translator = \Display\Translator::getInstance();

		$mailer = new \PHPMailer();
		$mailer->setFrom('noreply@wafriv.de', $translator->getTranslation('mailSender'));
		$mailer->addAddress($user->getEmail());
		$mailer->set('Subject', $translator->getTranslation('mailCreatedUser'));

		$template = new \Display\Template();
		$template->getTranslator()->setCurrentLanguage($user->getLanguageId(), false);
		$template->setTemplate('mails/createdUser.tpl');
		$template->assign('username', $user->getName());
		$template->assign('password', $password);
		$message = $template->render(true);

		$mailer->msgHTML($message);
		$mailer->send();

		// Reset the translator language to the current users language. Otherwise the translations
		// will get fucked up.
		$translator->setCurrentLanguage($_COOKIE['language']);

		return $user->getUserId();
	}

	/**
	 * Checks if the username is already in use or not.
	 *
	 * @param string $name
	 * @return integer Returns 0 if the username is not in use, otherwise 1
	 */
	public static function checkUsername($name)
	{
		$sql = '
			SELECT COUNT(*)
			FROM users
			WHERE name = '.sqlval($name).'
				AND !deleted
		';
		return query($sql);
	}

	/**
	 * Encrypt the user password and a salt with md5.
	 *
	 * @param string $password
	 * @param string $salt
	 * @return string
	 */
	protected static function encryptPassword($password, $salt)
	{
		return md5($password.'-'.$salt);
	}

	/**
	 * Creates a random password with a-z, A-Z, 0-1 and some special characters.
	 * The default password length is 8 characters.
	 *
	 * @param integer $length
	 * @return string
	 */
	public static function createPassword($length = 8)
	{
		// Found on http://stackoverflow.com/a/1837443
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"§$%&/()=?';
		$count = mb_strlen($chars);

		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}

		return $result;
	}

	/**
	 * Delete the user with the given user id.
	 *
	 * @param integer $userId
	 * @return integer
	 */
	public static function deleteUser($userId)
	{
		$sql = '
			UPDATE users
			SET deleted = 1
			WHERE `userId` = '.sqlval($userId).'
		';
		return query($sql);
	}

	/**
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @return boolean
	 */
	public function getAdmin()
	{
		return $this->admin;
	}

	/**
	 * @return integer
	 */
	public function getLanguageId()
	{
		return $this->languageId;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$sql = '
			UPDATE users
			SET name = '.sqlval($this->name).'
			WHERE userId = '.sqlval($this->userId).'
		';
		query($sql);
	}

	/**
	 * Encrypts and sets the password.
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = self::encryptPassword($password, $this->salt);
		$sql = '
			UPDATE users
			SET password = '.sqlval($this->password).'
			WHERE userId = '.sqlval($this->userId).'
		';
		query($sql);
	}

	/**
	 * @param boolean $admin
	 */
	public function setAdmin($admin)
	{
		$this->admin = $admin;
		$sql = '
			UPDATE users
			SET admin = '.sqlval($admin ? 1 : 0).'
			WHERE userId = '.sqlval($this->userId).'
		';
		query($sql);
	}

	/**
	 * @param integer $languageId
	 */
	public function setLanguageId($languageId)
	{
		$this->languageId = $languageId;
		$sql = '
			UPDATE users
			SET `languageId` = '.sqlval($languageId).'
			WHERE `userId` = '.sqlval($this->userId).'
		';
		query($sql);
	}
}
