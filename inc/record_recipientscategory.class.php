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
  @author    mj
  @copyright Copyright © 2026 by mj
  @license   GPLv3+
             https://www.gnu.org/licenses/gpl.txt
  @link      https://github.com/jhnsxo/gdprropa
  @since     1.0.4
 --------------------------------------------------------------------------
 */

namespace GlpiPlugin\Gdprropa;

use CommonDBRelation;
use CommonGLPI;
use Dropdown;
use Entity;
use Html;
use Toolbox;

class Record_RecipientsCategory extends CommonDBRelation
{
    public static $itemtype_1 = Record::class;
    public static $items_id_1 = 'plugin_gdprropa_records_id';
    public static $itemtype_2 = RecipientsCategory::class;
    public static $items_id_2 = 'plugin_gdprropa_recipientscategories_id';

    public static function getTypeName($nb = 0): string
    {
        return _n("Category of recipients", "Categories of recipients", $nb, 'gdprropa');
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): bool|string
    {
        if (!$item->canView()) {
            return false;
        }

        switch ($item->getType()) {
            case Record::class:
                $nb = 0;
                if ($_SESSION['glpishow_count_on_tabs']) {
                    $nb = self::countForItem($item);
                }

                return self::createTabEntry(Record_RecipientsCategory::getTypeName($nb), $nb);
        }

        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        switch ($item->getType()) {
            case Record::class:
                return self::showForRecord($item, $withtemplate);
        }

        return true;
    }

    public static function showForRecord(Record $record, $withtemplate = 0): bool
    {
        $id = $record->fields['id'];
        if (!RecipientsCategory::canView() || !$record->can($id, READ)) {
            return false;
        }

        $canedit = Record::canUpdate();
        $rand = mt_rand(1, mt_getrandmax());

        $iterator = self::getListForItem($record);
        $number = count($iterator);

        $items_list = [];
        $used = [];
        foreach ($iterator as $data) {
            $items_list[$data['id']] = $data;
            $used[$data['id']] = $data['id'];
        }

        if ($canedit) {
            echo "<div class='firstbloc'>";
            echo "<form name='ticketitem_form$rand' id='ticketitem_form$rand' method='post'
                action='" . Toolbox::getItemTypeFormURL(__class__) . "'>";
            echo "<input type='hidden' name='plugin_gdprropa_records_id' value='$id' />";

            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_2'><th>" . __("Add Category of Recipients", 'gdprropa') . "</th></tr>";
            echo "<tr class='tab_bg_3'><td><center><strong>";
            echo __("GDPR Article 30 1d", 'gdprropa');
            echo "</strong></center></td></tr>";
            echo "<tr class='tab_bg_1'><td width='80%' class='center'>";
            RecipientsCategory::dropdown([
                'addicon' => RecipientsCategory::canCreate(),
                'name' => 'plugin_gdprropa_recipientscategories_id',
                'entity' => $record->fields['entities_id'],
                'entity_sons' => false,
                'used' => $used,
            ]);
            echo "</td></tr><tr><td width='20%' class='center'>";
            echo "<input type='submit' name='add' value=\"" . _sx('button', 'Add') . "\" class='submit'>";
            echo "</td></tr>";
            echo "</table>";
            Html::closeForm();
            echo "</div>";
        }

        if ($number) {
            echo "<div class='spaced'>";
            if ($canedit) {
                Html::openMassiveActionsForm('mass' . __class__ . $rand);
                $massiveactionparams = ['num_displayed' => min($_SESSION['glpilist_limit'], $number), 'container' => 'mass' . __class__ . $rand];
                Html::showMassiveActions($massiveactionparams);
            }

            echo "<table class='tab_cadre_fixehov'>";
            $header_begin = "<tr>";
            $header_top = "";
            $header_bottom = "";
            $header_end = "";

            $header_top .= "<th width='10'>" . Html::getCheckAllAsCheckbox('mass' . __class__ . $rand) . "</th>";
            $header_top .= "<th>" . __("Name") . "</th>";
            $header_top .= "<th>" . __("Entity") . "</th>";
            $header_top .= "<th>" . __("Comments") . "</th>";
            $header_top .= "<th>" . __("Introduced in") . "</th>";
            $header_end .= "</tr>";

            echo $header_begin . $header_top . $header_bottom . $header_end;

            foreach ($items_list as $data) {
                $rc = new RecipientsCategory();
                $rc->fields = $data;

                echo "<tr class='tab_bg_1'>";

                if ($canedit) {
                    echo "<td width='10'>";
                    Html::showMassiveActionCheckBox(__class__, $data['linkid']);
                    echo "</td>";
                }

                echo "<td>" . $rc->getLink() . "</td>";
                echo "<td>" . Dropdown::getDropdownName(Entity::getTable(), $data['entities_id']) . "</td>";
                echo "<td>" . nl2br($data['comment']) . "</td>";
                echo "<td>" . Html::convDate($data['date_creation']) . "</td>";

                echo "</tr>";
            }

            echo "</table>";

            if ($canedit) {
                $massiveactionparams['ontop'] = false;
                Html::showMassiveActions($massiveactionparams);
                Html::closeForm();
            }

            echo "</div>";
        }

        return true;
    }

    public function getForbiddenStandardMassiveAction(): array
    {
        $forbidden = parent::getForbiddenStandardMassiveAction();
        $forbidden[] = 'update';

        return $forbidden;
    }

    public static function rawSearchOptionsToAdd(): array
    {
        $tab = [];

        $tab[] = [
            'id' => 'recipientscategory',
            'name' => RecipientsCategory::getTypeName()
        ];

        $tab[] = [
            'id' => '51',
            'table' => RecipientsCategory::getTable(),
            'field' => 'name',
            'name' => __("Name"),
            'forcegroupby' => true,
            'massiveaction' => false,
            'datatype' => 'dropdown',
            'searchtype' => ['equals', 'notequals'],
            'joinparams' => [
                'beforejoin' => [
                    'table' => self::getTable(),
                    'joinparams' => [
                        'jointype' => 'child'
                    ]
                ]
            ]
        ];

        return $tab;
    }
}