<?php

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

$page_title = "Spell Search";
require_once($includes_dir . 'spell.inc.php');

$opt = (isset($_GET['opt']) ? $_GET['opt'] : '');
$namestring = (isset($_GET['name']) ? $_GET['name'] : '');
$level = (isset($_GET['level']) ? $_GET['level'] : 0);
$type = (isset($_GET['type']) ? $_GET['type'] : 0);
$leveltwo = (isset($_GET['leveltwo']) ? $_GET['leveltwo'] : '');

$check1 = "";
$check2 = "";
$check3 = "";
$check4 = "";

if ($opt == 1) {
    $check1 = "checked";
    $OpDiff = 0;
    $ClassOper = "=";
} elseif ($opt == 2) {
    $check2 = "checked";
    $OpDiff = -1;
    $ClassOper = ">=";
} elseif ($opt == 3) {
    $check3 = "checked";
    $OpDiff = 1;
    $ClassOper = "<=";
} elseif ($opt == 4) {
	$check4 = "checked";
	$OpDiff = -1; #checkme
	$ClassOper = " BETWEEN "; #checkme
} else {
    $check2 = "checked";
    $OpDiff = 0;
    $ClassOper = ">=";
}

/* Display Spell Form */
$print_buffer .= '<table border="0" class="display_table container_div" style="width:90%"><tr align="left"><td>';
$print_buffer .= '
			<form name="f" action="">
			<input type="hidden" name="a" value="spells">
			<table border="0" cellspacing="0" cellpadding="3">
			<tr><td>Search For:</td><td><input type="text" name="name" size="40" value="' . $namestring . '" /> <small><i>Searches name, description and casting messages</i></small></td></tr>
			<tr><td>Class:</td><td><select name="type">
			<option value="0"' . ($type == 0 ? ' selected="1"' : '') . '>------</option>
			<option value="8"' . ($type == 8 ? ' selected="1"' : '') . '>Bard</option>
			<option value="15"' . ($type == 15 ? ' selected="1"' : '') . '>Beastlord</option>
			<option value="2"' . ($type == 2 ? ' selected="1"' : '') . '>Cleric</option>
			<option value="6"' . ($type == 6 ? ' selected="1"' : '') . '>Druid</option>
			<option value="14"' . ($type == 14 ? ' selected="1"' : '') . '>Enchanter</option>
			<option value="13"' . ($type == 13 ? ' selected="1"' : '') . '>Magician</option>
			<option value="7"' . ($type == 7 ? ' selected="1"' : '') . '>Monk</option>
			<option value="11"' . ($type == 11 ? ' selected="1"' : '') . '>Necromancer</option>
			<option value="3"' . ($type == 3 ? ' selected="1"' : '') . '>Paladin</option>
			<option value="4"' . ($type == 4 ? ' selected="1"' : '') . '>Ranger</option>
			<option value="9"' . ($type == 9 ? ' selected="1"' : '') . '>Rogue</option>
			<option value="5"' . ($type == 5 ? ' selected="1"' : '') . '>Shadowknight</option>
			<option value="10"' . ($type == 10 ? ' selected="1"' : '') . '>Shaman</option>
			<option value="1"' . ($type == 1 ? ' selected="1"' : '') . '>Warrior</option>
			<option value="12"' . ($type == 12 ? ' selected="1"' : '') . '>Wizard</option>
			</select></td></tr>

			<tr><td>Level:</td><td><select name="level">
			<option value="">-----</option>';

for ($i = 1; $i <= $server_max_level; $i++) {
    $print_buffer .= '<option value="' . $i . '"' . ($level == $i ? ' selected="1"' : '') . '>' . $i . '</option>';
}

$print_buffer .= '</select>
			<label><input type="radio" name="opt" value="1" ' . $check1 . ' />Only</label>
			<label><input type="radio" name="opt" value="2" ' . $check2 . ' />And Higher</label>
			<label><input type="radio" name="opt" value="3" ' . $check3 . ' />And Lower</label></td></tr>
			';
$print_buffer .= '
				<tr><td><label><input type="radio" name="opt" value="4" ' . $check4 . ' />Between</label></td></tr>
				<tr><td>Max Level: </td><td>
				<select name="leveltwo">
				<option value="">-----</option>
			';
for ($i = 1; $i <= $server_max_level; $i++) {
	$print_buffer .= '<option value="' . $i . '"' . ($leveltwo == $i ? ' selected="1"' : '') . '>' . $i . '</option>';
}

$print_buffer .= '</select></td></tr>
			<tr>
			<td colspan="2">
			<br>
			<a class="button submit">Search</a>
            <a class="button" href="?a=spells">Reset</a>
			</td>
			</td></tr>
			</table>
			</form>';
/* End Display Spell Form */

/* Start Data Pull */

if (($type != 0 && $level != 0) || $namestring != '') {
    if (!$level) {
        $level = 0;
        $ClassOper = ">";
    }
    $sql = 'SELECT
			' . $spells_table . '.*
			FROM
			' . $spells_table . '
			WHERE';
    $sv = '';

    if ($type) {
		if ($opt == 4) {
			$sql .= ' ' . $spells_table . '.classes' . $type . " " . $ClassOper . " " . $level . ' AND ' . $leveltwo . '
						AND ' . $spells_table . '.classes' . $type . ' <= ' . $server_max_level;
			$sv = 'AND';
		} else {
			$sql .= ' ' . $spells_table . '.classes' . $type . " " . $ClassOper . " " . $level . '
						AND ' . $spells_table . '.classes' . $type . ' <= ' . $server_max_level;
			$sv = 'AND';
		}
    }
    $sql .= ' ' . $sv . ' ' . $spells_table . '.`name` LIKE \'%' . addslashes($namestring) . '%\'';
    if ($use_spell_globals == TRUE) {
        $sql .= ' AND ISNULL((SELECT ' . $spell_globals_table . '.spellid FROM ' . $spell_globals_table . '
				WHERE ' . $spell_globals_table . '.spellid = ' . $spells_table . '.id))';
    }

    if ($type != 0) {
        $sql .= ' ORDER BY ' . $spells_table . '.classes' . $type . ', ' . $spells_table . '.`name`';
    } else {
        $sql .= ' ORDER BY ' . $spells_table . '.`name` LIMIT ' . $max_items_returned;
    }

    $result = db_mysql_query($sql);
    if (!$result) {
        die('Invalid query: ' . mysqli_error());
    }

    $print_buffer .= ' <table border="0" cellpadding="1" cellspacing="1" style="width:90%">';
    $LevelCheck = $level + $OpDiff;
    $Class = 'classes' . $type;
	if ($type == 0) {
		#$classcount = 1;
		#while ($classcount <= 16 && ($LevelCheck != $row[$Class])) {
		#	$ClassName .= " | " . $dbclasses[$classcount];
		#	$classcount++;
		#}
    } else {
		$ClassName = $dbclasses[$type];
	}

    $RowClass = "lr";
    while ($row = mysqli_fetch_array($result)) {
        /* This will only come through when the Level Changes */
        $DBSkill = $dbskills[$row["skill"]];
        if ($LevelCheck != $row[$Class]) {
            $LevelCheck = $row[$Class];
            $print_buffer .= '<tr><td colspan="4"><b>Level: ' . $row['classes' . $type] . '</b></td></tr>';
            $print_buffer .= '<tr>
					<td class="menuh" align=center colspan=2>Name</td>
					<td class="menuh" align=center>Class</td>
					<td class="menuh" align=center>Level</td>
					<td class="menuh" align=center>Effect(s)</td>
					<td class="menuh" align=center>Mana</td>
					<td class="menuh" align=center>Skill</td>
					<td class="menuh" align=center>Target Type</td>
					<td class="menuh" align=center>Acquired By</td>
				  </tr>';
        }
        $print_buffer .= '<tr class="' . $RowClass . '">
					<td align=center><a href="?a=spell&id=' . $row['id'] . '"><img src="' . $icons_url . $row['new_icon'] . '.gif" align="center" border="1" width="25px" height="25px"></a></td>
					<td align=center><a href="?a=spell&id=' . $row['id'] . '">' . $row['name'] . '</a></td>
					<td align=center>' . $ClassName . '</td>
					<td align=center>' . $LevelCheck . '</td>
					<td align=center><small>';
		for ($n = 1; $n <= 12; $n++) {
			if ($row['effectid'.$n.''] != 10 || ($row['effectid'.$n.''] == 10 && ($row['effect_base_value'.$n.''] != 0 || $row['effect_limit_value'.$n.''] != 0 || $row['max'.$n.''] != 0))) {
				$print_buffer .= SpellDescription(getspell($row['id']), $n);
			}
		}
		$print_buffer .= '</small></td>
					<td align=center>' . $row['mana'] . '</td>
					<td align=center>' . ucwords(strtolower($DBSkill)) . '</td>
					<td align=center>';
        if ($dbspelltargets[$row["targettype"]] != "") {
            $print_buffer .= $dbspelltargets[$row["targettype"]];
        }
		$print_buffer .= '</td>';
		
		$acquired_by = "";
		//$row["acquired_by"];
		if ($row["acquired_bought"] == 1) {
			$acquired_by = "Bought";
		}
		if ($row["acquired_dropped"] == 1) {
			if ($acquired_by != "") {
				$acquired_by .= "/Dropped";
			} else {
				$acquired_by .= "Dropped";
			}
		}
		if ($row["acquired_crafted"] == 1) {
			if ($acquired_by != "") {
				$acquired_by .= "/Crafted";
			} else {
				$acquired_by .= "Crafted";
			}
		}
		if ($row["acquired_quested"] == 1) {
			if ($acquired_by != "") {
				$acquired_by .= "/Quested";
			} else {
				$acquired_by .= "Quested";
			}
		}
		if ($row["acquired_vegas"] == 1) {
			if ($acquired_by != "") {
				$acquired_by .= "/Vegas";
			} else {
				$acquired_by .= "Vegas";
			}
		}
		if ($row["acquired_by"] == 0) {
			$acquired_by = "Quested";
		}
		$print_buffer .= "<td align=center>$acquired_by</td>";
		
        $print_buffer .= '</tr>';

        if ($RowClass == "lr") {
            $RowClass = "dr";
        } else {
            $RowClass = "lr";
        }
    }
    $print_buffer .= '</tr></table>';
}
$print_buffer .= '</tr></table>';


?>