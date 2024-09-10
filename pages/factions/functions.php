<?php

function print_npcs_by_zone($query_result)
{
    if (mysqli_num_rows($query_result) > 0) {
        $current_zone_iteration = "";
        while ($row = mysqli_fetch_array($query_result)) {
            if ($current_zone_iteration != $row["zone"]) {
                if ($current_zone_iteration != "")
                    $print_buffer .= "                  <br/><br/>\n";
                $print_buffer .= "                  <b>in <a href='?a=zone&name=" . $row["zone"] . "'>" . $row["long_name"] . "</a> by </b><br>";
                $current_zone_iteration = $row["zone"];
            }
			if ($row["FactionValue"] > 0) {
				$FactionValue = "<font color=green>+" . $row["FactionValue"] . "<font color=black>";
			} else {
				$FactionValue = "<font color=red>" . $row["FactionValue"] . "<font color=black>";
			}
            $print_buffer .= "<li style='list-style-type:none; margin-left: 15px;'><a href='?a=npc&id=" . $row["id"] . "'>" . str_replace("_", " ", get_npc_name_human_readable($row["name"])) . "</a> (" . $FactionValue . ")</li>\n";
        }
        if ($current_zone_iteration != "")
            $print_buffer .= "                  <br/><br/>\n";
    }
    return $print_buffer;
}



?>