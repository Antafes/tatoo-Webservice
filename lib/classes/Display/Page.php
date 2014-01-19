<?php
namespace Display;

/**
 * Description of EsPage
 *
 * @author Neithan
 */
abstract class Page
{
	/**
	 * @var Template
	 */
	protected $template;

	/**
	 * @param string $template The template file
	 */
	function __construct($template)
	{
		$this->template = new \Display\Template();
		$this->template->setTemplate($template);
	}

	/**
	 * Render and output the template
	 */
	public function render()
	{
		$this->template->render();
	}

	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Process possibly entered data of the page.
	 */
	abstract public function process();
}
