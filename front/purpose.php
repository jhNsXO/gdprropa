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

include("../../../inc/includes.php");

$dropdown = new Purpose();

include(GLPI_ROOT . "/front/dropdown.common.php");