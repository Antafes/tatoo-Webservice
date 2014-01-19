<?php
require_once(__DIR__.'/lib/config.default.php');

session_start();

$display = new \Display\Display();

$page = $_GET['page'];

if (!$page)
	$page = 'Index';

if ($_GET['language'])
{
	$translator = \Display\Translator::getInstance();
	$translator->setCurrentLanguage($_GET['language']);

	if ($_SESSION['userId'])
	{
		$user = \Display\Model\User::getUserById($_SESSION['userId']);
		$user->setLanguageId($_GET['language']);
	}

	redirect('index.php?page='.$page);
}

if (!$_SESSION['userId'] && $page != 'Register' && $page != 'Imprint')
	$display->showPage('Login');
else
	$display->showPage($page);