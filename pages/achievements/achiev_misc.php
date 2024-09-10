<?php

$itemtype = (isset($_GET['itemtype']) ? $_GET['itemtype'] : "null");
$order = (isset($_GET['order']) ? addslashes($_GET["order"]) : "SkillName ASC");

$page_title = "Miscellaneous Achievements";
$print_buffer .= "<table class=''><tr valign=top><td>";
$print_buffer .= "<h1>Choose a category</h1><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=death id='death'>Death Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=pvp id='death'>PVP Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=skills id='death'>Skill Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=tradeskills id='death'>Tradeskill Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=languages id='death'>Language Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=delevel id='death'>Delevel Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=quests id='death'>Quest Achievements</a>";
$print_buffer .= "</ul>";

if ($itemtype == "null") {
	
	$CountItems = 0;
	$CountTokens = 0;
	$CountShards = 0;
	$CountUniqueItems = 0;
	$platinum  = 0;
	$gold = 0;
	$silver = 0;
	$copper = 0;
	$splatinum  = 0;
	$sgold = 0;
	$ssilver = 0;
	$scopper = 0;
	$bbplatinum  = 0;
	$bgold = 0;
	$bsilver = 0;
	$bcopper = 0;
	$CountValeen = 0;
	$CountCharacters = 0;
	$CountAccounts = 0;
	$AverageLevel = 0;
	$corpseplatinum  = 0;
	$corpsegold = 0;
	$corpsesilver = 0;
	$corpsecopper = 0;
	
	#$query = "
	#	SELECT COUNT(distinct kr.fight_id) AS Count, COUNT(distinct kr.npc_id) AS UniqueCount
	#	FROM qs_player_npc_kill_record kr
	#	INNER JOIN qs_player_npc_kill_record_entries kre ON kr.fight_id = kre.event_id
	#	INNER JOIN character_data cd ON cd.id = kre.char_id
	#	INNER JOIN account a ON a.id = cd.account_id
	#	WHERE a.`status` < 20
	#";
	#$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
	#
	#$print_buffer .= 
	#"
	#	<table class='container_div display_table'		style='width:500px'>
	#	<td style='font-weight:bold' align=center>NPCs Killed</td>
	#	<td style='font-weight:bold' align=center>Unique NPCs Killed</td>
	#";
	#while ($row = mysqli_fetch_array($result)) {
	#	$print_buffer .=
	#	"
	#		<tr>
	#			<td align=center>" . number_format($row["Count"]) . " <img src='$icons_url\item_1070.png' width='15px' height='15px'/></td>
	#			<td align=center>" . number_format($row["UniqueCount"]) . " <img src='$icons_url\item_1070.png' width='15px' height='15px'/></td>
	#		</tr>
	#	";
	#}
	#$print_buffer .= "</table>";
	
	#$query = "
	#	SELECT 
	#		CAST(d.`value` AS INT) AS Count
	#	FROM 
	#		$data_buckets_table d
	#	WHERE d.`key` LIKE 'TotalRandomDrops'
	#	-- WHERE d.`id` = 6
	#";
	$query = "
		SELECT COUNT(r.item_id) AS Count
		FROM rng_items r
	";
	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
	
	while ($row = mysqli_fetch_array($result)) {
		$CountItems = number_format($row["Count"]);
	}
	
	#$query = "
	#	SELECT COUNT(DISTINCT r.item_id) AS Count
	#	FROM rng_items r
	#	INNER JOIN character_data cd ON cd.id = r.charid
	#	-- INNER JOIN account a ON a.id = cd.account_id
	#	-- WHERE a.`status` < 20
	#	WHERE cd.`account_id` > 2 AND cd.`account_id` != 459 AND cd.`account_id` != 595 AND cd.`account_id` != 597 AND cd.`account_id` != 808 AND cd.`account_id` != 861 AND cd.`account_id` != 990 AND cd.`account_id` != 959
	#	AND cd.`id` != 863 AND cd.`id` != 887 
	#";
	$query = "
		SELECT COUNT(DISTINCT r.item_id) AS Count
		FROM rng_items r
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$CountUniqueItems = number_format($row["Count"]);
	}
	#$query = "
	#	SELECT 
	#		CAST(d.`value` AS INT) AS Count
	#	FROM 
	#		$data_buckets_table d
	#	WHERE d.`key` LIKE 'EpicTokenCount'
	#	-- WHERE d.`id` = 26029
	#";
	$query = "
		SELECT COUNT(r.npc_id) AS Count
		FROM rng_tokens r
	";
	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$CountTokens = number_format($row["Count"]);
	}
	
	#$query = "
	#	SELECT 
	#		CAST(d.`value` AS INT) AS Count
	#	FROM 
	#		$data_buckets_table d
	#	WHERE d.`key` LIKE 'ShardsDropped'
	#	-- WHERE d.`id` = 118007
	#";
	$query = "
		SELECT SUM(r.shard_amount) AS Count
		FROM rng_shards r
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$CountShards = number_format($row["Count"]);
	}
	
	$query = "
		SELECT 
			CAST(d.`value` AS INT) AS Count
		FROM 
			$data_buckets_table d
		WHERE d.`key` LIKE 'TotalCashDropped'
		-- WHERE d.`id` = 7
	";
	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		
		$item_price = $row["Count"];
		$item_value = "";
		
		
		if ($item_price > 1000)
			$platinum = ((int)($item_price / 1000));
		
		if (($item_price - ($platinum * 1000)) > 100)
			$gold = ((int)(($item_price - ($platinum * 1000)) / 100));
		
		if (($item_price - ($platinum * 1000) - ($gold * 100)) > 10)
			$silver = ((int)(($item_price - ($platinum * 1000) - ($gold * 100)) / 10));
		
		if (($item_price - ($platinum * 1000) - ($gold * 100) - ($silver * 10)) > 0)
			$copper = ($item_price - ($platinum * 1000) - ($gold * 100) - ($silver * 10));
	}
	
	$query = "
		SELECT 
			CAST(d.`value` AS INT) AS Count
		FROM 
			$data_buckets_table d
		WHERE d.`key` LIKE 'CashSpent'
		-- WHERE d.`id` = 117943
	";
	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$item_price = $row["Count"];
		$item_value = "";
		
		
		if ($item_price > 1000)
			$bplatinum = ((int)($item_price / 1000));
		
		if (($item_price - ($bplatinum * 1000)) > 100)
			$bgold = ((int)(($item_price - ($bplatinum * 1000)) / 100));
		
		if (($item_price - ($bplatinum * 1000) - ($bgold * 100)) > 10)
			$bsilver = ((int)(($item_price - ($bplatinum * 1000) - ($bgold * 100)) / 10));
		
		if (($item_price - ($bplatinum * 1000) - ($bgold * 100) - ($bsilver * 10)) > 0)
			$bcopper = ($item_price - ($bplatinum * 1000) - ($bgold * 100) - ($bsilver * 10));
	}
	
	$query = "
		SELECT 
			CAST(d.`value` AS INT) AS Count
		FROM 
			$data_buckets_table d
		WHERE d.`key` LIKE 'CashReceived'
		-- WHERE d.`id` = 117959
	";
	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$item_price = $row["Count"];
		$item_value = "";
		
		
		if ($item_price > 1000)
			$splatinum = ((int)($item_price / 1000));
		
		if (($item_price - ($splatinum * 1000)) > 100)
			$sgold = ((int)(($item_price - ($splatinum * 1000)) / 100));
		
		if (($item_price - ($splatinum * 1000) - ($sgold * 100)) > 10)
			$ssilver = ((int)(($item_price - ($splatinum * 1000) - ($sgold * 100)) / 10));
		
		if (($item_price - ($splatinum * 1000) - ($sgold * 100) - ($ssilver * 10)) > 0)
			$scopper = ($item_price - ($splatinum * 1000) - ($sgold * 100) - ($ssilver * 10));
	}
	
	$query = "
		SELECT 
			CAST(d.`value` AS INT) AS Count
		FROM 
			$data_buckets_table d
		WHERE d.`key` LIKE 'ShardsSpent'
		-- WHERE d.`id` = 117947
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$CountValeen = number_format($row["Count"]);
	}
	
	$query = "
		SELECT COUNT(cd.id) AS Count
		FROM $character_table cd
		-- INNER JOIN account a ON a.id = cd.account_id
		WHERE cd.name NOT LIKE '%-deleted-%'
		-- AND a.`status` = 0
		AND cd.`account_id` > 2 AND cd.`account_id` != 595 AND cd.`account_id` != 597 AND cd.`account_id` != 808 AND cd.`account_id` != 861 AND cd.`account_id` != 959 AND cd.`account_id` != 990 AND cd.`account_id` != 1028 AND cd.`account_id` != 1243 AND cd.`account_id` != 1251
		AND cd.`id` != 863 AND cd.`id` != 887
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$CountCharacters = number_format($row["Count"]);
	}
	
	$query = "
		SELECT COUNT(a.id) AS Count
		FROM account a
		WHERE a.`status` = 0
		AND a.charname != ''
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$CountAccounts = number_format($row["Count"]);
	}
	
	$query = "
		SELECT AVG(cd.level) AS Count
		FROM $character_table cd
		-- INNER JOIN account a ON a.id = cd.account_id
		WHERE cd.name NOT LIKE '%-deleted-%'
		-- AND a.`status` = 0
		AND cd.`account_id` > 2 AND cd.`account_id` != 595 AND cd.`account_id` != 597 AND cd.`account_id` != 808 AND cd.`account_id` != 861 AND cd.`account_id` != 959 AND cd.`account_id` != 990 AND cd.`account_id` != 1028 AND cd.`account_id` != 1243 AND cd.`account_id` != 1251
		AND cd.`id` != 863 AND cd.`id` != 887
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$AverageLevel = number_format($row["Count"]);
	}
	
	$query = "
		SELECT 
			(
				SUM(v.Plat) * 1000
				+ 
				SUM(v.Gold) * 100 
				+ 
				SUM(v.Silver) * 10 
				+ 
				SUM(v.Copper)
			) AS Count
		FROM vw_qs_merchant_transactions v
		WHERE v.ItemID BETWEEN 550102 AND 550118
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$item_price = $row["Count"];
		$item_value = "";
		
		
		if ($item_price > 1000)
			$corpseplatinum = ((int)($item_price / 1000));
		
		if (($item_price - ($corpseplatinum * 1000)) > 100)
			$corpsegold = ((int)(($item_price - ($corpseplatinum * 1000)) / 100));
		
		if (($item_price - ($corpseplatinum * 1000) - ($corpsegold * 100)) > 10)
			$corpsesilver = ((int)(($item_price - ($corpseplatinum * 1000) - ($corpsegold * 100)) / 10));
		
		if (($item_price - ($corpseplatinum * 1000) - ($corpsegold * 100) - ($corpsesilver * 10)) > 0)
			$corpsecopper = ($item_price - ($corpseplatinum * 1000) - ($corpsegold * 100) - ($corpsesilver * 10));
	}
	
	$query = "
		SELECT 
				cd.`id` AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-3), 4) AS INT) * ((60 * 60 * 24 * 31) * 12) -- Year
				+
				CASE
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jan%' THEN (60 * 60 * 24 * 31) * 1 -- Month 
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Feb%' THEN (60 * 60 * 24 * 31) * 2
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Mar%' THEN (60 * 60 * 24 * 31) * 3
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Apr%' THEN (60 * 60 * 24 * 31) * 4
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%May%' THEN (60 * 60 * 24 * 31) * 5
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jun%' THEN (60 * 60 * 24 * 31) * 6
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jul%' THEN (60 * 60 * 24 * 31) * 7
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Aug%' THEN (60 * 60 * 24 * 31) * 8
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Sep%' THEN (60 * 60 * 24 * 31) * 9
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Oct%' THEN (60 * 60 * 24 * 31) * 10
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Nov%' THEN (60 * 60 * 24 * 31) * 11
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Dec%' THEN (60 * 60 * 24 * 31) * 12 -- Month
				END 
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-15), 2) AS INT) * (60 * 60 * 24) -- Day
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-12), 2) AS INT) * (60 * 60) -- Hours
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-9), 2) AS INT) * 60 -- Minutes
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-6), 2) AS INT) -- Seconds
				AS TimeScore
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstCharacter' 
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$character_id = $row["CharID"];
		$character_name = $row["CharName"];
		$creation_time = $row["Time"];
	}
	
	$query = "
		SELECT 
				g.`id` AS GuildID,
				g.`name` AS GuildName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-3), 4) AS INT) * ((60 * 60 * 24 * 31) * 12) -- Year
				+
				CASE
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jan%' THEN (60 * 60 * 24 * 31) * 1 -- Month 
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Feb%' THEN (60 * 60 * 24 * 31) * 2
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Mar%' THEN (60 * 60 * 24 * 31) * 3
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Apr%' THEN (60 * 60 * 24 * 31) * 4
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%May%' THEN (60 * 60 * 24 * 31) * 5
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jun%' THEN (60 * 60 * 24 * 31) * 6
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jul%' THEN (60 * 60 * 24 * 31) * 7
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Aug%' THEN (60 * 60 * 24 * 31) * 8
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Sep%' THEN (60 * 60 * 24 * 31) * 9
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Oct%' THEN (60 * 60 * 24 * 31) * 10
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Nov%' THEN (60 * 60 * 24 * 31) * 11
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Dec%' THEN (60 * 60 * 24 * 31) * 12 -- Month
				END 
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-15), 2) AS INT) * (60 * 60 * 24) -- Day
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-12), 2) AS INT) * (60 * 60) -- Hours
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-9), 2) AS INT) * 60 -- Minutes
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-6), 2) AS INT) -- Seconds
				AS TimeScore
			FROM 
				$data_buckets_table d
			INNER JOIN $guild_table g ON g.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstToEnterPoTime' 
	";

	$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

	while ($row = mysqli_fetch_array($result)) {
		$guild_id = $row["GuildID"];
		$guild_name = $row["GuildName"];
		$achievement_time = $row["Time"];
	}
	
	$print_buffer .= 
	"
		<table class='container_div display_table'		style='width:500px'>
		<td style='font-weight:bold' align=left><u> Random Drops </u></td>
		<td style='font-weight:bold' align=left><u> Unique Items </u></td>
		<td style='font-weight:bold' align=center><u> Tokens </u></td>
		<td style='font-weight:bold' align=right><u> Shards </u></td>
	";
	
	$print_buffer .=
	"
		<tr>
			<td style='font-weight:bold' align=left> " . $CountItems . " <img src='$icons_url\item_2011.png' width='15px' height='15px'/> </td>
			<td style='font-weight:bold' align=left> " . $CountUniqueItems . " <img src='$icons_url\item_2011.png' width='15px' height='15px'/> </td>
			<td style='font-weight:bold' align=center> " . $CountTokens . " <img src='$icons_url\item_647.png' width='15px' height='15px'/> </td>
			<td style='font-weight:bold' align=right> " . $CountShards . " <img src='$icons_url\item_2240.png' width='50px' height='10px'/> </td>
			</td>
		</tr>
	";
	
	$print_buffer .= "</table>";
	
	$print_buffer .= 
	"
		<table class='container_div display_table'		style='width:500px'>
		<td style='font-weight:bold' align=left><u> Given To Vendors </u></td>
		<td style='font-weight:bold' align=center><u> Dropped Cash </u></td>
		<td style='font-weight:bold' align=right><u> Received From Vendors </u></td>
	";
	
	$print_buffer .=
	"
		<tr>
			<td style='font-weight:bold' align=left> " . number_format($bplatinum) . " <img src='$icons_url\item_644.png' width='10px' height='10px'/>
			" . number_format($bgold) . " <img src='$icons_url\item_645.png' width='10px' height='10px'/>
			" . number_format($bsilver) . " <img src='$icons_url\item_646.png' width='10px' height='10px'/>
			" . number_format($bcopper) . " <img src='$icons_url\item_647.png' width='10px' height='10px'/>
			</td>
			<td style='font-weight:bold' align=center> " . number_format($platinum) . " <img src='$icons_url\item_644.png' width='10px' height='10px'/>
			" . number_format($gold) . " <img src='$icons_url\item_645.png' width='10px' height='10px'/>
			" . number_format($silver) . " <img src='$icons_url\item_646.png' width='10px' height='10px'/>
			" . number_format($copper) . " <img src='$icons_url\item_647.png' width='10px' height='10px'/> </td>
			</td>
			<td style='font-weight:bold' align=right> " . number_format($splatinum) . " <img src='$icons_url\item_644.png' width='10px' height='10px'/>
			" . number_format($sgold) . " <img src='$icons_url\item_645.png' width='10px' height='10px'/>
			" . number_format($ssilver) . " <img src='$icons_url\item_646.png' width='10px' height='10px'/>
			" . number_format($scopper) . " <img src='$icons_url\item_647.png' width='10px' height='10px'/>
			</td>
		</tr>
	";
	
	$print_buffer .= 
	"
		<tr>
			<td style='font-weight:bold' text-indent: 400px> " . $CountValeen . " <img src='$icons_url\item_2240.png' width='50px' height='10px'/></td>
		</tr>
	";
	
	$print_buffer .= "</table>";
	
	$print_buffer .= 
	"
		<table class='container_div display_table'		style='width:500px'>
		<td style='font-weight:bold' align=left><u> Total Accounts </u></td>
		<td style='font-weight:bold' align=center><u> Total Characters </u></td>
		<td style='font-weight:bold' align=right><u> Average Level </u></td>
	";
	
	$print_buffer .=
	"
		<tr>
			<td style='font-weight:bold' align=left> " . $CountAccounts . " </td>
			<td style='font-weight:bold' align=center> " . $CountCharacters . " </td>
			<td style='font-weight:bold' align=right> " . $AverageLevel . "  </td>
		</tr>
	";
	
	$print_buffer .= "</table>";
	
	$print_buffer .= 
	"
		<table class='container_div display_table'		style='width:500px'>
		<td style='font-weight:bold' align=left><u> Given to Corpse Summoners </u></td>
	";
	
	$print_buffer .=
	"
		<tr>
			<td style='font-weight:bold' align=left> " . number_format($corpseplatinum) . " <img src='$icons_url\item_644.png' width='10px' height='10px'/>
			" . number_format($corpsegold) . " <img src='$icons_url\item_645.png' width='10px' height='10px'/>
			" . number_format($corpsesilver) . " <img src='$icons_url\item_646.png' width='10px' height='10px'/>
			" . number_format($corpsecopper) . " <img src='$icons_url\item_647.png' width='10px' height='10px'/>
			</td>
		</tr>
	";
	
	$print_buffer .= "</table>";
	
	$print_buffer .= 
	"
		<table class='container_div display_table'		style='width:500px'>
		<td style='font-weight:bold' align=left><u> First Character Created</u></td>
	";
	
	$print_buffer .=
	"
		<tr>
			<td style='font-weight:bold' align=left><a href='/charbrowser/index.php?page=character&char=" . $character_name . "'>" . $character_name . "</a>
			on " . $creation_time . "
			</td>
		</tr>
	";
	
	$print_buffer .= "</table>";
	
	$print_buffer .= 
	"
		<table class='container_div display_table'		style='width:500px'>
		<td style='font-weight:bold' align=left><u> First Guild To Enter <a href='?a=zone&name=potimea'>The Plane of Time</a></u></td>
	";
	
	$print_buffer .=
	"
		<tr>
			<td style='font-weight:bold' align=left><a href='/charbrowser/index.php?page=guild&guild=" . $guild_name . "'>" . $guild_name . "</a>
			on " . $achievement_time . "
			</td>
		</tr>
	";
	
	$print_buffer .= "</table>";
	
	$print_buffer .= "</td><td width=0% nowrap>";
	$print_buffer .= "</td></tr></table>";
}

if (isset($itemtype) && $itemtype != "null") {
	if ($itemtype == "death") {
		$print_buffer .= '<h1>' . "Death Achievements" . '</h1>';
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstServerDeath'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First Death</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`key`, 12, 10)
			WHERE d.`key` LIKE 'DeathCount-%'
			ORDER BY Count DESC
			LIMIT 1
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Most Deaths</td>
			<td style='font-weight:bold' align=center>Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'Die')+3,INSTR(d.`key`,'Times')-INSTR(d.`key`,'Die')-3) AS INT) AS 'Count',
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstToDie%'
			ORDER BY `Count` ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>To Die x Times</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count',
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'ServerDeath#%'
			ORDER BY Count ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Death</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>#" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstPlayerToBeDT'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First To Be Death Touched</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			WHERE d.`key` LIKE 'ServerDeathCount'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Total Death Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "pvp") {
		$print_buffer .= '<h1>' . "PVP Achievements" . '</h1>';
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstPVPKill'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First PVP Kill</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`key`, 14, 10)
			WHERE d.`key` LIKE 'PVPKillCount%'
			ORDER BY Count DESC, d.`id` ASC
			LIMIT 1
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Most Kills</td>
			<td style='font-weight:bold' align=center>Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count',
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstPVPKillCount#%'
			ORDER BY `Count` ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>To Get x Kills</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count',
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'ServerPVPKillCount#%'
			ORDER BY Count ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Kill</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>#" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			WHERE d.`key` LIKE 'ServerPVPKillCount'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Total PVP Kill Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "skills") {
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
		";
		
		$print_buffer .= '<h1>' . "First Maxed Skill Achievements" . '</h1>';
		$query = "
			SELECT 
				CASE
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 9
						THEN 'Bind Wound [210]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 16
						THEN 'Disarm [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 17
						THEN 'Disarm Traps [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 25
						THEN 'Feign Death [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 27
						THEN 'Forage [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 29
						THEN 'Hide [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 32
						THEN 'Mend [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 35
						THEN 'Pick Lock [210]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 39
						THEN 'Safe Fall [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 40
						THEN 'Sense Heading [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 42
						THEN 'Sneak [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 48
						THEN 'Pick Pockets [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 50
						THEN 'Swimming [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 51
						THEN 'Throwing [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 53
						THEN 'Tracking [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 55
						THEN 'Fishing [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 62
						THEN 'Sense Traps [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 66
						THEN 'Alcohol Tolerance [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 67
						THEN 'Begging [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 71
						THEN 'Intimidation [200]'
					ELSE 'None'
				END AS SkillName,
				cd.id AS CharID,
					CASE
						WHEN cd.`name` LIKE '%-deleted-%'
							THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
						ELSE cd.`name` 
					END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-3), 4) AS INT) * ((60 * 60 * 24 * 31) * 12) -- Year
				+
				CASE
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jan%' THEN (60 * 60 * 24 * 31) * 1 -- Month 
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Feb%' THEN (60 * 60 * 24 * 31) * 2
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Mar%' THEN (60 * 60 * 24 * 31) * 3
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Apr%' THEN (60 * 60 * 24 * 31) * 4
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%May%' THEN (60 * 60 * 24 * 31) * 5
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jun%' THEN (60 * 60 * 24 * 31) * 6
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jul%' THEN (60 * 60 * 24 * 31) * 7
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Aug%' THEN (60 * 60 * 24 * 31) * 8
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Sep%' THEN (60 * 60 * 24 * 31) * 9
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Oct%' THEN (60 * 60 * 24 * 31) * 10
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Nov%' THEN (60 * 60 * 24 * 31) * 11
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Dec%' THEN (60 * 60 * 24 * 31) * 12 -- Month
				END 
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-15), 2) AS INT) * (60 * 60 * 24) -- Day
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-12), 2) AS INT) * (60 * 60) -- Hours
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-9), 2) AS INT) * 60 -- Minutes
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-6), 2) AS INT) -- Seconds
				AS TimeScore
			FROM data_buckets d
			INNER JOIN character_data cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstSkillMax-%'
			ORDER BY $order
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		$print_buffer .= "
		<table class='display_table datatable container_div'><tr>
		<td style='font-weight:bold' align=center><u><b><a href=?a=achiev_misc&itemtype=skills&order=SkillName>Skill</a></b></u></td>
		<td style='font-weight:bold' align=center><u><b><a href=?a=achiev_misc&itemtype=skills&order=CharName>Player</a></b></u></td>
		<td style='font-weight:bold' align=right><u><b><a href=?a=achiev_misc&itemtype=skills&order=TimeScore>Time</a></b></u></td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left>". $row["SkillName"] . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "tradeskills") {
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
		";
		
		$print_buffer .= '<h1>' . "First Maxed Tradeskill Achievements" . '</h1>';
		$query = "
			SELECT 
				CASE
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 56
						THEN 'Make Poison [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 57
						THEN 'Tinkering [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 58
						THEN 'Research [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 59
						THEN 'Alchemy [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 60
						THEN 'Baking [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 61
						THEN 'Tailoring [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 63
						THEN 'Blacksmithing [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 64
						THEN 'Fletching [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 65
						THEN 'Brewing [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 68
						THEN 'Jewelry Making [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 69
						THEN 'Pottery [250]'
					ELSE 'None'
				END AS SkillName,
				cd.id AS CharID,
					CASE
						WHEN cd.`name` LIKE '%-deleted-%'
							THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
						ELSE cd.`name` 
					END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-3), 4) AS INT) * ((60 * 60 * 24 * 31) * 12) -- Year
				+
				CASE
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jan%' THEN (60 * 60 * 24 * 31) * 1 -- Month 
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Feb%' THEN (60 * 60 * 24 * 31) * 2
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Mar%' THEN (60 * 60 * 24 * 31) * 3
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Apr%' THEN (60 * 60 * 24 * 31) * 4
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%May%' THEN (60 * 60 * 24 * 31) * 5
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jun%' THEN (60 * 60 * 24 * 31) * 6
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jul%' THEN (60 * 60 * 24 * 31) * 7
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Aug%' THEN (60 * 60 * 24 * 31) * 8
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Sep%' THEN (60 * 60 * 24 * 31) * 9
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Oct%' THEN (60 * 60 * 24 * 31) * 10
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Nov%' THEN (60 * 60 * 24 * 31) * 11
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Dec%' THEN (60 * 60 * 24 * 31) * 12 -- Month
				END 
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-15), 2) AS INT) * (60 * 60 * 24) -- Day
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-12), 2) AS INT) * (60 * 60) -- Hours
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-9), 2) AS INT) * 60 -- Minutes
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-6), 2) AS INT) -- Seconds
				AS TimeScore
			FROM data_buckets d
			INNER JOIN character_data cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstTradeskillMax-%'
			ORDER BY $order
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		$print_buffer .= "
		<table class='display_table datatable container_div'><tr>
		<td style='font-weight:bold' align=center><u><b><a href=?a=achiev_misc&itemtype=tradeskills&order=SkillName>Tradeskill</a></b></u></td>
		<td style='font-weight:bold' align=center><u><b><a href=?a=achiev_misc&itemtype=tradeskills&order=CharName>Player</a></b></u></td>
		<td style='font-weight:bold' align=right><u><b><a href=?a=achiev_misc&itemtype=tradeskills&order=TimeScore>Time</a></b></u></td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left>". $row["SkillName"] . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "languages") {
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
		";
		
		$print_buffer .= '<h1>' . "First Maxed Language Achievements" . '</h1>';
		$query = "
			SELECT 
				SUBSTRING(d.`key`, 6) AS SkillName,
				cd.id AS CharID,
					CASE
						WHEN cd.`name` LIKE '%-deleted-%'
							THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
						ELSE cd.`name` 
					END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-3), 4) AS INT) * ((60 * 60 * 24 * 31) * 12) -- Year
				+
				CASE
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jan%' THEN (60 * 60 * 24 * 31) * 1 -- Month 
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Feb%' THEN (60 * 60 * 24 * 31) * 2
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Mar%' THEN (60 * 60 * 24 * 31) * 3
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Apr%' THEN (60 * 60 * 24 * 31) * 4
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%May%' THEN (60 * 60 * 24 * 31) * 5
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jun%' THEN (60 * 60 * 24 * 31) * 6
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Jul%' THEN (60 * 60 * 24 * 31) * 7
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Aug%' THEN (60 * 60 * 24 * 31) * 8
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Sep%' THEN (60 * 60 * 24 * 31) * 9
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Oct%' THEN (60 * 60 * 24 * 31) * 10
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Nov%' THEN (60 * 60 * 24 * 31) * 11
					WHEN SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) LIKE '%Dec%' THEN (60 * 60 * 24 * 31) * 12 -- Month
				END 
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-15), 2) AS INT) * (60 * 60 * 24) -- Day
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-12), 2) AS INT) * (60 * 60) -- Hours
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-9), 2) AS INT) * 60 -- Minutes
				+
					CAST(SUBSTRING(d.`value`, (LENGTH(d.`value`)-6), 2) AS INT) -- Seconds
				AS TimeScore
			FROM data_buckets d
			INNER JOIN character_data cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'Lang-%'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		$print_buffer .= "
		<table class='display_table datatable container_div'><tr>
		<td style='font-weight:bold' align=center><u><b><a href=?a=achiev_misc&itemtype=languages&order=SkillName>Language</a></b></u></td>
		<td style='font-weight:bold' align=center><u><b><a href=?a=achiev_misc&itemtype=languages&order=CharName>Player</a></b></u></td>
		<td style='font-weight:bold' align=right><u><b><a href=?a=achiev_misc&itemtype=languages&order=TimeScore>Time</a></b></u></td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left>". $row["SkillName"] . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "delevel") {
		$print_buffer .= '<h1>' . "Delevel Achievements" . '</h1>';
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'ServerFirstDelevel'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First Delevel</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'ServerFirstDelevel65to10'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First 65 to 10 Delevel</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "quests") {
		$print_buffer .= '<h1>' . "Quest Achievements" . '</h1>';
		
		$query = "
			SELECT 
				d.`key` AS KeyName,
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				i.id AS ItemID,
				CASE
					WHEN d.`key` LIKE 'FirstGoingPostalTurnIn'
						THEN 'Going Postal'
					WHEN d.`key` LIKE 'FirstLashunGoldTurnIn-22290'
						THEN 'Lashun Novashine (Gold)'
					ELSE i.`Name`
				END AS ItemName
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
				LEFT JOIN $items_table i ON i.id = SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1)
			WHERE d.`key` LIKE 'First%TurnIn%'
			ORDER BY ItemName ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>First Turned In </td>
			<td style='font-weight:bold' align=center>By</td>
			<td style='font-weight:bold' align=center>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			if ($row["KeyName"] == "FirstGoingPostalTurnIn") {
				$url_link = "?a=global_search&q=%20A%20Bardic%20Letter";
			}
			else {
				$url_link = "?a=item&id=" . $row["ItemID"];
			}
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='" . $url_link . "'>" . $row["ItemName"] . "</a></td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CASE
					WHEN d.`key` LIKE '%thQuestGoingPostalTurnIn'
						THEN 'Going Postal'
					ELSE d.`key`
				END AS KeyName,
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time',
				i.id AS ItemID,
				CASE
					WHEN d.`key` LIKE '%thQuestGoingPostalTurnIn'
						THEN 'Going Postal'
					WHEN d.`key` LIKE '%thQuestLashunGoldTurnIn-22290'
						THEN 'Lashun Novashine (Gold)'
					ELSE i.`Name`
				END AS ItemName,
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
				INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
				LEFT JOIN $items_table i ON i.id = SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1)
			WHERE (d.`key` LIKE '%thQuest%TurnIn%' OR d.`key` LIKE '%thQuestGoingPostalTurnIn')
			ORDER BY ItemName ASC, 'Time' ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left># Turned In Achievemnts</td>
			<td style='font-weight:bold' align=center>By</td>
			<td style='font-weight:bold' align=center>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			if ($row["KeyName"] == "Going Postal") {
				$url_link = "?a=global_search&q=%20A%20Bardic%20Letter";
			}
			else {
				$url_link = "?a=item&id=" . $row["ItemID"];
			}
			$print_buffer .=
			"
				<tr>
					<td align=left><a href='" . $url_link . "'>" . $row["ItemName"] . "</a> (#" . number_format($row["Count"]) . ")</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
}

$print_buffer .= "</table>";
$print_buffer .= "</tr>";
?>
