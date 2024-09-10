<?php

$selection = (isset($_GET['selection']) ? $_GET['selection'] : "null");
$order = (isset($_GET['order']) ? addslashes($_GET["order"]) : "SkillName ASC");

$page_title = "Vegas Loot";
$print_buffer .= "<table class=''><tr valign=top><td>";
$print_buffer .= "<h1>Choose a search type</h1><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=vegas_loot&selection=items id='items'>Items</a>";
$print_buffer .= "</ul>";

if (isset($selection) && $selection != "null") {
	if ($selection == "items") {
		$print_buffer .= '<h1>' . "Vegas Items Dropped" . '</h1>';
		$query = "
			SELECT
				cd.`id` AS CharID,
				cd.`name` AS CharName,
				i.`id` AS ItemID,
				i.`name` as ItemName,
				r.`time` as Time
			FROM 
				rng_items r
			INNER JOIN $character_table cd ON cd.`id` = r.`charid`
			INNER JOIN $items_table i ON i.`id` = r.`item_id`
			INNER JOIN $npc_types_table n ON n.`id` = r.`npc_id`
			INNER JOIN $zones_table z ON z.`zoneidnumber` = r.`zone_id`
			WHERE r.`time` > '2024-08-01 00:00:00'
			ORDER BY Time DESC
			
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center><a href='?a=item&id=" . $row["ItemID"] . "'>" . $row["ItemName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}	
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
}

$print_buffer .= "</table>";
$print_buffer .= "</tr>";
?>
