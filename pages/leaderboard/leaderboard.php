<?php

$itemtype = (isset($_GET['itemtype']) ? $_GET['itemtype'] : "null");

$page_title = "Top 20 Overall Leaderboard Rankings";
$print_buffer .= "<table class=''><tr valign=top><td>";
$print_buffer .= "<h1>Choose a Stat</h1><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=leaderboard&itemtype=tophp id='leaderboard'>Top HP</a>";
$print_buffer .= "<li><a href=?a=leaderboard&itemtype=topmana id='leaderboard'>Top Mana</a>";
$print_buffer .= "<li><a href=?a=leaderboard&itemtype=topac id='leaderboard'>Top AC</a>";
$print_buffer .= "<li><a href=?a=leaderboard&itemtype=topaa id='leaderboard'>Top AA</a>";
$print_buffer .= "<li><a href=?a=leaderboard&itemtype=topresists id='leaderboard'>Top Resists</a>";
$print_buffer .= "<li><a href=?a=leaderboard&itemtype=topraidpoints id='leaderboard'>Top Bot/Raid Points</a>";
$print_buffer .= "</ul>";

if (isset($itemtype) && $itemtype != "null") {
	if ($itemtype == "tophp") {
		$print_buffer .= '<h1>' . "Top Overall HP" . '</h1>';
		$query = "
			SELECT 
				c.`id` AS CharID,
				c.`name` AS CharName,
				c.`level` AS CharLevel,
				c.`race` AS CharRace,
				c.`class` AS CharClass,
				c.`hp` AS HP
			FROM $leaderboard_table c
			INNER JOIN $character_table cd ON cd.`id` = c.`id`
			WHERE c.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			AND c.`hp` > 0
			-- AND c.`anon` != 1
			ORDER BY c.`hp` DESC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Character</td>
			<td style='font-weight:bold' align=center>Level</td>
			<td style='font-weight:bold' align=center>Race</td>
			<td style='font-weight:bold' align=center>Class</td>
			<td style='font-weight:bold' align=right>HP</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='/charbrowser/index.php?page=character&char=" . $row["CharName"]. "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["CharLevel"] . "</td>
					<td align=center>" . $row["CharRace"] . "</td>
					<td align=center>" . $row["CharClass"] . "</td>
					<td align=right>" . $row["HP"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "topmana") {
		$print_buffer .= '<h1>' . "Top Overall Mana" . '</h1>';
		$query = "
			SELECT 
				c.`id` AS CharID,
				c.`name` AS CharName,
				c.`level` AS CharLevel,
				c.`race` AS CharRace,
				c.`class` AS CharClass,
				c.`mana` AS Mana
			FROM $leaderboard_table c
			INNER JOIN $character_table cd ON cd.`id` = c.`id`
			WHERE c.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			AND c.`mana` > 0
			-- AND c.`anon` != 1
			ORDER BY c.`mana` DESC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Character</td>
			<td style='font-weight:bold' align=center>Level</td>
			<td style='font-weight:bold' align=center>Race</td>
			<td style='font-weight:bold' align=center>Class</td>
			<td style='font-weight:bold' align=right>Mana</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='/charbrowser/index.php?page=character&char=" . $row["CharName"]. "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["CharLevel"] . "</td>
					<td align=center>" . $row["CharRace"] . "</td>
					<td align=center>" . $row["CharClass"] . "</td>
					<td align=right>" . $row["Mana"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "topac") {
		$print_buffer .= '<h1>' . "Top Overall AC" . '</h1>';
		$query = "
			SELECT 
				c.`id` AS CharID,
				c.`name` AS CharName,
				c.`level` AS CharLevel,
				c.`race` AS CharRace,
				c.`class` AS CharClass,
				c.`ac` AS AC
			FROM $leaderboard_table c
			INNER JOIN $character_table cd ON cd.`id` = c.`id`
			WHERE c.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			AND c.`ac` > 0
			-- AND c.`anon` != 1
			ORDER BY c.`ac` DESC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Character</td>
			<td style='font-weight:bold' align=center>Level</td>
			<td style='font-weight:bold' align=center>Race</td>
			<td style='font-weight:bold' align=center>Class</td>
			<td style='font-weight:bold' align=right>AC</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='/charbrowser/index.php?page=character&char=" . $row["CharName"]. "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["CharLevel"] . "</td>
					<td align=center>" . $row["CharRace"] . "</td>
					<td align=center>" . $row["CharClass"] . "</td>
					<td align=right>" . $row["AC"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "topaa") {
		$print_buffer .= '<h1>' . "Top Overall AA" . '</h1>';
		$query = "
			SELECT 
				c.`id` AS CharID,
				c.`name` AS CharName,
				c.`level` AS CharLevel,
				c.`race` AS CharRace,
				c.`class` AS CharClass,
				c.`aa` AS AA
			FROM $leaderboard_table c
			INNER JOIN $character_table cd ON cd.`id` = c.`id`
			WHERE c.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			AND c.`aa` > 0
			-- AND c.`anon` != 1
			ORDER BY c.`aa` DESC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Character</td>
			<td style='font-weight:bold' align=center>Level</td>
			<td style='font-weight:bold' align=center>Race</td>
			<td style='font-weight:bold' align=center>Class</td>
			<td style='font-weight:bold' align=right>AA</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='/charbrowser/index.php?page=character&char=" . $row["CharName"]. "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["CharLevel"] . "</td>
					<td align=center>" . $row["CharRace"] . "</td>
					<td align=center>" . $row["CharClass"] . "</td>
					<td align=right>" . $row["AA"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "topresists") {
		$print_buffer .= '<h1>' . "Top Overall Resists" . '</h1>';
		$query = "
			SELECT 
				c.`id` AS CharID,
				c.`name` AS CharName,
				c.`level` AS CharLevel,
				c.`race` AS CharRace,
				c.`class` AS CharClass,
				c.`resists` AS Resists
			FROM $leaderboard_table c
			INNER JOIN $character_table cd ON cd.`id` = c.`id`
			WHERE c.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			AND c.`resists` > 0
			-- AND c.`anon` != 1
			ORDER BY c.`resists` DESC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Character</td>
			<td style='font-weight:bold' align=center>Level</td>
			<td style='font-weight:bold' align=center>Race</td>
			<td style='font-weight:bold' align=center>Class</td>
			<td style='font-weight:bold' align=right>Resists</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='/charbrowser/index.php?page=character&char=" . $row["CharName"]. "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["CharLevel"] . "</td>
					<td align=center>" . $row["CharRace"] . "</td>
					<td align=center>" . $row["CharClass"] . "</td>
					<td align=right>" . $row["Resists"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	if ($itemtype == "topraidpoints") {
		$print_buffer .= '<h1>' . "Bot/Raid Point Achievements" . '</h1>';
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(d.`value` AS INT) AS RaidPts
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.`id` = SUBSTRING(d.`key`, 18)
				INNER JOIN $accounts_table a ON a.`id` = cd.`account_id`
			WHERE d.`key` LIKE 'PlayerRaidPoints-%'
			AND a.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			-- AND cd.`anon` != 1
			ORDER BY RaidPts DESC, cd.`id` ASC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Top 20 Point Totals</td>
			<td style='font-weight:bold' align=center>Player</td>
		";
		$i = 1;
		while ($row = mysqli_fetch_array($result)) {
			if ($i == 1) { $medal = "644"; }
			if ($i == 2) { $medal = "645"; }
			if ($i == 3) { $medal = "646"; }
			if ($i == 4) { $medal = "647"; }
			if ($i >= 5) { $medal = "949"; }
			$print_buffer .=
			"
				<tr>
					<td align=left><b><img src='$icons_url\item_$medal.png' width='15px' height='15px'/>[#$i] - " . $row["RaidPts"] . " points<b></td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
				</tr>
			";
			++$i;
		}
		$print_buffer .= "</table>";
		
		$print_buffer .= "<br><br>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				COUNT(d.`key`) AS UniqueKills
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.`id` = SUBSTRING(d.`key`, 16, INSTR(SUBSTRING(d.`key`, 16), '-')+1)
				INNER JOIN $accounts_table a ON a.`id` = cd.`account_id`
			WHERE d.`key` LIKE 'PlayerRaidKill-%'
			AND a.`status` < 100
			-- AND cd.`last_login` > (UNIX_TIMESTAMP() - 7884008.64)
			-- AND cd.`anon` != 1
			GROUP BY cd.`id`
			ORDER BY UniqueKills DESC, cd.`id` ASC
			LIMIT 20
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Top 20 Unique Raid Kill Counts</td>
			<td style='font-weight:bold' align=center>Player</td>
		";
		$i = 1;
		while ($row = mysqli_fetch_array($result)) {
			if ($i == 1) { $medal = "644"; }
			if ($i == 2) { $medal = "645"; }
			if ($i == 3) { $medal = "646"; }
			if ($i == 4) { $medal = "647"; }
			if ($i >= 5) { $medal = "949"; }
			$print_buffer .=
			"
				<tr>
					<td align=left><b><img src='$icons_url\item_$medal.png' width='15px' height='15px'/>[#$i] - " . $row["UniqueKills"] . " kills<b></td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
				</tr>
			";
			++$i;
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
}
?>
