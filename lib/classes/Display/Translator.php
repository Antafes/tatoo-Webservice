<?php
namespace Display;

/**
 * Description of Language
 *
 * @author Neithan
 */
class Translator
{
	/**
	 * Singleton instance of the translator
	 *
	 * @var \self
	 */
	protected static $translator;

	/**
	 * An array of Model\Language objects.
	 *
	 * @var array
	 */
	protected $languages;

	/**
	 * The id of the current language.
	 *
	 * @var integer
	 */
	protected $currentLanguage;

	/**
	 * @var array
	 */
	protected $translations;

	private function __construct()
	{
		$this->fillLanguages();
		$this->fillTranslations();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return Translator
	 */
	public static function getInstance()
	{
		if (!self::$translator)
		{
			$translator = new self();
			$translator->setCurrentLanguage($_COOKIE['esLanguage']);
			self::$translator = $translator;
		}

		return self::$translator;
	}

	/**
	 * Fetches all languages and adds them as EsModelLanguage to the language list.
	 */
	protected function fillLanguages()
	{
		$sql = '
			SELECT languageId
			FROM languages
			WHERE !deleted
		';
		$languages = query($sql, true);

		foreach ($languages as $language)
		{
			$this->languages[$language['languageId']] = Model\Language::getLanguageById(
				$language['languageId']
			);
		}
	}

	/**
	 * Fetches all translations and adds them to the translation list.
	 */
	protected function fillTranslations()
	{
		/* @var Model\Language $language */
		foreach ($this->languages as $language)
		{
			$sql = '
				SELECT `key`, `value`
				FROM translations
				WHERE languageId = '.sqlval($language->getLanguageId()).'
					AND !deleted
			';
			$translations = query($sql, true);

			foreach ($translations as $translation)
			{
				$this->translations[$language->getLanguageId()][$translation['key']]
					= $translation['value'];
			}
		}
	}

	/**
	 * Get all fetched languages.
	 *
	 * @return array
	 */
	public function getAllLanguages()
	{
		return $this->languages;
	}

	/**
	 * Get the id of the users language.
	 *
	 * @return integer
	 */
	public function getCurrentLanguage()
	{
		if (!$this->currentLanguage)
		{
			$this->currentLanguage = $this->getUserLanguage();
			$this->setUserLanguage($this->currentLanguage);
		}

		return $this->currentLanguage;
	}

	/**
	 * Set the language of the user
	 *
	 * @param integer $currentLanguage
	 * @param boolean $setUserLanguage
	 */
	public function setCurrentLanguage($currentLanguage, $setUserLanguage = true)
	{
		$this->currentLanguage = $currentLanguage;

		if ($setUserLanguage)
			$this->setUserLanguage($this->currentLanguage);
	}

	/**
	 * Get the translated name for the users language
	 *
	 * @return string
	 */
	public function getCurrentLanguageName()
	{
		return $this->getTranslation($this->languages[$this->currentLanguage]->getLanguage());
	}

	/**
	 * Get the translation for the given key. If there is no translation available, the key is
	 * returned.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getTranslation($key)
	{
		if (is_object($key) || is_bool($key))
			return $key;

		if (is_array($key))
		{
			foreach ($key as &$item)
				$item = $this->getTranslation($item);

			return $key;
		}

		if (array_key_exists($key, $this->translations[$this->currentLanguage]))
			return $this->translations[$this->currentLanguage][$key];
		else
			return $key;
	}

	/**
	 * @return integer
	 */
	protected function getUserLanguage()
	{
		$languageId = $_COOKIE['esLanguage'];

		if (!$languageId)
		{
			$iso2code   = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			$language   = Model\Language::getLanguageByIso2Code($iso2code);
			$languageId = $language->getLanguageId();
		}

		return $languageId;
	}

	/**
	 * @param integer $languageId
	 */
	protected function setUserLanguage($languageId)
	{
		setcookie('language', $languageId, time() + 86400);
	}
}
