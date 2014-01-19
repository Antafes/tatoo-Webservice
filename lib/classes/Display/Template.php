<?php
namespace Display;

/**
 * Description of Template
 *
 * @author Neithan
 */
class Template
{
	/**
	 * @var Smarty
	 */
	protected $smarty;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * Creates an instance of Smarty and a EsTranslator.
	 * Also sets the template directory for Smarty.
	 */
	function __construct()
	{
		$this->smarty = new \Smarty();
		$this->smarty->setTemplateDir('templates/');

		$translator = Translator::getInstance();
		$this->translator = $translator;
		$this->smarty->assign('translator', $translator);
		$this->smarty->assign('languages', $this->translator->getAllLanguages());
		$this->smarty->assign('currentLanguage', $this->translator->getCurrentLanguage());
	}

	/**
	 * Get the objects smarty instance.
	 *
	 * @return Smarty
	 */
	public function getSmarty()
	{
		return $this->smarty;
	}

	/**
	 * Set the template to use.
	 *
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	/**
	 * Assign a value with a name to the smarty instance.
	 * All values are pre processed by the translator.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function assign($name, $value)
	{
		$this->smarty->assign($name, $this->translator->getTranslation($value));
	}

	/**
	 * Render the defined template
	 */
	public function render()
	{
		echo $this->smarty->display($this->template.'.tpl');
		unset($_SESSION['scripts']);
	}

	/**
	 * @return Translator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}

	/**
	 * load a js file in smarty
	 * use only the filename without ending
	 * @param String $file
	 */
	public function loadJs($file)
	{
		if (!is_array($_SESSION['scripts']['file']))
			$_SESSION['scripts']['file'] = array();

		if (!in_array($file, $_SESSION['scripts']['file']))
			$_SESSION['scripts']['file'][] = $file;
	}

    /**
	 * load a js script in smarty
	 * @param String $script contains only the js!
	 */
	public function loadJsScript($script)
	{
		$_SESSION['scripts']['script'][] = $script;
	}

	/**
	 * load a js ready script in smarty
	 * @param String $script contains only the js!
	 */
	public function loadJsReadyScript($script)
	{
		$_SESSION['scripts']['ready_script'][] = $script;
	}

	/**
	 * load a css file in smarty
	 * use only the filename without ending
	 * @param String $file
	 */
	public function loadCss($file)
	{
		if (!is_array($_SESSION['css']['file']))
			$_SESSION['css']['file'] = array();

		if (!in_array($file, $_SESSION['css']['file']))
			$_SESSION['css']['file'][] = $file;
	}
}
