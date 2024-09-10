<?php

require_once($includes_dir . 'spell.inc.php');
$id = $_GET["id"];
$spell = getspell($id);
if (!$spell) {
    header("Location: ?a=spells");
    exit();
}
$page_title = $spell["name"] . ' ';

$page_title = str_replace('"', "'", $page_title);

$print_buffer .= "<table class='container_div ' style='width:500px;padding:10px'><tr style='vertical-align:middle !important'>";

$print_buffer .= "
    <tr>
        <td style='vertical-align:middle;text-align:right;width:150px;padding-right: 15px'>
           " . '<img src="' . $icons_url . $spell['new_icon'] . '.gif" style="border-radius:5px"> ' . "
        </td>
        <td style='vertical-align:middle'>
            <h1> Spell: " . $spell['name'] . "</h1>
        </td>
    </tr>
";
$print_buffer .= "<tr><td colspan='2'><h2 class='section_header'>Info - [ID: " . $spell["id"] . "]</h2></td></tr>";

$v = "";
$minlvl = 50;
$class_found = 0;
$class_data = "";
for ($i = 1; $i <= 16; $i++) {
    if (($spell["classes$i"] > 0) AND ($spell["classes$i"] < 254)) {
        $class_found = 1;
        $class_data .= "$v " . $dbclasses[$i] . " (" . $spell["classes$i"] . ")";
        $v = ",";
        if ($spell["classes$i"] < $minlvl) {
            $minlvl = $spell["classes$i"];
        }
    }
}

if($class_found) {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Classes</b></td><td>";
    $print_buffer .= $class_data;
}

$print_buffer .= "</td></tr>";
if ($spell["you_cast"] != "") {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>When you cast: </b></td><td>" . $spell["you_cast"] . "</td></tr>";
}
if ($spell["other_casts"] != "") {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>When others cast</b></td><td>" . $spell["other_casts"] . "</td></tr>";
}
if ($spell["cast_on_you"] != "") {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>When cast on you</b></td><td>" . $spell["cast_on_you"] . "</td></tr>";
}
if ($spell["cast_on_other"] != "") {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>When cast on other</b></td><td>" . $spell["cast_on_other"] . "</td></tr>";
}
if ($spell["spell_fades"] != "") {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>When fading</b></td><td>" . $spell["spell_fades"] . "</td></tr>";
}
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Mana</b></td><td>" . $spell["mana"] . "</td></tr>";
if ($spell["skill"] < 52) {
    //$print_buffer .= "<tr><td><b>Skill</b></td><td>".ucfirstwords($dbskills[$spell["skill"]])."</td></tr>";
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Skill</b></td><td>" . ucfirst(strtolower($dbskills[$spell["skill"]])) . "</td></tr>";
}
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Casting Time</b></td><td>" . ($spell["cast_time"] / 1000) . " sec</td></tr>";
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Recovery Time</b></td><td>" . ($spell["recovery_time"] / 1000) . " sec</td></tr>";
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Recast Time</b></td><td>" . ($spell["recast_time"] / 1000) . " sec</td></tr>";
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Range</b></td><td>" . $spell["range"] . "</td></tr>";
// Adding these two fields seems to give the clearest picture.  Technically
// HateAdded is modified for pets, dispel, and first hits, but those are
// probably not the cases most people are looking for.
$hate = $spell["HateAdded"] + $spell["bonushate"];
if ($hate != 0) {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Hate Generated</b></td><td>$hate</td></tr>";
}
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Target</b></td><td>";
if ($dbspelltargets[$spell["targettype"]] != "") {
    $print_buffer .= $dbspelltargets[$spell["targettype"]];
} else {
    $print_buffer .= "Unknown target (" . $spell["targettype"] . ")";
}
$print_buffer .= "</td></tr>";
// AE range seems to be 1 for self/single-target spells
if ($spell["aoerange"] > 1) {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>AoE Range</b></td><td>" . $spell["aoerange"] . "</td></tr>";
}
// AE max targets seems to be 1 for self/single-target spells
if ($spell["aemaxtargets"] > 1) {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>AoE Max Targets</b></td><td>" . $spell["aemaxtargets"] . "</td></tr>";
}
// EQEmu server checks that duration >= 1000 before applying any AE rules
if ($spell["AEDuration"] >= 1000) {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>AoE Duration</td><td>" . ($spell["AEDuration"] / 1000) . " sec</td></tr>";
}
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Resist</b></td><td>" . $dbspellresists[$spell["resisttype"]];
if ($spell["ResistDiff"] != 0) {
    $print_buffer .= " (Adjust: " . $spell["ResistDiff"] . ")";
}
$print_buffer .= "</td></tr>";
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Interruptable</b></td><td>" . (($spell["uninterruptable"] == 0) ? "Yes" : "No") . "</td></tr>";
if ($spell["TimeOfDay"] == 2) {
    $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Casting restriction</b></td><td>Nighttime</td></tr>";
}
if ($minlvl > 80) {
	$minlvl = 80;
}
$durationmin = CalcBuffDuration($minlvl, $spell["buffdurationformula"], $spell["buffduration"]);
$durationmaxlevel = MaxDurationLevel($minlvl, $spell["buffdurationformula"], $spell["buffduration"]);
if ($durationmaxlevel > 80) {
	$durationmaxlevel = 80;
}
$durationmax = CalcBuffDuration($durationmaxlevel, $spell["buffdurationformula"], $spell["buffduration"]);
$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Duration</b></td><td>";
if ($durationmin == 0) {
    $print_buffer .= "Instant";
} else {
	if ($minlvl != $durationmaxlevel) {
		$print_buffer .= translate_time($durationmin * 6) . " @ L" . floor($minlvl) . " to " . translate_time($durationmax * 6) . " @ L" . floor($durationmaxlevel) . " ($durationmin ticks)";
	} else {
		$print_buffer .= translate_time($durationmin * 6) . " ($durationmin ticks)";
	}
}
if ($spell["pushback"] > 0 || $spell["pushup"] > 0) {
	$print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Knockback</b></td><td>";
	if ($spell["pushback"] > 0) {
		$print_buffer .= " Push Back: 2'";
	}
	if ($spell["pushup"] > 0) {
		$print_buffer .= " Push Up: 10'";
	}
	$print_buffer .= "<br>";
}
$print_buffer .= "</td></tr>";
for ($i = 1; $i <= 4; $i++) {
    // reagents
    if ($spell["components" . $i] > 0) {
        $print_buffer .= "<tr><td style='text-align:right; padding-right: 5px;'><b>Needed Reagent $i</b></td><td>" .
            "<a href=?a=item&id=" . $spell["components" . $i] .
            ">" . get_field_result("Name", "SELECT Name FROM $items_table WHERE id=" .
                $spell["components" . $i]) .
            " </a>(" . $spell["component_counts" . $i] . ")</td></tr>";
    }
}


$print_buffer .= "<tr><td colspan='2'><h2 class='section_header'>Spell Effects</h2></td></tr>";

$print_buffer .= '<td colspan=2><small>';
for ($n = 1; $n <= 12; $n++) {
	if ($spell['effectid'.$n.''] != 10 || ($spell['effectid'.$n.''] == 10 && ($spell['effect_base_value'.$n.''] != 0 || $spell['effect_limit_value'.$n.''] != 0 || $spell['max'.$n.''] != 0))) {
		$print_buffer .= SpellDescription($spell, $n);
	}
}
$print_buffer .= '</small></td>';

$print_buffer .= "</td></tr><tr><td colspan='2'>";

if ($spell["RecourseLink"] > 0) {
	$recourseSpell = getspell($spell["RecourseLink"]);
	if ($recourseSpell) {
		$print_buffer .= "<tr><td colspan='2'><h2 class='section_header'>Recourse Spell Effects";
		$print_buffer .= " - <a href='?a=spell&id=" . $spell["RecourseLink"] . "'>"
			. get_field_result(
                "name",
                "SELECT name FROM $spells_table WHERE id=" . $spell["RecourseLink"]
            ) . 
			"</a>";
		$print_buffer .= "</h2></td></tr>";
		
		$print_buffer .= '<td colspan=2><small>';
		for ($n = 1; $n <= 12; $n++) {
			if ($recourseSpell['effectid'.$n.''] != 10 || ($recourseSpell['effectid'.$n.''] == 10 && ($recourseSpell['effect_base_value'.$n.''] != 0 || $recourseSpell['effect_limit_value'.$n.''] != 0 || $recourseSpell['max'.$n.''] != 0))) {
				$print_buffer .= SpellDescription($recourseSpell, $n);
			}
		}
		$print_buffer .= '</small></td>';
		
		$print_buffer .= "</td></tr><tr><td colspan='2'>";
	}
}
$query = "
    SELECT
        $items_table.id,
        $items_table.`name`
    FROM
        $items_table
    WHERE
        ($items_table.scrolleffect = $id
		OR $items_table.clickeffect = $id
		OR $items_table.proceffect = $id
		OR $items_table.worneffect = $id
		OR $items_table.focuseffect = $id
		OR $items_table.bardeffect = $id)
		AND $items_table.id NOT BETWEEN 600000 AND 999999
		AND $items_table.minstatus = 0
    ORDER BY
        $items_table.`name` ASC
";
$result = db_mysql_query($query);
if (mysqli_num_rows($result)) {
    $print_buffer .= "<h2 class='section_header'>Items with this spell:</h2>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<a href=?a=item&id=" . $row["id"] . ">" . get_item_icon_from_id($row['id']) . ' ' . $row["name"] . "</a><br>";
    }
}

$query = "
    SELECT
        $npc_types_table.id,
        $npc_types_table.`name`
    FROM
        $npc_types_table
	INNER JOIN
		$npc_spells_entries_table ON $npc_spells_entries_table.npc_spells_id = $npc_types_table.npc_spells_id
    WHERE
        $npc_spells_entries_table.spellid = $id
    ORDER BY
        $npc_types_table.`name` ASC
";
$result = db_mysql_query($query);
if (mysqli_num_rows($result)) {
    $print_buffer .= "<h2 class='section_header'>NPCs that use this spell:</h2><ul>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<a href=?a=npc&id=" . $row["id"] . ">" . get_npc_name_human_readable($row["name"]) . "</a><br>";
    }
}
$print_buffer .= "</ul></td></tr></table>";

?>