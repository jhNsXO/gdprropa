-- 1. Configs Table
    CREATE TABLE `glpi_plugin_gdprropa_configs` (
        `id` int unsigned NOT NULL auto_increment,
        `entities_id` int unsigned NOT NULL default '0' COMMENT 'RELATION to glpi_entities (id)',
        `config` TEXT NOT NULL,
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL COMMENT 'RELATION to glpi_users (id)',
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL COMMENT 'RELATION to glpi_users (id)',
        PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 2. Controller Infos
    CREATE TABLE `glpi_plugin_gdprropa_controllerinfos` (
        `id` int unsigned NOT NULL auto_increment,
        `entities_id` int unsigned COMMENT 'RELATION to glpi_entities (id)',
        `is_recursive` tinyint NOT NULL default '1',
        `users_id_representative` int unsigned default NULL,
        `users_id_dpo` int unsigned default NULL,
        `contracttypes_id_jointcontroller` int unsigned default NULL,
        `contracttypes_id_processor` int unsigned default NULL,
        `contracttypes_id_thirdparty` int unsigned default NULL,
        `contracttypes_id_internal` int unsigned default NULL,
        `contracttypes_id_other` int unsigned default NULL,
        `controllername` varchar(250) default NULL,
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        UNIQUE `entities_id` (`entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 3. Data Subjects Categories
    CREATE TABLE `glpi_plugin_gdprropa_datasubjectscategories` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(255) default NULL,
        `comment` text,
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        UNIQUE `un_per_record` (`name`, `entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 4. Legal Basis Acts
    CREATE TABLE `glpi_plugin_gdprropa_legalbasisacts` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(255) default NULL,
        `comment` text,
        `type` int NOT NULL default '0',
        `content` text,
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        KEY `type` (`type`),
        UNIQUE `un_per_record` (`name`, `entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 5. Personal Data Categories
    CREATE TABLE `glpi_plugin_gdprropa_personaldatacategories` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(255) default NULL,
        `comment` text,
        `plugin_gdprropa_personaldatacategories_id` int unsigned NOT NULL default '0',
        `completename` text,
        `level` int NOT NULL default '0',
        `ancestors_cache` longtext,
        `sons_cache` longtext,
        `is_special_category` tinyint NOT NULL default '0',
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        KEY `is_special_category` (`is_special_category`),
        UNIQUE `un_per_record` (`name`, `entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 6. Security Measures
    CREATE TABLE `glpi_plugin_gdprropa_securitymeasures` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(255) default NULL,
        `comment` text,
        `type` int NOT NULL default '0',
        `content` text,
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        KEY `type` (`type`),
        UNIQUE `un_per_record` (`name`, `entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 7. Recipients Categories
    CREATE TABLE `glpi_plugin_gdprropa_recipientscategories` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(255) default NULL,
        `comment` text,
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        UNIQUE `un_per_record` (`name`, `entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- 8. Purposes
    CREATE TABLE `glpi_plugin_gdprropa_purposes` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(255) default NULL,
        `comment` text,
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        UNIQUE `un_per_record` (`name`, `entities_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 9. Records
    CREATE TABLE `glpi_plugin_gdprropa_records` (
        `id` int unsigned NOT NULL auto_increment,
        `name` varchar(250) default NULL,
        `content` text,
        `additional_info` text,
        `states_id` int unsigned NOT NULL default '0',
        `pia_required` tinyint NOT NULL default '0',
        `pia_status` int NOT NULL default '0',
        `consent_required` tinyint NOT NULL default '0',
        `consent_storage` text,
        `storage_medium` int NOT NULL default '0',
        `first_entry_date` date default NULL,
        `entities_id` int unsigned NOT NULL default '0',
        `is_recursive` tinyint NOT NULL default '1',
        `is_deleted` tinyint NOT NULL default '0',
        `date_creation` datetime default NULL,
        `users_id_creator` int unsigned default NULL,
        `date_mod` datetime default NULL,
        `users_id_lastupdater` int unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `name` (`name`),
        KEY `states_id` (`states_id`),
        KEY `pia_required` (`pia_required`),
        KEY `pia_status` (`pia_status`),
        KEY `consent_required` (`consent_required`),
        KEY `storage_medium` (`storage_medium`),
        KEY `first_entry_date` (`first_entry_date`),
        KEY `is_deleted` (`is_deleted`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 10. Records Contracts
    CREATE TABLE `glpi_plugin_gdprropa_records_contracts` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `contracts_id` int unsigned NOT NULL default '0',
        `type` int NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `contracts_id`, `type`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `contracts_id` (`contracts_id`),
        KEY `type` (`type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 11. Records Retentions
    CREATE TABLE `glpi_plugin_gdprropa_records_retentions` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `type` int NOT NULL default '0',
        `contracts_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_legalbasisacts_id` int unsigned NOT NULL default '0',
        `contract_until_is_valid` tinyint NOT NULL default '0',
        `contract_retention_scale` varchar(50) default NULL,
        `contract_retention_value` int NOT NULL default '0',
        `contract_after_end_of` tinyint NOT NULL default '0',
        `additional_info` text,
        PRIMARY KEY  (`id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `type` (`type`),
        KEY `contracts_id` (`contracts_id`),
        KEY `plugin_gdprropa_legalbasisacts_id` (`plugin_gdprropa_legalbasisacts_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- 12. Records Data Subjects Categories
    CREATE TABLE `glpi_plugin_gdprropa_records_datasubjectscategories` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_datasubjectscategories_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `plugin_gdprropa_datasubjectscategories_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `plugin_gdprropa_datasubjectscategories_id` (`plugin_gdprropa_datasubjectscategories_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 13. Records Legal Basis Acts
    CREATE TABLE `glpi_plugin_gdprropa_records_legalbasisacts` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_legalbasisacts_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `plugin_gdprropa_legalbasisacts_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `plugin_gdprropa_legalbasisacts_id` (`plugin_gdprropa_legalbasisacts_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 14. Records Personal Data Categories
    CREATE TABLE `glpi_plugin_gdprropa_records_personaldatacategories` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_personaldatacategories_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `plugin_gdprropa_personaldatacategories_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `plugin_gdprropa_personaldatacategories_id` (`plugin_gdprropa_personaldatacategories_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 15. Records Security Measures
    CREATE TABLE `glpi_plugin_gdprropa_records_securitymeasures` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_securitymeasures_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `plugin_gdprropa_securitymeasures_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `plugin_gdprropa_securitymeasures_id` (`plugin_gdprropa_securitymeasures_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 16. Records Softwares
    CREATE TABLE `glpi_plugin_gdprropa_records_softwares` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `softwares_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `softwares_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `softwares_id` (`softwares_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;


-- 17. Records recipients categories
    CREATE TABLE `glpi_plugin_gdprropa_records_recipientscategories` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_recipientscategories_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `plugin_gdprropa_recipientscategories_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `plugin_gdprropa_recipientscategories_id` (`plugin_gdprropa_recipientscategories_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- 18. Records Purposes
    CREATE TABLE `glpi_plugin_gdprropa_records_purposes` (
        `id` int unsigned NOT NULL auto_increment,
        `plugin_gdprropa_records_id` int unsigned NOT NULL default '0',
        `plugin_gdprropa_purposes_id` int unsigned NOT NULL default '0',
        PRIMARY KEY  (`id`),
        UNIQUE KEY `unicity` (`plugin_gdprropa_records_id`, `plugin_gdprropa_purposes_id`),
        KEY `plugin_gdprropa_records_id` (`plugin_gdprropa_records_id`),
        KEY `plugin_gdprropa_purposes_id` (`plugin_gdprropa_purposes_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
