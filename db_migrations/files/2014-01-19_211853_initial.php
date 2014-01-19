<?php

$DB_MIGRATION = array(

	'description' => function () {
		return 'Initialer Import';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			CREATE TABLE `configurations` (
				`configuration_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`key` VARCHAR(255) NOT NULL,
				`value` VARCHAR(255) NOT NULL,
				PRIMARY KEY (`configuration_id`),
				INDEX `key` (`key`)
			)
			COLLATE="utf8_general_ci"
			ENGINE=InnoDB
		');

		$results[] = query_raw('
			CREATE TABLE `armies` (
				`army_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`game_id` INT(10) UNSIGNED NOT NULL,
				`name` VARCHAR(255) NOT NULL,
				`version` INT(11) NOT NULL,
				`edition` INT(11) NOT NULL,
				`internal_id` VARCHAR(255) NOT NULL,
				`creator` VARCHAR(255) NOT NULL,
				`create_datetime` DATETIME NOT NULL,
				`xml` MEDIUMTEXT NOT NULL,
				`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT "0",
				PRIMARY KEY (`army_id`),
				UNIQUE INDEX `edition` (`edition`, `version`),
				UNIQUE INDEX `internal_id` (`internal_id`, `version`),
				INDEX `game_id` (`game_id`),
				CONSTRAINT `game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`) ON UPDATE CASCADE ON DELETE CASCADE
			)
			COLLATE="utf8_general_ci"
			ENGINE=InnoDB
		');

		$results[] = query_raw('
			CREATE TABLE `games` (
				`game_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL,
				`version` INT(11) NOT NULL,
				`edition` INT(11) NOT NULL,
				`internal_id` VARCHAR(255) NOT NULL,
				`creator` VARCHAR(255) NOT NULL,
				`create_datetime` DATETIME NOT NULL,
				`xml` MEDIUMTEXT NOT NULL,
				`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT "0",
				PRIMARY KEY (`game_id`),
				UNIQUE INDEX `edition_version` (`edition`, `version`),
				UNIQUE INDEX `gs_id_unique` (`internal_id`, `version`)
			)
			COLLATE="utf8_general_ci"
			ENGINE=InnoDB
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$result = query_raw('
			ALTER TABLE tbl CHANGE col col_to_delete TEXT
		');

		return !!$result;

	}

);