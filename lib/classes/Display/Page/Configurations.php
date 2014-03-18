<?php
namespace Display\Page;
/**
 * Description of Admin
 *
 * @author Neithan
 */
class Configurations extends \Display\Page
{
	public function __construct()
	{
		parent::__construct('configurations');
	}

	public function process()
	{
		if ($_POST['createConfiguration'])
			$this->createConfiguration(
				$_POST['createConfiguration'], $_POST['configuration'], $_POST['configValue']
			);
		elseif ($_POST['updateConfiguration'])
			$this->updateConfiguration(
				$_POST['updateConfiguration'], $_POST['id'], $_POST['configuration'], $_POST['configValue']
			);
		elseif ($_GET['delete'])
		{
			\Display\Model\Configuration::delete($_GET['delete']);
			redirect('index.php?page=Configurations');
		}

		if ($_GET['action'] == 'create')
			$this->createAction($_GET['edit']);
		else
		{
			$configurationList = new \Display\Model\ConfigurationList();
			$this->template->assign('configurations', $configurationList->getConfigurations());
		}
	}

	private function createAction($id)
	{
		if ($id)
			$configuration = \Display\Model\Configuration::loadById($id);
		else
			$configuration = new \Display\Model\Configuration ('new', '', '');

		$this->template->assign('configuration', $configuration);
	}

	private function createConfiguration($salt, $key, $value)
	{
		if (!$salt || $salt != $_SESSION['formSalts']['createConfiguration'])
			return;

		if (!$key || !$value)
		{
			$this->template->assign('error', 'configurationEmpty');
			return;
		}

		if (\Display\Model\Configuration::create($key, $value))
			$this->template->assign('message', 'createConfigurationSuccessful');
		else
			$this->template->assign('error', 'createConfigurationUnsuccessful');
	}

	private function updateConfiguration($salt, $id, $key, $value)
	{
		if (!$salt || $salt != $_SESSION['formSalts']['updateConfiguration'])
			return;

		if (!$key || !$value)
		{
			$this->template->assign('error', 'configurationEmpty');
			return;
		}

		$configuration = \Display\Model\Configuration::loadById($id);

		if ($configuration->getKey() === $key && $configuration->getValue() === $value)
			return;

		if ($configuration->update($key, $value))
			$this->template->assign('message', 'updateConfigurationSuccessful');
		else
			$this->template->assign('error', 'updateConfigurationUnsuccessful');
	}
}