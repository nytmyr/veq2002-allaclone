<?php

require_once('pages/npcs/functions.php');

$id = (isset($_GET['id']) ? $_GET['id'] : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($id != "" && is_numeric($id)) {
    $Query = "SELECT * FROM $npc_types_table WHERE id='" . $id . "'";
    $QueryResult = db_mysql_query($Query) or message_die('npc.php', 'MYSQL_QUERY', $Query, mysqli_error());
    if (mysqli_num_rows($QueryResult) == 0) {
        header("Location: npcs.php");
        exit();
    }
    $npc = mysqli_fetch_array($QueryResult);
    $name = $npc["name"];
	
	if ($npc["raid_target"] == 1 OR $npc["rare_spawn"] == 1) {
		$FirstKillQuery = "
		SELECT n.`id` AS NPCID, n.`name` AS NPCName
		, GROUP_CONCAT(DISTINCT
			CASE 
				WHEN g.`name` <> '' 
					THEN g.`name`
			END
		SEPARATOR ', ') AS GuildKill
		, GROUP_CONCAT(DISTINCT cd.`name`SEPARATOR ', ') AS PlayerKill
		, k.`time` AS Time
		FROM character_data cd
		-- INNER JOIN account a ON a.`id` = cd.`account_id`
		INNER JOIN qs_player_npc_kill_record_entries ke ON ke.`char_id` = cd.`id`
		INNER JOIN qs_player_npc_kill_record k ON k.`fight_id` = ke.`event_id`
		INNER JOIN npc_types n ON n.`id` = k.`npc_id`
		LEFT JOIN guild_members gm ON gm.`char_id` = cd.`id`
		LEFT JOIN guilds g ON g.`id` = gm.`guild_id`
		WHERE n.`id` = " . $id . "
		-- AND a.`status` < 20
		AND cd.`account_id` > 2 AND cd.`account_id` != 595 AND cd.`account_id` != 597 AND cd.`account_id` != 808 AND cd.`account_id` != 861 AND cd.`account_id` != 959 AND cd.`account_id` != 990 AND cd.`account_id` != 1028 AND cd.`account_id` != 1243 AND cd.`account_id` != 1251
		GROUP BY k.`fight_id`
		ORDER BY k.`fight_id` ASC
		LIMIT 1
		";
		$FirstKillQueryResult = db_mysql_query($FirstKillQuery) or message_die('npc.php', 'MYSQL_QUERY', $FirstKillQuery, mysqli_error());
		if (mysqli_num_rows($FirstKillQueryResult) == 0) {
		}
		$firstkill = mysqli_fetch_array($FirstKillQueryResult);
	}
	
} elseif ($name != "") {
    $Query = "SELECT * FROM $npc_types_table WHERE name like '$name'";
    $QueryResult = db_mysql_query($Query) or message_die('npc.php', 'MYSQL_QUERY', $Query, mysqli_error());
    if (mysqli_num_rows($QueryResult) == 0) {
        header("Location: npcs.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $npc = mysqli_fetch_array($QueryResult);
        $id = $npc["id"];
        $name = $npc["name"];
    }
	if ($npc["raid_target"] == 1 OR $npc["rare_spawn"] == 1) {
		$FirstKillQuery = "
		SELECT n.`id` AS NPCID, n.`name` AS NPCName
		, GROUP_CONCAT(DISTINCT
			CASE 
				WHEN g.`name` <> '' 
					THEN g.`name`
			END
		SEPARATOR ', ') AS GuildKill
		, GROUP_CONCAT(DISTINCT cd.`name`SEPARATOR ', ') AS PlayerKill
		, k.`time` AS Time
		FROM character_data cd
		-- INNER JOIN account a ON a.`id` = cd.`account_id`
		INNER JOIN qs_player_npc_kill_record_entries ke ON ke.`char_id` = cd.`id`
		INNER JOIN qs_player_npc_kill_record k ON k.`fight_id` = ke.`event_id`
		INNER JOIN npc_types n ON n.`id` = k.`npc_id`
		LEFT JOIN guild_members gm ON gm.`char_id` = cd.`id`
		LEFT JOIN guilds g ON g.`id` = gm.`guild_id`
		WHERE n.`name` LIKE " . $name . "
		-- AND a.`status` < 20
		AND cd.`account_id` > 2 AND cd.`account_id` != 595 AND cd.`account_id` != 597 AND cd.`account_id` != 808 AND cd.`account_id` != 861 AND cd.`account_id` != 959 AND cd.`account_id` != 990 AND cd.`account_id` != 1028 AND cd.`account_id` != 1243 AND cd.`account_id` != 1251
		-- GROUP BY n.`id`
		-- ORDER BY a.`status` ASC, k.time ASC
		-- LIMIT 1
		GROUP BY k.`fight_id`
		ORDER BY k.`fight_id` ASC
		LIMIT 1
		";
		$FirstKillQueryResult = db_mysql_query($FirstKillQuery) or message_die('npc.php', 'MYSQL_QUERY', $FirstKillQuery, mysqli_error());
		if (mysqli_num_rows($FirstKillQueryResult) == 0) {
		} else {
			$firstkill = mysqli_fetch_array($FirstKillQueryResult);
		}
	}
	
} else {
    header("Location: npcs.php");
    exit();
}

if ($use_custom_zone_list == TRUE) {
    $query = "
        SELECT
            $zones_table.note
        FROM
            $zones_table,
            $spawn_entry_table,
            $spawn2_table
        WHERE
            $spawn_entry_table.npcID = $id
        AND $spawn_entry_table.spawngroupID = $spawn2_table.spawngroupID
        AND $spawn2_table.zone = $zones_table.short_name
        AND LENGTH($zones_table.note) > 0
		AND $zones_table.min_status = 0
    ";
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if (substr_count(strtolower($row["note"]), "disabled") >= 1) {
                header("Location: npcs.php");
                exit();
            }
        }
    }
}

if ((get_npc_name_human_readable($npc["name"])) == '' || ($npc["trackable"] == 0 && $trackable_npcs_only == TRUE)) {
    header("Location: npcs.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the NPC to display
 *    $name : name of the NPC to display
 *    $NpcRow : row of the NPC to display extracted from the database
 *    The NPC actually exists
 */

$page_title = "NPC :: " . get_npc_name_human_readable($name);
$raw_name = get_npc_name_human_readable($npc["name"]);

$DebugNpc = FALSE; // for world builders, set this to false for common use

if ($npc["raid_target"] == 1) {
	$npctype = "- <font color=red>[Raid Encounter] [" . $npc["raid_points"] . " point"; if($npc["raid_points"] > 1) { $npctype .= "s"; } $npctype .= "]<font color=black>";
}

if ($npc["rare_spawn"] == 1) {
	$npctype = "- <font color=green>[Named/Rare]<font color=black>";
}

if ($firstkill["GuildKill"] OR $firstkill["PlayerKill"]) {
	$firstkill_data = '<br><span style="color: purple; font-size: 13px;"><b>First Killed On '. $firstkill["Time"] . '</b>';
	#if ($firstkill["GuildKill"]) {
	#	$firstkill_data .= '<br>Guild(s): <a href="/charbrowser/index.php?page=guild&guild=' . str_replace(' ', '%20', $firstkill["GuildKill"]) . '">' . $firstkill["GuildKill"] . '</a>';
	#}
	if ($firstkill["GuildKill"]) {
		$playerarray = explode(', ', $firstkill["GuildKill"]);
		$count = count($playerarray);
		if ($count > 1) { 
			$firstkill_data .= '<br>Guilds: '; 
		} else { 
			$firstkill_data .= '<br>Guild: ';
		}
		$i = 0;
		foreach ($playerarray as &$value) {
			$i++;
			$value = $value = strtok($value, '-');
			if ($count > 0) {
				if ($count == 1) {
					$firstkill_data .= '<a href="/charbrowser/index.php?page=guild&guild=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
				}
				if ($i == 1 && $count == 2) {
					$firstkill_data .= '<a href="/charbrowser/index.php?page=character&char=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
				}
				elseif ($i < $count) {
					$firstkill_data .= '<a href="/charbrowser/index.php?page=guild&guild=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
					$firstkill_data .= ', ';
				}
				elseif ($i > 1 && $i == $count) {
					$firstkill_data .= ' and ';
					$firstkill_data .= '<a href="/charbrowser/index.php?page=guild&guild=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
				}
			}
		}
		
	}
	if ($firstkill["PlayerKill"]) {
		$playerarray = explode(', ', $firstkill["PlayerKill"]);
		$count = count($playerarray);
		if ($count > 1) { 
			$firstkill_data .= '<br>Players: '; 
		} else { 
			$firstkill_data .= '<br>Player: ';
		}
		$i = 0;
		foreach ($playerarray as &$value) {
			$i++;
			$value = $value = strtok($value, '-');
			if ($count > 0) {
				if ($count == 1) {
					$firstkill_data .= '<a href="/charbrowser/index.php?page=character&char=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
				}
				if ($i == 1 && $count == 2) {
					$firstkill_data .= '<a href="/charbrowser/index.php?page=character&char=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
				}
				elseif ($i < $count) {
					$firstkill_data .= '<a href="/charbrowser/index.php?page=character&char=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
					$firstkill_data .= ', ';
				}
				elseif ($i > 1 && $i == $count) {
					$firstkill_data .= ' and ';
					$firstkill_data .= '<a href="/charbrowser/index.php?page=character&char=' . str_replace(' ', '%20', $value) . '">' . $value . '</a>';
				}
			}
		}
		
	}
} else {
	if ($npc["raid_target"] == 1 OR $npc["rare_spawn"] == 1) {
		$firstkill_data = '<br><span style="color: purple; font-size: 13px;"><b>Not yet killed.</b>';
	}
}

$print_buffer .= "
    <table class='display_table container_div'>
        <tr valign='top'>
            <td colspan='2'>
                <h1>" . get_npc_name_human_readable($npc["name"]) . " " . $npctype . "" . $firstkill_data . "</h1>
            </td>
        </tr>
		<tr>
			<td>
				<table>
					<tr>
						<td>
							<a href='https://everquest.allakhazam.com/search.html?q=" . $raw_name . "'>Search for this NPC on Allakhazam</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
";
$print_buffer .= "
    <tr valign='top'>
        <td width='0%'>
            <table>
                <tr>
                    <td>
                        ";



$print_buffer .= "<table border='0' width='90%'>";

$alt_currency_merchant = "";
if ($npc["class"] == 70) {
	$alt_currency_merchant = "<tr><td style='text-align:right'><b>Currency</b></td><td>" . get_item_name_from_id($altcurrencyid[$npc["alt_currency_id"]]) . "</td></tr>";
}

$npc_attack_speed = "";
if ($show_npcs_attack_speed == TRUE) {
    $npc_attack_speed = "<tr><td style='text-align:right'><b>Attack Speed</td><td>";
    if ($npc["attack_delay"] == 33) {
        $npc_attack_speed .= "Normal (100%)";
    } else {
        $npc_attack_speed .= number_format(((33 / $npc["attack_delay"]) * 100)) . "%";
    }
    $print_buffer .= "</td></tr>";
}
if ($show_npcs_difficulty == TRUE) {
	if ($npc["difficulty"] > 0) {
		$npc_difficulty = "<tr><td style='text-align:right'><b>Difficulty</td><td>";
		if ($npc["raid_target"]) {
			$npc_difficulty .= number_format($npc["difficulty"]) . "<br>";
			if ($npc["loottable_id"] > 0) {
				$npc_difficulty .= "<tr><td style='text-align:right'><b>Vegas Low</td><td>" . number_format($npc["difficulty"] * .5) . "<tr><td style='text-align:right'><b>Vegas High</td><td>" . number_format($npc["difficulty"] * 1.5);
				#$npc_difficulty .= "<a href='http://vegaseq.com/Allaclone/?a=items_search&&a=items&iname=&iclass=0&irace=0&islot=0&istat1=&istat1comp=%3E%3D&istat1value=&istat2=&istat2comp=%3E%3D&istat2value=&iresists=&iresistscomp=%3E%3D&iresistsvalue=&imod=&imodcomp=%3E%3D&imodvalue=&iskillmod=&iskillmodcomp=%3E%3D&iskillmodvalue=&ibardskillmod=&ibardskillmodcomp=%3E%3D&ibardskillmodvalue=&itype=-1&ideity=0&ieffect=&ieffectlevelcomp=%3E%3D&ieffectlevel=&ieffecttype=&iminlevel=0&ireqlevel=0&iminreclevel=0&imaxreclevel=0&ibeingsold=-1&itier=-1&ilowprice=&ihighprice=&ivegas=2&ishardvalue=0&inodrop=0&iraiditemsonly=0&ieramin=4&ieramax=5&imindiff=" . ($npc["difficulty"] * .5) . "&imaxdiff=" . ($npc["difficulty"] * 1.35) . "&isearch=1'>" . number_format($npc["difficulty"] * .5) . " to " . number_format($npc["difficulty"] * 1.5) . "</a>";
			}
		}
		elseif ($npc["rare_spawn"]) {
			$npc_difficulty .= number_format($npc["difficulty"]) . "<br>";
			if ($npc["loottable_id"] > 0) {
				$npc_difficulty .= "<tr><td style='text-align:right'><b>Vegas Low</td><td>" . number_format($npc["difficulty"] * .5) . "<tr><td style='text-align:right'><b>Vegas High</td><td>" . number_format($npc["difficulty"] * 1.35);
				#$npc_difficulty .= "<a href='http://vegaseq.com/Allaclone/?a=items_search&&a=items&iname=&iclass=0&irace=0&islot=0&istat1=&istat1comp=%3E%3D&istat1value=&istat2=&istat2comp=%3E%3D&istat2value=&iresists=&iresistscomp=%3E%3D&iresistsvalue=&imod=&imodcomp=%3E%3D&imodvalue=&iskillmod=&iskillmodcomp=%3E%3D&iskillmodvalue=&ibardskillmod=&ibardskillmodcomp=%3E%3D&ibardskillmodvalue=&itype=-1&ideity=0&ieffect=&ieffectlevelcomp=%3E%3D&ieffectlevel=&ieffecttype=&iminlevel=0&ireqlevel=0&iminreclevel=0&imaxreclevel=0&ibeingsold=-1&itier=-1&ilowprice=&ihighprice=&ivegas=2&ishardvalue=0&inodrop=0&iraiditemsonly=0&ieramin=4&ieramax=5&imindiff=" . ($npc["difficulty"] * .5) . "&imaxdiff=" . ($npc["difficulty"] * 1.35) . "&isearch=1'>" . number_format($npc["difficulty"] * .5) . " to " . number_format($npc["difficulty"] * 1.35) . "</a>";
			}
		} else {
			$npc_difficulty .= number_format($npc["difficulty"]) . "<br>";
			if ($npc["loottable_id"] > 0) {
				$npc_difficulty .= "<tr><td style='text-align:right'><b>Vegas Low</td><td>" . number_format($npc["difficulty"] * .5) . "<tr><td style='text-align:right'><b>Vegas High</td><td>" . number_format($npc["difficulty"] * 1.2);
				#$npc_difficulty .= "<a href='http://vegaseq.com/Allaclone/?a=items_search&&a=items&iname=&iclass=0&irace=0&islot=0&istat1=&istat1comp=%3E%3D&istat1value=&istat2=&istat2comp=%3E%3D&istat2value=&iresists=&iresistscomp=%3E%3D&iresistsvalue=&imod=&imodcomp=%3E%3D&imodvalue=&iskillmod=&iskillmodcomp=%3E%3D&iskillmodvalue=&ibardskillmod=&ibardskillmodcomp=%3E%3D&ibardskillmodvalue=&itype=-1&ideity=0&ieffect=&ieffectlevelcomp=%3E%3D&ieffectlevel=&ieffecttype=&iminlevel=0&ireqlevel=0&iminreclevel=0&imaxreclevel=0&ibeingsold=-1&itier=-1&ilowprice=&ihighprice=&ivegas=2&ishardvalue=0&inodrop=0&iraiditemsonly=0&ieramin=4&ieramax=5&imindiff=" . ($npc["difficulty"] * .5) . "&imaxdiff=" . ($npc["difficulty"] * 1.35) . "&isearch=1'>" . number_format($npc["difficulty"] * .5) . " to " . number_format($npc["difficulty"] * 1.2) . "</a>";
			}
		}
		$print_buffer .= "</td></tr>";
	}
}

$print_buffer .= "</td></tr></table>";

$npc_data = '
    <table border="0" width="90%">
        <tbody>
            <tr>
                <td style="width:250px !important; text-align:right"><b>Full Name</b>
                </td>
                <td>' . get_npc_name_human_readable($npc["name"]) . " " . $npc["lastname"] . '</td>
            </tr>
            <tr>
                <td style="text-align:right""><b>Level</b>
                </td>
                <td>' . $npc["level"] . '</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Race</b>
                </td>
                <td>' . $dbiracenames[$npc["race"]]  .'</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Class</b>
                </td>
                <td>' . $dbclasses[$npc["class"]] . '</td>
            </tr>
			' . $alt_currency_merchant . '
            <tr>
                <td style="text-align:right"><b>Main Faction</b>
                </td>
                <td>' . return_npc_primary_faction($npc['npc_faction_id']) . '</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Health Points</b>
                </td>
                <td>' . number_format($npc["hp"]) . '</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Damage</b>
                </td>
                <td>' . $npc["mindmg"] . " to " . $npc["maxdmg"] . '</td>
            </tr>
			 ' . $npc_attack_speed . '
			<tr>
				<td style="text-align:right"><b>Special Attacks</b>
				</td>
				<td> ' . ($npc["runspeed"] == 0 ? (empty($npc["special_abilities"]) ? "Rooted<br>" : "Rooted,<br>") : "") . '' . SpecialAbilities($npc["special_abilities"]) . '</td>
            </tr>
			<tr>
				<td style="text-align:right"><b>--------------</b>
				</td>
			</tr>
			<tr>
                <td style="text-align:right"><b>Magic Resists</b>
                </td>
                <td style="text-align:left">
					' . $npc["MR"] . '
				</td>
            </tr>
			<tr>
                <td style="text-align:right"><b>Cold Resists</b>
                </td>
                <td style="text-align:left">
					' . $npc["CR"] . '
				</td>
            </tr>
			<tr>
                <td style="text-align:right"><b>Fire Resists</b>
                </td>
                <td style="text-align:left">
					' . $npc["FR"] . '
				</td>
            </tr>
			<tr>
                <td style="text-align:right"><b>Disease Resists</b>
                </td>
                <td style="text-align:left">
					' . $npc["DR"] . '
				</td>
            </tr>
			<tr>
                <td style="text-align:right"><b>Poison Resists</b>
                </td>
                <td style="text-align:left">
					' . $npc["PR"] . '
				</td>
            </tr>
			<tr>
				<td style="text-align:right"><b>--------------</b>
				</td>
			</tr>
			' . $npc_difficulty . '
        </tbody>
    </table>

';

$print_buffer .= $npc_data;

$print_buffer .= "<td valign='right'><table class='display_table container_div'>"; // right column height='100%'
$print_buffer .= "<tr><td>"; // image
if ($UseWikiImages) {
    $ImageFile = NpcImage($wiki_server_url, $wiki_root_name, $id);
    if ($ImageFile == "") {
        $print_buffer .= "<a href='" . $wiki_server_url . $wiki_root_name . "/index.php?title=Special:Upload&wpDestFile=Npc-" . $id . ".jpg'>Click to add an image for this NPC</a>";
    } else {
        $print_buffer .= "<img src='" . $ImageFile . "'/>";
    }
} else {
    if (file_exists($npcs_dir . $id . ".jpg")) {
        $print_buffer .= "<img src=" . $npcs_url . $id . ".jpg>";
    }
}

$print_buffer .= "</td></tr><tr><td>";
// zone list
$query = "
    SELECT
        $zones_table.long_name,
        $zones_table.short_name,
        $spawn2_table.x,
        $spawn2_table.y,
        $spawn2_table.z,
        $spawn_group_table.`name` AS spawngroup,
        $spawn_group_table.id AS spawngroupID,
        $spawn2_table.respawntime,
		$spawn2_table.variance,
		$spawn_entry_table.chance
    FROM
        $zones_table,
        $spawn_entry_table,
        $spawn2_table,
        $spawn_group_table,
		$npc_types_table n
    WHERE
        $spawn_entry_table.npcID = $id
    AND $spawn_entry_table.spawngroupID = $spawn2_table.spawngroupID
    AND $spawn2_table.zone = $zones_table.short_name
    AND $spawn_entry_table.spawngroupID = $spawn_group_table.id
	AND $spawn2_table.enabled = 1
	AND n.id = $id
	AND ((n.`race` = 127 AND n.`mindmg` != 1 AND n.`maxdmg` != 4 AND n.`show_name` = 1) OR (n.`race` != 127))
	AND ((n.`race` = 240 AND n.`mindmg` != 1 AND n.`maxdmg` != 4 AND n.`show_name` = 1) OR (n.`race` != 240))
	-- AND (n.`mindmg` != 0 AND n.`maxdmg` != 4)
	AND n.bodytype NOT BETWEEN 65 AND 67
	AND n.show_name = 1
	AND n.hide_npc = 0
	AND n.untargetable = 0
";
foreach ($ignore_zones AS $zid) {
    $query .= " AND $zones_table.short_name!='$zid'";
}
$query .= " ORDER BY $zones_table.long_name,$spawn_group_table.`name`";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $print_buffer .= "<h2 class='section_header' style='width:90%'>This NPC spawns in</h2>";
    $z = "";
	$respawntimemin = 0;
	$respawntimemax = 0;
    while ($row = mysqli_fetch_array($result)) {
        if ($z != $row["short_name"]) {
            $print_buffer .= "<p><a href='?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a>";
            $z = $row["short_name"];
            if ($allow_quests_npc == TRUE) {
                if (file_exists("$quests_dir$z/" . str_replace("#", "", $npc["name"]) . ".pl")) {
                    $print_buffer .= "<br/><a href='" . $root_url . "quests/index.php?npc=" . str_replace("#", "", $npc["name"]) . "&zone=" . $z . "&amp;npcid=" . $id . "'>Quest(s) for that NPC</a>";
                }
            }
        }
        if ($display_spawn_group_info == TRUE) {
            $print_buffer .= "<li><a href='spawngroup.php?id=" . $row["spawngroupID"] . "'>" . $row["spawngroup"] . "</a> : " . floor($row["y"]) . " / " . floor($row["x"]) . " / " . floor($row["z"]);
            $print_buffer .= "<br/>Spawns every " . translate_respawn_time($row["respawntime"]);
        }
		
		#$respawntimemin = $row["respawntime"] - $row["variance"];
		#$respawntimemax = $row["respawntime"] + $row["variance"];
		$respawntime = $row["respawntime"];
		$variance = $row["variance"];
		$chance = $row["chance"];
		#$respawnaverage = ($respawntimemax + $respawntimemin) / 2;
    }
	if (display_spawn_times == TRUE) {
		#if ($respawntimemin == $respawntimemax) {
		
		#$print_buffer .= "<br/>Spawns every " . translate_respawn_time($respawntimemin);
		if ($chance < 5) {
			$chance_text = "Ultra Rare spawn";
		}
		else if ($chance < 15) {
			$chance_text = "Very Rare spawn";
			}
		else if ($chance < 25) {
			$chance_text = "Rare spawn";
			}
		else if ($chance < 50) {
			$chance_text = "Uncommon spawn";
			}
		else if ($chance < 75) {
			$chance_text = "Common spawn";
			}
		else if ($chance < 100) {
			$chance_text = "Very Common spawn";
			}
		else if ($chance >= 100) {
			$chance_text = "Spawns";
		}		
		
		if ($variance == 0) {
			$print_buffer .= "<br/>$chance_text every " . translate_respawn_time($respawntime);
		} else {
			#$print_buffer .= "<br/>Spawns every " . translate_respawn_time($respawntimemin) . " to " . translate_respawn_time($respawntimemax);
			$print_buffer .= "<br/>$chance_text every " . translate_respawn_time($respawntime) . " +/- " . translate_respawn_time($variance);
		}
	}
} else {
	$query = "
    SELECT z.`short_name`, z.`long_name`
	FROM $npc_types_table n
	LEFT JOIN $zones_table z ON z.zoneidnumber = FLOOR(CAST((n.`id` / 1000) AS DOUBLE))
	WHERE n.`id` = $id
	AND ((n.`race` = 127 AND n.`mindmg` != 1 AND n.`maxdmg` != 4 AND n.`show_name` = 1) OR (n.`race` != 127))
	AND ((n.`race` = 240 AND n.`mindmg` != 1 AND n.`maxdmg` != 4 AND n.`show_name` = 1) OR (n.`race` != 240))
	AND (n.`mindmg` != 0 AND n.`maxdmg` != 4)
	AND n.bodytype NOT BETWEEN 65 AND 67
	AND n.show_name = 1
	AND n.hide_npc = 0
	AND n.untargetable = 0
	ORDER BY z.`long_name`
	";
	$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
	if (mysqli_num_rows($result) > 0) {
		$print_buffer .= "<h2 class='section_header' style='width:90%'>This NPC may spawn in</h2>";
		while ($row = mysqli_fetch_array($result)) {
			if ($z != $row["short_name"]) {
				$print_buffer .= "<p><a href='?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a>";
				$z = $row["short_name"];
				if ($allow_quests_npc == TRUE) {
					if (file_exists("$quests_dir$z/" . str_replace("#", "", $npc["name"]) . ".pl")) {
						$print_buffer .= "<br/><a href='" . $root_url . "quests/index.php?npc=" . str_replace("#", "", $npc["name"]) . "&zone=" . $z . "&amp;npcid=" . $id . "'>Quest(s) for that NPC</a>";
					}
				}
			}
		}
	}
}
// factions
$query = "
    SELECT
        $faction_list_table.`name`,
        $faction_list_table.id,
        $faction_entries_table.value

    FROM
        $faction_list_table,
        $faction_entries_table
    WHERE
        $faction_entries_table.npc_faction_id = " . $npc["npc_faction_id"] . "
    AND $faction_entries_table.faction_id = $faction_list_table.id
    AND $faction_entries_table.value < 0
    GROUP BY
        $faction_list_table.id
	ORDER BY
		$faction_list_table.`name` ASC
";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $print_buffer .= "<h2 class='section_header' style='width:90%'>Killing this NPC lowers factions with</h2><ul>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<li><a href=?a=faction&id=" . $row["id"] . ">" . $row["name"] . "</a> (<font color=red>" . $row["value"] . "<font color=black>)";
    }
}
$print_buffer .= "</ul>";
$query = "
    SELECT
        $faction_list_table.`name`,
        $faction_list_table.id,
        $faction_entries_table.value
    FROM
        $faction_list_table,
        $faction_entries_table
    WHERE
        $faction_entries_table.npc_faction_id = " . $npc["npc_faction_id"] . "
    AND $faction_entries_table.faction_id = $faction_list_table.id
    AND $faction_entries_table.value > 0
    GROUP BY
        $faction_list_table.id
	ORDER BY
		$faction_list_table.`name` ASC
";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $print_buffer .= "
        <h2 class='section_header' style='width:90%'>Killing this NPC raises factions with</h2>
        <ul>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<li><a href=?a=faction&id=" . $row["id"] . ">" . $row["name"] . "</a> (<font color=green>+" . $row["value"] . "<font color=black>)";
    }
}
$print_buffer .= "</ul>";
$print_buffer .= "</td></tr></table>";

$print_buffer .= "<tr valign='top'>";

if ($npc["npc_spells_id"] > 0) {
        /** @noinspection SqlDialectInspection */
        $query = "
			SELECT s.id AS SpellID, s.name, s.new_icon, ne.`type`, ne.minlevel, ne.maxlevel
			, CASE WHEN (n.attack_proc > 0 OR nn.attack_proc > 0 OR nnn.attack_proc > 0 OR nnnn.attack_proc > 0 OR nnnnn.attack_proc > 0) AND (n.attack_proc = s.id OR nn.attack_proc = s.id OR nnn.attack_proc = s.id OR nnnn.attack_proc = s.id OR nnnnn.attack_proc = s.id) THEN s.id ELSE 0 END AS ProcID
			FROM $npc_spells_table n
			LEFT JOIN $npc_spells_table nn ON nn.id = n.parent_list
			LEFT JOIN $npc_spells_table nnn ON nnn.id = nn.parent_list
			LEFT JOIN $npc_spells_table nnnn ON nnnn.id = nnn.parent_list
			LEFT JOIN $npc_spells_table nnnnn ON nnnnn.id = nnnn.parent_list
			LEFT JOIN $npc_spells_entries_table ne ON (ne.npc_spells_id = n.id OR ne.npc_spells_id = nn.id OR ne.npc_spells_id = nnn.id OR ne.npc_spells_id = nnnn.id OR ne.npc_spells_id = nnnnn.id)
			INNER JOIN $spells_table s ON (s.id = n.attack_proc OR s.id = nn.attack_proc OR s.id = nnn.attack_proc OR s.id = nnnn.attack_proc OR s.id = nnnnn.attack_proc OR s.id = ne.spellid)
			AND n.id = " .$npc["npc_spells_id"] . "
			-- AND " . $npc["level"] . " BETWEEN ne.minlevel AND ne.maxlevel
			GROUP BY s.id
			ORDER BY ProcID DESC, ne.type ASC, s.name

        ";
        $result2 = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
        if (mysqli_num_rows($result2) > 0) {
			$print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>This NPC casts the following spells</h2><p>";
            #$list_name = get_npc_name_human_readable($g["name"]);

            #$print_buffer .= "</ul>{$list_name}";
            if ($DebugNpc) {
                $print_buffer .= " (" . $npc["npc_spells_id"] . ")";
            }
            $print_buffer .= "<ul>";
            while ($row = mysqli_fetch_array($result2)) {
			
                $icon = '<img src="' . $icons_url . $row['new_icon'] . '.gif" align="center" border="1" style="border-radius:5px;height:15px;width:auto">';
				
				if (($npc["level"] >=  $row["minlevel"] AND $npc["level"] <=  $row["maxlevel"]) OR $row["ProcID"] > 1) {
					$print_buffer .= "<li><a href='?a=spell&id=" . $row["SpellID"] . "'>{$icon} {$row['name']} </a>";
					if ($row["ProcID"] < 1) {
						$print_buffer .= " (" . $dbspelltypes[$row["type"]] . ")";
					} else {
						$print_buffer .= " (Proc)";
					}
					if ($DebugNpc) {
						$print_buffer .= " (recast=" . $row["recast_delay"] . ", priority= " . $row["priority"] . ")";
					}
				}
            }
        }
        $print_buffer .= "</td></tr></table></td>";
}

if (($npc["loottable_id"] > 0) AND ((!in_array($npc["class"], $dbmerchants)) OR ($merchants_dont_drop_stuff == FALSE))) {
	if ($show_npc_drop_chances_as_rarity == TRUE) {
		$query = "
			SELECT
			$items_table.id,
			$items_table.Name,
			$items_table.itemtype,
			CASE
				WHEN $loot_drop_entries_table.chance < 5
				THEN 'Ultra Rare'
				WHEN $loot_drop_entries_table.chance < 15
				THEN 'Very Rare'
				WHEN $loot_drop_entries_table.chance < 25
				THEN 'Rare'
				WHEN $loot_drop_entries_table.chance < 50
				THEN 'Uncommon'
				WHEN $loot_drop_entries_table.chance < 75
				THEN 'Common'
				WHEN $loot_drop_entries_table.chance < 100
				THEN 'Very Common'
				WHEN $loot_drop_entries_table.chance >= 100
				THEN 'Always'
			END AS chance,
			$loot_table_entries.probability,
			$loot_table_entries.lootdrop_id,
			$loot_drop_entries_table.multiplier as LootDropMult
		";
	} else {
		$query = "
			SELECT
			$items_table.id,
			$items_table.Name,
			$items_table.itemtype,
			$loot_drop_entries_table.chance,
			$loot_table_entries.probability,
			$loot_table_entries.lootdrop_id,
			$loot_drop_entries_table.multiplier as LootDropMult
		";
	}
	
    if ($discovered_items_only == TRUE) {
        $query .= " FROM $items_table,$loot_table_entries,$loot_drop_entries_table,$discovered_items_table";
    } else {
        $query .= " FROM $items_table,$loot_table_entries,$loot_drop_entries_table";
    }

    $query .= " WHERE $loot_table_entries.loottable_id=" . $npc["loottable_id"] . "
			AND $loot_table_entries.lootdrop_id=$loot_drop_entries_table.lootdrop_id
			AND $loot_drop_entries_table.item_id=$items_table.id";
			
	if ($item_custom_loot == TRUE) {
		$query .= " AND $loot_table_entries.lootdrop_id NOT BETWEEN 300000 AND 399999";
	}
    if ($discovered_items_only == TRUE) {
        $query .= " AND $discovered_items_table.item_id=$items_table.id";
    }
	$query .= " AND $loot_table_entries.probability > 0";
	$query .= " AND $loot_drop_entries_table.chance > 0";
	$query .= " ORDER BY $items_table.Name ASC";
	
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        if ($show_npc_drop_chances == TRUE) {
            $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>When killed, this NPC drops</h2><br/>";
        } else {
            $print_buffer .= " <td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>When killed, this NPC can drop</h2><br/>";
        }
		if ($show_vegas_drops == TRUE) {
			$vegaserarangemin = 1;
			$vegaserarangemax = 4;
			$vegasraidonly = 0;
			if ($id >= 110000 && $id < 130000) { #Velious
				$vegaserarangemin = 2;
				$vegaserarangemax = 5;
			}
			elseif ($id >= 150000 && $id < 182000) { #Luclin
				$vegaserarangemin = 3;
				$vegaserarangemax = 5;
			}
			elseif ($id >= 200000 && $id < 224000) { #PoP
				$vegaserarangemin = 4;
				$vegaserarangemax = 5;
			}
			if ($npc["raid_target"]) {
				if ($npc["difficulty"] > 1131570) {
					$vegasraidonly = 1;
				} else {
					$vegasraidonly = "";
				}
				$print_buffer .= "<u><b><a href='http://vegaseq.com/Allaclone/?a=items_search&&a=items&iname=&iclass=0&irace=0&islot=0&istat1=&istat1comp=%3E%3D&istat1value=&istat2=&istat2comp=%3E%3D&istat2value=&iresists=&iresistscomp=%3E%3D&iresistsvalue=&imod=&imodcomp=%3E%3D&imodvalue=&iskillmod=&iskillmodcomp=%3E%3D&iskillmodvalue=&ibardskillmod=&ibardskillmodcomp=%3E%3D&ibardskillmodvalue=&itype=-1&ideity=0&ieffect=&ieffectlevelcomp=%3E%3D&ieffectlevel=&ieffecttype=&iminlevel=0&ireqlevel=0&iminreclevel=0&imaxreclevel=0&ibeingsold=-1&itier=-1&ilowprice=&ihighprice=&ivegas=2&ishardvalue=0&inodrop=0&iraiditemsonly=" . $vegasraidonly . "&ieramin=" . $vegaserarangemin . "&ieramax=" . $vegaserarangemax . "&imindiff=" . ($npc["difficulty"] * .5) . "&imaxdiff=" . ($npc["difficulty"] * 1.5) . "&isearch=1'>Click here for Vegas drops</a></b></u>";
				$print_buffer .= "<br> --------- <br>";
			}
			elseif ($npc["rare_spawn"]) {
				$print_buffer .= "<u><b><a href='http://vegaseq.com/Allaclone/?a=items_search&&a=items&iname=&iclass=0&irace=0&islot=0&istat1=&istat1comp=%3E%3D&istat1value=&istat2=&istat2comp=%3E%3D&istat2value=&iresists=&iresistscomp=%3E%3D&iresistsvalue=&imod=&imodcomp=%3E%3D&imodvalue=&iskillmod=&iskillmodcomp=%3E%3D&iskillmodvalue=&ibardskillmod=&ibardskillmodcomp=%3E%3D&ibardskillmodvalue=&itype=-1&ideity=0&ieffect=&ieffectlevelcomp=%3E%3D&ieffectlevel=&ieffecttype=&iminlevel=0&ireqlevel=0&iminreclevel=0&imaxreclevel=0&ibeingsold=-1&itier=-1&ilowprice=&ihighprice=&ivegas=2&ishardvalue=0&inodrop=0&iraiditemsonly=" . $vegasraidonly . "&ieramin=" . $vegaserarangemin . "&ieramax=" . $vegaserarangemax . "&imindiff=" . ($npc["difficulty"] * .5) . "&imaxdiff=" . ($npc["difficulty"] * 1.35) . "&isearch=1'>Click here for Vegas drops</a></b></u>";
				$print_buffer .= "<br> --------- <br>";
			} else {
				$print_buffer .= "<u><b><a href='http://vegaseq.com/Allaclone/?a=items_search&&a=items&iname=&iclass=0&irace=0&islot=0&istat1=&istat1comp=%3E%3D&istat1value=&istat2=&istat2comp=%3E%3D&istat2value=&iresists=&iresistscomp=%3E%3D&iresistsvalue=&imod=&imodcomp=%3E%3D&imodvalue=&iskillmod=&iskillmodcomp=%3E%3D&iskillmodvalue=&ibardskillmod=&ibardskillmodcomp=%3E%3D&ibardskillmodvalue=&itype=-1&ideity=0&ieffect=&ieffectlevelcomp=%3E%3D&ieffectlevel=&ieffecttype=&iminlevel=0&ireqlevel=0&iminreclevel=0&imaxreclevel=0&ibeingsold=-1&itier=-1&ilowprice=&ihighprice=&ivegas=2&ishardvalue=0&inodrop=0&iraiditemsonly=" . $vegasraidonly . "&ieramin=" . $vegaserarangemin . "&ieramax=" . $vegaserarangemax . "&imindiff=" . ($npc["difficulty"] * .5) . "&imaxdiff=" . ($npc["difficulty"] * 1.2) . "&isearch=1'>Click here for Vegas drops</a></b></u>";
				$print_buffer .= "<br> --------- <br>";
			}
			$print_buffer .= "<br>";
		}
        $ldid = 0;
        while ($row = mysqli_fetch_array($result)) {
            if ($show_npc_drop_chances == TRUE) {
                if ($ldid != $row["lootdrop_id"]) {
                    $print_buffer .= "</ol><li>With a probability of " . $row["probability"] . "% (multiplier : " . $row["multiplier"] . "): </li><ol>";
                    $ldid = $row["lootdrop_id"];
                }
            }
			if ($show_npc_drop_chances_as_rarity == TRUE) {
				#if ($ldid != $row["lootdrop_id"]) {
                #    $print_buffer .= "</ol>";
				#	$print_buffer .= "----------------";
				#	$print_buffer .= "<ol>";
                #    $ldid = $row["lootdrop_id"];
                #}
			}
            $print_buffer .= "<li>" . get_item_icon_from_id($row["id"]) . " <a href='?a=item&id=" . $row["id"] . "'>" . $row["Name"] . "</a>";
            $print_buffer .= " (" . $dbitypes[$row["itemtype"]] . ")";
            if ($show_npc_drop_chances == TRUE) {
                $print_buffer .= " - " . $row["chance"] . "%";
                $print_buffer .= " (" . ($row["chance"] * $row["probability"] / 100) . "% Global)";
				if ($row["LootDropMult"] > 1) {
					$print_buffer .= " x" . $row["LootDropMult"];
				}
            }
			if ($show_npc_drop_chances_as_rarity == TRUE) {
				$print_buffer .= " - " . $row["chance"] . "";
				if ($row["LootDropMult"] > 1) {
					$print_buffer .= " x" . $row["LootDropMult"];
				}
			}
            $print_buffer .= "</li>";
        }
        $print_buffer .= "</td></tr></table></td>";
    } else {
		$print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>No item drops found.</h2><p>";
        $print_buffer .= "</td></tr></table></td>";
    }
}

if ($npc["merchant_id"] > 0) {
    $query = "
        SELECT
            $items_table.id,
            $items_table.Name,
            $items_table.price,
            $items_table.ldonprice,
            $items_table.icon,
			$merchant_list_table.alt_currency_cost
        FROM
            $items_table,
            $merchant_list_table
        WHERE
            $merchant_list_table.merchantid = " . $npc["merchant_id"] . "
        AND $merchant_list_table.item = $items_table.id
        ORDER BY
            $items_table.Name ASC
    ";
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>This NPC sells</h2><p>";
        while ($row = mysqli_fetch_array($result)) {
            $print_buffer .= "<li style='list-style-type:none;margin-left:15px;'><a href='?a=item&id=" . $row["id"] . "'>" .
                '<img src="' . $icons_url . $row['icon'] . '.gif" align="center" border="1" style="border-radius:5px;height:15px;width:auto"> ' .
                 $row["Name"] .
                 "</a> ";
            if ($npc["class"] == 41) {
                $print_buffer .= "(" . price($row["price"]) . ")";
            } // NPC is a shopkeeper
            if ($npc["class"] == 61) {
                $print_buffer .= "(" . $row["ldonprice"] . " points)";
            } // NPC is a LDON merchant
			if ($npc["class"] == 70) $print_buffer .= " (" . $row["alt_currency_cost"] . "" . get_item_icon_from_id($altcurrencyid[$npc["alt_currency_id"]]) . ")"; // NPC is a Alternate Currency merchant
            $print_buffer .= "</li>";
        }
        $print_buffer .= "</td></tr></table></td>";
    }
}

$print_buffer .= "</tr></table>";


$print_buffer .= "</td>";

$print_buffer .= "</td></tr></table>";
$print_buffer .= "</td></tr></table>";
$print_buffer .= "</td></tr></table>";


?>
