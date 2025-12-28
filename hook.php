<?php

/*
 -------------------------------------------------------------------------
 GDPR Records of Processing Activities plugin for GLPI
 Copyright © 2020-2025 by Yild.

 https://github.com/yild/gdprropa
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GDPR Records of Processing Activities.

 GDPR Records of Processing Activities is free software; you can
 redistribute it and/or modify it under the terms of the
 GNU General Public License as published by the Free Software
 Foundation; either version 3 of the License, or (at your option)
 any later version.

 GDPR Records of Processing Activities is distributed in the hope that
 it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 See the GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GDPR Records of Processing Activities.
 If not, see <https://www.gnu.org/licenses/>.

 Based on DPO Register plugin, by Karhel Tmarr.

 --------------------------------------------------------------------------

  @package   gdprropa
  @author    Yild
  @copyright Copyright © 2020-2025 by Yild
  @license   GPLv3+
             https://www.gnu.org/licenses/gpl.txt
  @link      https://github.com/yild/gdprropa
  @since     1.0.0
 --------------------------------------------------------------------------
 */

//namespace GlpiPlugin\Gdprropa;

use GlpiPlugin\Gdprropa\ControllerInfo;
use GlpiPlugin\Gdprropa\DataSubjectsCategory;
use GlpiPlugin\Gdprropa\LegalBasisAct;
use GlpiPlugin\Gdprropa\Menu;
use GlpiPlugin\Gdprropa\PersonalDataCategory;
use GlpiPlugin\Gdprropa\Profile;
use GlpiPlugin\Gdprropa\SecurityMeasure;

function plugin_gdprropa_install()
{
    global $DB;

    $install = false;
    if (!$DB->tableExists('glpi_plugin_gdprropa_records')) {
        $install = true;
    }

    if ($install) {
        if (class_exists('Migration')) {
            $migration = new Migration('plugin_gdprropa_install');
            $sqlFiles = [
                'install-1.0.0.sql',
                // Add more migration files here if needed
            ];
            foreach ($sqlFiles as $sqlFile) {
                $sqlPath = __DIR__ . '/sql/' . $sqlFile;
                if (file_exists($sqlPath)) {
                    $migration->displayMessage("Running migration: $sqlFile");
                    $migration->executeMigration($sqlPath);
                }
            }
        } else {
            if (class_exists('Toolbox')) {
                Toolbox::logInFile('gdprropa', sprintf(
                    'ERROR [%s:%s] Migration class not found during install, user=%s',
                    __FILE__, __FUNCTION__, $_SESSION['glpiname'] ?? 'unknown'
                ));
            }
            return false;
        }
    }

    Profile::initProfile();
    Profile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);

    return true;
}

function plugin_gdprropa_uninstall()
{
    global $DB;

    $result = true;
    $tables = [
        'glpi_plugin_gdprropa_configs',
        'glpi_plugin_gdprropa_controllerinfos',
        'glpi_plugin_gdprropa_datasubjectscategories',
        'glpi_plugin_gdprropa_legalbasisacts',
        'glpi_plugin_gdprropa_personaldatacategories',
        'glpi_plugin_gdprropa_securitymeasures',
        'glpi_plugin_gdprropa_records',
        'glpi_plugin_gdprropa_records_contracts',
        'glpi_plugin_gdprropa_records_retentions',
        'glpi_plugin_gdprropa_records_datasubjectscategories',
        'glpi_plugin_gdprropa_records_legalbasisacts',
        'glpi_plugin_gdprropa_records_personaldatacategories',
        'glpi_plugin_gdprropa_records_securitymeasures',
        'glpi_plugin_gdprropa_records_softwares',
    ];
    try {
        if (class_exists('Migration')) {
            $migration = new Migration('plugin_gdprropa_uninstall');
            foreach ($tables as $table) {
                if ($DB->tableExists($table)) {
                    $migration->dropTable($table);
                }
            }
            if (method_exists($migration, 'executeMigration')) {
                $migration->executeMigration();
            }
        } else {
            foreach ($tables as $table) {
                if ($DB->tableExists($table)) {
                    $DB->queryOrDie("DROP TABLE IF EXISTS `$table`;");
                }
            }
        }
    } catch (Exception $e) {
        if (class_exists('Toolbox')) {
            Toolbox::logInFile('gdprropa', sprintf(
                'ERROR [%s:%s] Failed to drop tables: %s, user=%s',
                __FILE__, __FUNCTION__, $e->getMessage(), $_SESSION['glpiname'] ?? 'unknown'
            ));
        }
        $result = false;
    }

    // Purge the logs table of the entries about the current class
    try {
        if ($DB->tableExists('glpi_logs')) {
            $query = "DELETE FROM `glpi_logs` WHERE `itemtype` LIKE 'PluginGdprropa%' OR `itemtype_link` LIKE 'PluginGdprropa%'";
            $DB->query($query);
        }
    } catch (Exception $e) {
        if (class_exists('Toolbox')) {
            Toolbox::logInFile('gdprropa', sprintf(
                'ERROR [%s:%s] Failed to purge logs: %s, user=%s',
                __FILE__, __FUNCTION__, $e->getMessage(), $_SESSION['glpiname'] ?? 'unknown'
            ));
        }
        $result = false;
    }

    try {
        $profileRight = new ProfileRight();
        foreach (Profile::getAllRights() as $right) {
            $profileRight->deleteByCriteria(['name' => $right['field']]);
        }
        Menu::removeRightsFromSession();
        Profile::removeRightsFromSession();
    } catch (Exception $e) {
        if (class_exists('Toolbox')) {
            Toolbox::logInFile('gdprropa', sprintf(
                'ERROR [%s:%s] Failed to cleanup profile rights: %s, user=%s',
                __FILE__, __FUNCTION__, $e->getMessage(), $_SESSION['glpiname'] ?? 'unknown'
            ));
        }
        $result = false;
    }

    if (class_exists('Toolbox')) {
        Toolbox::logInFile('gdprropa', sprintf(
            'UNINSTALL [%s:%s] Tables uninstall attempted, result=%s, user=%s',
            __FILE__, __FUNCTION__, $result ? 'success' : 'failure', $_SESSION['glpiname'] ?? 'unknown'
        ));
    }
    return $result;
}

function plugin_gdprropa_getDropdown()
{
    return [
        LegalBasisAct::class => LegalBasisAct::getTypeName(2),
        SecurityMeasure::class => SecurityMeasure::getTypeName(2),
        DataSubjectsCategory::class => DataSubjectsCategory::getTypeName(2),
        PersonalDataCategory::class => PersonalDataCategory::getTypeName(2),
    ];
}

function plugin_gdprropa_getAddSearchOptions($itemtype)
{
    $options = [];

    if ($itemtype == 'Entity') {
        $options = ControllerInfo::getSearchOptionsControllerInfo();
    }

    return $options;
}

function plugin_gdprropa_getDatabaseRelations()
{
    $plugin = new Plugin();

    if ($plugin->isActivated('gdprropa')) {
        return [
            'glpi_entites' => [
                'glpi_plugin_gdprropa_configs' => 'entities_id',
                'glpi_plugin_gdprropa_records' => 'entities_id',
                'glpi_plugin_gdprropa_controllerinfos' => 'entities_id',
                'glpi_plugin_gdprropa_datasubjectscategories' => 'entities_id',
                'glpi_plugin_gdprropa_legalbasisacts' => 'entities_id',
                'glpi_plugin_gdprropa_personaldatacategories' => 'entities_id',
                'glpi_plugin_gdprropa_securitymeasures' => 'entities_id',
            ],

            'glpi_users' => [
                'glpi_plugin_gdprropa_controllerinfos' => [
                    'users_id_representative',
                    'users_id_dpo',
                ],
                'glpi_plugin_gdprropa_configs' => [
                    'users_id_creator',
                    'users_id_lastupdater',
                ],
                'glpi_plugin_gdprropa_datasubjectscategories' => [
                    'users_id_creator',
                    'users_id_lastupdater',
                ],
                'glpi_plugin_gdprropa_legalbasisacts' => [
                    'users_id_creator',
                    'users_id_lastupdater',
                ],
                'glpi_plugin_gdprropa_personaldatacategories' => [
                    'users_id_creator',
                    'users_id_lastupdater',
                ],
                'glpi_plugin_gdprropa_records' => [
                    'users_id_creator',
                    'users_id_lastupdater',
                    'users_id_owner',
                ],
                'glpi_plugin_gdprropa_securitymeasures' => [
                    'users_id_creator',
                    'users_id_lastupdater',
                ],
            ],

            'glpi_contracts' => [
                'glpi_plugin_gdprropa_records_contracts' => 'contracts_id',
                'glpi_plugin_gdprropa_records_retentions' => 'contracts_id',
            ],
            'glpi_contracttypes' => [
                'glpi_plugin_gdprropa_controllerinfos' => [
                    'contracttypes_id_jointcontroller',
                    'contracttypes_id_processor',
                    'contracttypes_id_thirdparty',
                    'contracttypes_id_internal',
                    'contracttypes_id_other',
                ],
            ],

            'glpi_plugin_gdprropa_records' => [
                'glpi_plugin_gdprropa_records_contracts' => 'plugin_gdprropa_records_id',
                'glpi_plugin_gdprropa_records_datasubjectscategories' => 'plugin_gdprropa_records_id',
                'glpi_plugin_gdprropa_records_legalbasisacts' => 'plugin_gdprropa_records_id',
                'glpi_plugin_gdprropa_records_personaldatacategories' => 'plugin_gdprropa_records_id',
                'glpi_plugin_gdprropa_records_retentions' => 'plugin_gdprropa_records_id',
                'glpi_plugin_gdprropa_records_securitymeasures' => 'plugin_gdprropa_records_id',
                'glpi_plugin_gdprropa_records_softwares' => 'plugin_gdprropa_records_id',
            ],

            'glpi_plugin_gdprropa_datasubjectscategories' => [
                'glpi_plugin_gdprropa_records_datasubjectscategories' => 'plugin_gdprropa_datasubjectscategories_id',
            ],

            'glpi_plugin_gdprropa_legalbasisacts' => [
                'glpi_plugin_gdprropa_records_legalbasisacts' => 'plugin_gdprropa_legalbasisacts_id',
                'glpi_plugin_gdprropa_records_retentions' => 'plugin_gdprropa_legalbasisacts_id',
            ],

            'glpi_plugin_gdprropa_personaldatacategories' => [
                'glpi_plugin_gdprropa_records_personaldatacategories' => 'plugin_gdprropa_personaldatacategories_id',
            ],

            'glpi_plugin_gdprropa_securitymeasures' => [
                'glpi_plugin_gdprropa_records_securitymeasures' => 'plugin_gdprropa_securitymeasures_id',
            ],

            'glpi_softwares' => [
                'glpi_plugin_gdprropa_records_softwares' => 'softwares_id',
            ],
        ];
    }

    return [];
}

function plugin_gdprropa_postinit()
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['item_purge']['gdprropa'] = [];
    $PLUGIN_HOOKS['item_purge']['gdprropa']['Contract'] = ['Record_Contract', 'cleanForItem'];
    $PLUGIN_HOOKS['item_purge']['gdprropa']['Software'] = ['Record_Software', 'cleanForItem'];
}
