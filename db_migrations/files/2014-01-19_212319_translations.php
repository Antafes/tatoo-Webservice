<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'Sprachen und Übersetzungen';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			CREATE TABLE `languages` (
				`languageId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`language` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
				`iso2code` CHAR(2) NOT NULL COLLATE "utf8_bin",
				`deleted` TINYINT(1) NOT NULL,
				PRIMARY KEY (`languageId`),
				INDEX `iso2code` (`iso2code`)
			)
			COLLATE="utf8_bin"
			ENGINE=InnoDB
		');

		$results[] = query_raw('
			INSERT INTO `languages` (`languageId`, `language`, `iso2code`, `deleted`) VALUES (1, "german", "de", 0)
		');

		$results[] = query_raw('
			INSERT INTO `languages` (`languageId`, `language`, `iso2code`, `deleted`) VALUES (2, "english", "en", 0)
		');

		$results[] = query_raw('
			CREATE TABLE `translations` (
				`translationId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`languageId` INT(10) UNSIGNED NOT NULL,
				`key` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
				`value` TEXT NOT NULL COLLATE "utf8_general_ci",
				`deleted` TINYINT(1) NOT NULL,
				PRIMARY KEY (`translationId`),
				INDEX `languageId` (`languageId`),
				CONSTRAINT `languageId` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`) ON UPDATE CASCADE ON DELETE CASCADE
			)
			COLLATE="utf8_bin"
			ENGINE=InnoDB
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			DELETE TABLE `translations`
		');

		$results[] = query_raw('
			DELETE TABLE `languages`
		');

		return !in_array(false, $results);

	}

);