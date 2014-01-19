<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'Benutzer und Login';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			CREATE TABLE `users` (
				`userId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
				`password` VARCHAR(255) NOT NULL COLLATE "utf8_bin",
				`salt` VARCHAR(255) NOT NULL COLLATE "utf8_bin",
				`admin` TINYINT(1) NOT NULL DEFAULT "0",
				`languageId` INT(10) UNSIGNED NOT NULL DEFAULT "1",
				`deleted` TINYINT(1) NOT NULL,
				PRIMARY KEY (`userId`),
				INDEX `language` (`languageId`),
				CONSTRAINT `language` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`)
			)
			COLLATE="utf8_bin"
			ENGINE=InnoDB
		');

		$results[] = query_raw('
			INSERT INTO `users` (`name`, `password`, `salt`, `admin`)
			VALUES ("Admin", "cb2bf6d82e1a5e5eaf78c78e74d8f018", "sdgse5se", 1)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (1, 1, "title", "Tatoo Webservice Konfigurator", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (2, 2, "title", "Tatoo Webservice Configurator", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (3, 1, "german", "Deutsch", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (4, 2, "german", "German", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (5, 1, "english", "Englisch", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (6, 2, "english", "English", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (7, 1, "username", "Benutzername", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (8, 2, "username", "Username", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (9, 1, "password", "Passwort", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (10, 2, "password", "Password", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (11, 1, "login", "Login", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (12, 2, "login", "Login", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (13, 1, "index", "Startseite", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (14, 2, "index", "Index", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (15, 1, "admin", "Admin", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (16, 2, "admin", "Admin", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (17, 1, "logout", "Logout", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (18, 2, "logout", "Logout", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (19, 1, "invalidLogin", "Die eingegebenen Logindaten sind nicht bekannt.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (20, 2, "invalidLogin", "The entered login data are not known.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (21, 1, "emptyLogin", "Bitte fülle alle Felder aus.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (22, 2, "emptyLogin", "Please fill in all fields.", 0)
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			DELETE TABLE `users`
		');

		$results[] = query_raw('
			DELETE FROM translations WHERE translationId BETWEEN 1 AND 22
		');

		return !in_array(false, $results);

	}

);