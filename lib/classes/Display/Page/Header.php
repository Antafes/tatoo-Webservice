<?php
namespace Display\Page;

/**
 * Description of EsHeader
 *
 * @author Neithan
 */
class Header extends \Display\Page
{
	/**
	 * @param \Display\Template $template
	 */
	public function __construct($template)
	{
		$this->template = $template;
	}

	public function process()
	{
		// Add CSS files
		$this->template->loadCss('header');
		$this->template->loadCss('common');
		$this->template->loadCss('jquery-ui-1.10.3.custom');

		// Add JS files
		$this->template->loadJs('jquery-2.0.3');
		$this->template->loadJs('jquery-ui-1.10.3.custom');
		$this->template->loadJs('header');

		$this->createMenu();
		$translator = \Display\Translator::getInstance();
		$language = \Display\Model\Language::getLanguageById($translator->getCurrentLanguage());
		$this->template->assign('languageCode', $language->getIso2code());
	}

	protected function createMenu()
	{
		if ($_SESSION['userId'])
		{
			$user = \Display\Model\User::getUserById($_SESSION['userId']);
			$this->template->assign('isAdmin', $user->getAdmin());
		}
	}
}
