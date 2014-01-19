<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'Administration';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			ALTER TABLE `users` ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `salt`
		');

		$results[] = query_raw('
			UPDATE `tatoo_webservice`.`users` SET `email`="admin@localhost" WHERE  `userId`=1
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (23, 1, "userId", "Benutzer ID", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (24, 2, "userId", "User ID", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (25, 1, "removeAdmin", "entfernen", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (26, 2, "removeAdmin", "remove", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (27, 1, "setAdmin", "setzen", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (28, 2, "setAdmin", "set", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (29, 1, "createUser", "Benutzer erstellen", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (30, 2, "createUser", "Create user", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (31, 1, "email", "E-Mail", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (32, 2, "email", "eMail", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (33, 1, "cancel", "Abbrechen", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`translationId`, `languageId`, `key`, `value`, `deleted`) VALUES (34, 2, "cancel", "Cancel", 0)
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			ALTER TABLE `users` DROP COLUMN `email`
		');

		$results[] = query_raw('
			DELETE FROM `translations` WHERE `translationId` BETWEEN 23 AND 34
		');

		return !in_array(false, $results);

	}

);