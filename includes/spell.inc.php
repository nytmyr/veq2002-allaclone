<?php

function SpellDescription($spell, $n, $csv = false)
{
    global $dbspelleffects, $dbspelltargets, $dbskills, $dbspellresists, $dbiracenames, $dbstatcaps, $items_table, $spells_table, $server_max_level;

    $print_buffer = '<ul>';

    if ($spell["effectid$n"] != 254 && ($spell["effectid$n"] != 10 || ($spell["effectid$n"] == 10 && $spell["effect_base_value$n"] != 0))) {
        $maxlvl = $spell["effect_base_value$n"];
		$base_value = $spell["effect_base_value$n"];
		$max_value = $spell["max$n"];
        $minlvl = $server_max_level;
        for ($i = 1; $i <= 16; $i++) {
            if ($spell["classes$i"] < $minlvl) {
                $minlvl = $spell["classes$i"];
            }
        }
		$minpotencyvalue;
		$maxpotencylevel = $server_max_level;
		$maxpotencyvalue;
		for ($i = $minlvl; $i <= $server_max_level; $i++) {
			$min        = CalcSpellEffectValue(
				$spell["formula" . $n],
				$spell["effect_base_value$n"],
				$spell["max$n"],
				$i
			);
			if ($base_value < 0 OR $max_value < 0) {
				if ($min < $maxpotencyvalue) {
					$maxpotencyvalue = $min;
					$maxpotencylevel = $i;
				}
			} else {
				if ($min > $maxpotencyvalue) {
					$maxpotencyvalue = $min;
					$maxpotencylevel = $i;
				}
			}
        }
		
		if ($spell["formula" . $n] == 123) {
			$min        = CalcSpellEffectValue(
				$spell["formula$n"],
				$spell["effect_base_value$n"],
				$spell["max$n"],
				1
			);
			$minpotencyvalue = $min;
			$max        = CalcSpellEffectValue(
				$spell["formula$n"],
				$spell["effect_base_value$n"],
				$spell["max$n"],
				$server_max_level
			);
			$maxpotencyvalue = $max;
		}
		else {
			$min        = CalcSpellEffectValue(
				$spell["formula$n"],
				$spell["effect_base_value$n"],
				$spell["max$n"],
				$minlvl
			);
			$minpotencyvalue = $min;
			$max        = CalcSpellEffectValue(
				$spell["formula$n"],
				$spell["effect_base_value$n"],
				$spell["max$n"],
				$server_max_level
			);
			$maxpotencyvalue = $max;
		}
		
		$duration = $spell["buffduration"];
		$durationmin = CalcBuffDuration($minlvl, $spell["buffdurationformula"], $spell["buffduration"]);
		$durationmax = CalcBuffDuration($server_max_level, $spell["buffdurationformula"], $spell["buffduration"]);
        $base_limit = $spell["effect_limit_value$n"];
		$AEDuration = $spell["AEDuration"];
		$min = intval($minpotencyvalue);
		$max = intval($maxpotencyvalue);
		
        if (($min < $max) AND ($max < 0)) {
            $tn  = $min;
            $min = $max;
            $max = $tn;
        }
		
        if ($csv == true) {
            $print_buffer .= ",,";
        } else {
            $print_buffer .= "<b>Effect $n: </b>";
        }
		
        switch ($spell["effectid$n"]) {
            case 3: // Increase Movement (% / 0)
                if ($max < 0) { // Decrease
                    $print_buffer .= "Decrease Movement";
                    if ($min != $max) {
						if ($spell["formula" . $n] == 102) {
							$maxlvlplus = $maxlvl - 1;
							#$print_buffer .= " by " . -$maxlvlplus . "% (L$minlvl) to " . -$spell["max$n"] . "% (L$maxpotencylevel)";
							$print_buffer .= " by " . abs($min) . "% (L$minlvl) to " . abs($max) . "% (L$maxpotencylevel)";
						} else {
							$print_buffer .= " by " . abs($min) . "% (L$minlvl) to " . abs($max) . "% (L$maxpotencylevel)";
						}
                    } else {
                        // $print_buffer .= " by " . abs(100) . "%";
						$print_buffer .= " by " . -($max) . "%";
                    }
                } else {
                    $print_buffer .= "Increase Movement";
                    if ($min != $max) {
                        $print_buffer .= " by " . $min . "% (L$minlvl) to " . ($max) . "% (L$maxpotencylevel)";
                    } else {
                        $print_buffer .= " by " . ($max) . "%";
                    }
                }
                break;
            case 11: // Decrease OR Inscrease AttackSpeed (max/min = percentage of speed / normal speed, IE, 70=>-30% 130=>+30%
                if ($max < 100) { // Decrease
                    $print_buffer .= "Decrease Attack Speed";
                    if ($min != $max) {
                        $print_buffer .= " by " . (100 - $min) . "% (L$minlvl) to " . (100 - $max) . "% (L$maxpotencylevel)";
                    } else {
                        $print_buffer .= " by " . (100 - $max) . "%";
                    }
                } else {
                    $print_buffer .= "Increase Attack Speed";
                    if ($min != $max) {
                        $print_buffer .= " by " . ($min - 100) . "% (L$minlvl) to " . ($max - 100) . "% (L$maxpotencylevel)";
                    } else {
                        $print_buffer .= " by " . ($max - 100) . "%";
                    }
                }
                break;
            case 21: // stun
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                if ($min != $max) {
                    $print_buffer .= " (" . ($min / 1000) . " sec (L$minlvl) to " . ($max / 1000) . " sec (L$maxlvl))";
                } else {
                    $print_buffer .= " (" . ($max / 1000) . " sec)";
                }
                break;
            case 32: // summonitem
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $name         = get_field_result(
                    "name",
                    "SELECT name FROM $items_table WHERE id=" . $spell["effect_base_value$n"]
                );
                if (($name != "") AND ($csv == false)) {
                    $print_buffer .= " <a href=?a=item&id=" . $spell["effect_base_value$n"] . ">$name</a>";
                } else {
                    $print_buffer .= " $name";
                }
                break;
			case 123: // Buff Blocker: Screech
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]] . " ($max)";
				break;
			case 124: // Increase Spell Damage
			case 87: // Increase Magnification
            case 98: // Increase Haste v2
            case 114: // Increase Agro Multiplier
            case 119: // Increase Haste v3
            case 125: // Increase Spell Healing
            case 127: // Increase Spell Haste
            case 128: // Increase Spell Duration
            case 129: // Increase Spell Range
            case 130: // Decrease Spell/Bash Hate
            case 131: // Decrease Chance of Using Reagent
            case 132: // Decrease Spell Mana Cost
            case 158: // Increase Chance to Reflect Spell
            case 168: // Increase Melee Mitigation
            case 169: // Increase Chance to Critical Hit
            case 172: // Increase Chance to Avoid Melee
            case 173: // Increase Chance to Riposte
            case 174: // Increase Chance to Dodge
            case 175: // Increase Chance to Parry
            case 176: // Increase Chance to Dual Wield
            case 177: // Increase Chance to Double Attack
            case 180: // Increase Chance to Resist Spell
            case 181: // Increase Chance to Resist Fear Spell
            case 183: // Increase All Skills Skill Check
            case 184: // Increase Chance to Hit With all Skills
            case 185: // Increase All Skills Damage Modifier
            case 186: // Increase All Skills Minimum Damage Modifier
            case 188: // Increase Chance to Block
            case 200: // Increase Proc Modifier
            case 201: // Increase Range Proc Modifier
            case 216: // Increase Accuracy
            case 227: // Reduce Skill Timer
            case 266: // Add Attack Chance
            case 273: // Increase Critical Dot Chance
            case 294: // Increase Critical Spell Chance
			case 321: // Increase Target's Hate
				$name = $dbspelleffects[$spell["effectid$n"]];
				$min = $spell["effect_base_value$n"];
                $max = $spell["effect_limit_value$n"];
				if ($max < 0 || $min < 0) {
					$name = str_replace("Increase", "Decrease", $name);
				}
				$print_buffer .= $name;
                if ($min != $max) {
					if ($max < 0) {
						$max *= -1;
					}
					if ($min < 0) {
						$min *= -1;
					}
					if ($min > $max) {
						$max = $min;
					}
					#if ($min == 1) {
						$print_buffer .= " by up to $max%";
					#}
					#else {
					#	$print_buffer .= " by $min% (L$minlvl) to $max% (L$maxpotencylevel)";
					#}
                } else {
					if ($spell["effectid$n"] == 98) {
						$max -= 100;
					}
                    $print_buffer .= " by $max%";
                }
                break;
			case 262:
				$min = $spell["effect_base_value$n"];
				if ($min < 0) {
					$print_buffer .= "Decrease ";
				}
				else {
					$print_buffer .= "Increase ";
				}
				$print_buffer .= $dbstatcaps[$spell["effect_limit_value$n"]];
				if ($min < 0) {
					$min *= -1;
				}
				$print_buffer .= " Cap by $min";
				break;
            #case 87: // Increase Magnification
            #case 98: // Increase Haste v2
            #case 114: // Increase Agro Multiplier
            #case 119: // Increase Haste v3
            #case 125: // Increase Spell Healing
            #case 127: // Increase Spell Haste
            #case 128: // Increase Spell Duration
            #case 129: // Increase Spell Range
            #case 130: // Decrease Spell/Bash Hate
            #case 131: // Decrease Chance of Using Reagent
            #case 132: // Decrease Spell Mana Cost
            #case 158: // Increase Chance to Reflect Spell
            #case 168: // Increase Melee Mitigation
            #case 169: // Increase Chance to Critical Hit
            #case 172: // Increase Chance to Avoid Melee
            #case 173: // Increase Chance to Riposte
            #case 174: // Increase Chance to Dodge
            #case 175: // Increase Chance to Parry
            #case 176: // Increase Chance to Dual Wield
            #case 177: // Increase Chance to Double Attack
            #case 180: // Increase Chance to Resist Spell
            #case 181: // Increase Chance to Resist Fear Spell
            #case 183: // Increase All Skills Skill Check
            #case 184: // Increase Chance to Hit With all Skills
            #case 185: // Increase All Skills Damage Modifier
            #case 186: // Increase All Skills Minimum Damage Modifier
            #case 188: // Increase Chance to Block
            #case 200: // Increase Proc Modifier
            #case 201: // Increase Range Proc Modifier
            #case 216: // Increase Accuracy
            #case 227: // Reduce Skill Timer
            #case 266: // Add Attack Chance
            #case 273: // Increase Critical Dot Chance
            #case 294: // Increase Critical Spell Chance
            #    $name = $dbspelleffects[$spell["effectid$n"]];
            #    // For several of these cases, we have better information on
            #    // the range of values for the focus effect.
            #    switch ($spell["effectid$n"]) {
			#		 case 114: // Increase Agro Multiplier
			#		 case 158:
			#		 case 185:
			#		 case 186:
            #            $min = $spell["effect_base_value$n"];
			#			if ($min < 0) {
			#				$min *= -1;
			#				$max *= -1;
			#				$name = str_replace("Increase", "Decrease", $name);
			#			}
            #            break;
            #        case 125: // Increase Spell Healing
            #        case 131: // Decrease Chance of Using Reagent
            #        case 132: // Decrease Spell Mana Cost
            #            $min = $spell["effect_base_value$n"];
            #            $max = $spell["effect_limit_value$n"];
			#			if ($max < 0) {
			#				$max *= -1;
			#				$name = str_replace("Decrease", "Increase", $name);
			#			}
            #            break;
            #        // Reword this effect to seem more natural, matching
            #        // Allakhazam.
            #        case 130: // Decrease Spell/Bash Hate
            #            $min = $spell["effect_base_value$n"];
            #            $max = $spell["effect_limit_value$n"];
            #            //$name = str_replace("Decrease", "Increase", $name);
			#			if ($spell["effect_base_value$n"] < 0) {
			#				$name = str_replace("Increase", "Decrease", $name);
			#			} else {
			#				$name = str_replace("Decrease", "Increase", $name);
			#			}
            #            break;
            #    }
            #    $print_buffer .= $name;
            #    if ($min != $max) {
            #        $print_buffer .= " by $min% (L$minlvl) to $max% (L$maxpotencylevel)";
            #    } else {
			#		if ($spell["effectid$n"] == 98) {
			#			$max -= 100;
			#		}
            #        $print_buffer .= " by $max%";
            #    }
            #    break;
            case 15: // Increase Mana per tick
            case 100: // Increase Hitpoints v2 per tick
				if ($spell["effect_base_value$n"] < 0) {
					$print_buffer .= "Decrease ";
				} else {
					$print_buffer .= "Increase ";
				}
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                if ($min != $max) {
                    $print_buffer .= " by " . abs($min) . " (L$minlvl) to " . abs($max) . " (L$maxpotencylevel)";
					if ($duration >= 30) {
						$print_buffer .= " per tick";
					}
					if ($duration > 0 && $duration < 30) {
						if ($durationmin == $durationmax) {
							$print_buffer .= " per tick for " . $duration . " ticks (total " . abs($min * $duration) . " (L$minlvl) to " . abs($max * $duration) . ") (L$maxpotencylevel)";
						} else {
							$print_buffer .= " per tick for " . $durationmin . " (L$minlvl) to " . $durationmax . " (L$maxpotencylevel) ticks (total " . abs($min * $durationmin) . " (L$minlvl) to " . abs($max * $durationmax) . " (L$maxpotencylevel))";
						}
					}
                } else {
					 $print_buffer .= " by " . abs($max);
					if ($duration >= 30) {
						$print_buffer .= " per tick";
					}
					if ($duration > 0 && $duration < 30) {
						$print_buffer .= " per tick for " . $duration . " ticks (total " . abs($max * $duration) . ")";
					}
                }
                break;
            case 30: // Frenzy Radius
            case 86: // Reaction Radius
                $print_buffer .= "Limit " . $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " to (" . $spell["effect_base_value$n"] . ")";
				$print_buffer .= " up to (L" . $spell["max$n"] . ")";
                break;
            case 22: // Charm
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " up to level " . $spell["max1"];
                break;            
            case 23: // Fear
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                #$print_buffer .= " up to level " . $spell["max1"];
				$print_buffer .= " for up to " . $durationmax * 6 . " seconds.";
                break;
            case 31: // Mesmerize
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " up to level " . $spell["max1"];
                break;
            case 33: // Summon Pet:
            case 68: // Summon Skeleton Pet:
			case 71: // Create Undead Pet:
            case 106: // Summon Warder:
            case 108: // Summon Familiar:
            case 113: // Summon Horse:
            case 152: // Summon Pets:
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                if ($csv == false) {
                    $print_buffer .= " <a href=?a=pet&name=" . $spell["teleport_zone"] . ">" . $spell["teleport_zone"] . "</a>";
                } else {
                    $print_buffer .= " : " . $spell["teleport_zone"];
                }
                break;
			case 12: // Invisiblity
            case 13: // See Invisible
            case 18: // Pacify
            case 20: // Blindness
            case 25: // Bind Affinity
            case 26: // Gate
            case 28: // Invisibility versus Undead
            case 29: // Invisibility versus Animals
            case 40: // Invunerability
            case 41: // Destroy Target
            case 42: // Shadowstep
            case 44: // Lycanthropy
            case 52: // Sense Undead
            case 53: // Sense Summoned
            case 54: // Sense Animals
            case 56: // True North
            case 57: // Levitate
            case 61: // Identify
            case 64: // SpinStun
            case 65: // Infravision
            case 66: // UltraVision
            case 67: // Eye of Zomm
            case 68: // Reclaim Energy
            case 73: // Bind Sight
            case 74: // Feign Death
            case 75: // Voice Graft
            case 76: // Sentinel
            case 77: // Locate Corpse
            case 82: // Summon PC
            case 90: // Cloak
            case 93: // Stop Rain
            case 94: // Make Fragile (Delete if combat)
            case 95: // Sacrifice
            case 96: // Silence
            case 99: // Root
            case 101: // Complete Heal: With Recast Blocker Buff
            case 103: // Call Pet
            case 105: // Anti-Gate
            case 115: // Food/Water
            case 117: // Make Weapons Magical
            case 150: // Death Save - Restore Full Health
            case 151: // Suspend Pet - Lose Buffs and Equipment
            case 154: // Remove Detrimental
            case 156: // Illusion: Target
            case 178: // Lifetap from Weapon Damage
            case 179: // Instrument Modifier
            case 182: // Hundred Hands Effect
            case 194: // Fade
            case 195: // Stun Resist
            case 205: // Rampage
            case 206: // Area of Effect Taunt
            case 311: // Limit: Combat Skills Not Allowed
            case 314: // Fixed Duration Invisbility
            case 299: // Wake the Dead
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                break;
            case 58: // Illusion:
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= $dbiracenames[$spell["effect_base_value$n"]];
                break;
			case 59: // Damage Shield:
				$name = $dbspelleffects[$spell["effectid$n"]];
                if ($max > 0) {
                    $name = str_replace("Increase", "Decrease", $name);
                }
                $print_buffer .= $name;
                if ($min != $max) {
					if ($min < 0) {
                        $min = -$min;
                    }
					if ($max < 0) {
                        $max = -$max;
                    }
                    $print_buffer .= " by $min (L$minlvl) to $max (L$maxpotencylevel)";
                } else {
                    if ($max < 0) {
                        $max = -$max;
                    }
                    $print_buffer .= " by $max";
                }
                break;
			case 64: // SpinStun
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				//$print_buffer .= " for " . translate_time(CalcBuffDuration(($spell["effect_base_value$n"] / 1000), $spell["buffdurationformula"], $spell["buffduration"]));
				$print_buffer .= " for " . translate_time($spell["effect_base_value$n"] / 1000);
				if ($spell["max$n"] > 0) {
					$print_buffer .= " up to Level " . $spell["max$n"];
				}
				break;
            case 63: // Memblur
            case 120: // Set Healing Effectiveness
            case 330: // Critical Damage Mob
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " ($max%)";
                break;
            case 81: // Resurrect
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " and restore " . $spell["effect_base_value$n"] . "% experience";
                break;
            case 83: // Teleport
            case 88: // Evacuate
            case 145: // Teleport v2
                //$print_buffer .= " (Need to add zone to spells table)";
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                if ($csv == false) {
                    $print_buffer .= " <a href=?a=zone&name=" . $spell["teleport_zone"] . ">" . $spell["teleport_zone"] . "</a>";
                } else {
                    $print_buffer .= " : " . $spell["teleport_zone"];
                }
                break;
            case 85: // Add Proc:
            case 289: // Improved Spell Effect:
            case 323: // Add Defensive Proc:
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $name         = get_field_result(
                    "name",
                    "SELECT name FROM $spells_table WHERE id=" . $spell["effect_base_value$n"]
                );
                if ($csv == false) {
                    $print_buffer .= "<a href=?a=spell&id=" . $spell["effect_base_value$n"] . ">$name</a>";
                } else {
                    $print_buffer .= " : $name";
                }
                break;
            case 89: // Increase Player Size
                $name = $dbspelleffects[$spell["effectid$n"]];
                $min  -= 100;
                $max  -= 100;
                if ($max < 0) {
                    $name = str_replace("Increase", "Decrease", $name);
                }
                $print_buffer .= $name;
                if ($min != $max) {
                    $print_buffer .= " by $min% (L$minlvl) to $max% (L$maxpotencylevel)";
                } else {
                    $print_buffer .= " by $max%";
                }
                break;
            case 27: // Cancel Magic
            case 134: // Limit: Max Level
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " $max";
                break;
            case 157: // Spell-Damage Shield
				$name = $dbspelleffects[$spell["effectid$n"]];
				if ($max > 0) {
                    $name = str_replace("Increase", "Decrease", $name);
					$print_buffer .= "$name by $max";
                }
				else {
					$print_buffer .= "$name by " . (-1 * $max);
				}
                break;
            case 121: // Reverse Damage Shield
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " ($max)";
                break;
            case 91: // Summon Corpse
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " (max level $max)";
                break;
			case 104: // Translocate target to their bind point
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				if ($spell["teleport_zone"]) {
					if ($csv == false) {
						$print_buffer .= " <a href=?a=zone&name=" . $spell["teleport_zone"] . ">" . $spell["teleport_zone"] . "</a>";
					} else {
						$print_buffer .= " : " . $spell["teleport_zone"];
					}
				}
				else {
					$print_buffer .= " to their bind point";
				}
				break;
			case 135: // Limit: Resist(Magic allowed)
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				$print_buffer .= " " . $dbspellresists[$spell["effect_base_value$n"]];
				break;
            case 137: // Limit: Effect(Hitpoints allowed)
				$posBaseValue = $spell["effect_base_value$n"];
				if ($posBaseValue < 0 ) {
					$posBaseValue *= -1;
				}
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				$print_buffer .= " " . $dbspelleffects[$posBaseValue];
				break;
            case 138: // Limit: Spell Type(Detrimental only)
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				if ($dbspellresists[$spell["effect_base_value$n"]] == 1) {
					$print_buffer .= " Beneficial";
				}
				else {
					$print_buffer .= " Detrimental";
				}
				break;
            case 141: // Limit: Instant spells only
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]];
				break;
            case 136: // Limit: Target
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                if ($max < 0) {
                    $max = -$max;
                    $v   = " excluded";
                } else {
                    $v = "";
                }
                $print_buffer .= " (" . $dbspelltargets[$max] . "$v)";
                break;
            case 139: // Limit: Spell
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $max          = $spell["effect_base_value$n"];
                if ($max < 0) {
                    $max = -$max;
                    $v   = " excluded";
                }
                $name = get_field_result("name", "SELECT name FROM $spells_table WHERE id=$max");
                if ($csv == false) {
                    $print_buffer .= "($name)";
                } else {
                    $print_buffer .= " (<a href=?a=spell&id=" . $spell["effect_base_value$n"] . ">$name</a>$v)";
                }
                break;
            case 140: // Limit: Min Duration
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $min          *= 6;
                $max          *= 6;
                if ($min != $max) {
                    $print_buffer .= " ($min sec (L$minlvl) to $max sec (L$maxlvl) (L$maxpotencylevel))";
                } else {
                    $print_buffer .= " ($max sec)";
                }
                break;
			case 142: // Limit: Min Level
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " ($min)";
                break;
            case 143: // Limit: Min Casting Time
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $min          *= 6;
                $max          *= 6;
                if ($min != $max) {
                    $print_buffer .= " (" . ($min / 6000) . " sec (L$minlvl) to " . ($max / 6000) . " sec (L$maxlvl))";
                } else {
                    $print_buffer .= " (" . ($max / 6000) . " sec)";
                }
                break;
            case 148: // Stacking: Block existing spell
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " if slot " . ($spell["formula$n"] - 200) . " is effect '" . $dbspelleffects[$spell["effect_base_value$n"]] . "' and < " . $spell["max$n"];
                break;
            case 149: // Stacking: Overwrite existing spell
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " if slot " . ($spell["formula$n"] - 200) . " is effect '" . $dbspelleffects[$spell["effect_base_value$n"]] . "' and < " . $spell["max$n"];
                break;
            case 147: // Increase Hitpoints (%)
                $name = $dbspelleffects[$spell["effectid$n"]];
                if ($max < 0) {
                    $name = str_replace("Increase", "Decrease", $name);
                }
                $print_buffer .= $name . " by " . $spell["effect_limit_value$n"] . " ($max% max)";
                break;
            case 153: // Balance Party Health
                $print_buffer .= $dbspelleffects[$spell["effectid$n"]];
                $print_buffer .= " ($max% penalty)";
                break;
			case 301:
				$print_buffer .= "Increase Archery Damage by " . $spell["effect_base_value$n"] . "%";
				break;
			case 84:
				$print_buffer .= "Gravity Flux (Toss Up " . -$spell["effect_base_value$n"] . " units)";
				break;
			case 411:
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]] . " " . get_limit_class_usable_string($max);
				break;
			case 413:
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]] . " by " . $spell["effect_base_value$n"] . "%";
				break;
			case 414:
				$print_buffer .= $dbspelleffects[$spell["effectid$n"]] . " " . ucfirstwords($dbskills[$max]);
				break;
			case 226: // Two Hamd Bash
				$print_buffer .= "Add Two-Handed Bash Ability";
				break;
            case 0: // In/Decrease hitpoints
            case 1: // Increase AC
            case 2: // Increase ATK
            case 4: // Increase STR
            case 5: // Increase DEX
            case 6: // Increase AGI
            case 7: // Increase STA
            case 8: // Increase INT
            case 9: // Increase WIS
			case 10: // Increase CHA
            case 19: // Increase Faction
            case 35: // Increase Disease Counter
            case 36: // Increase Poison Counter
            case 46: // Increase Magic Fire
            case 47: // Increase Magic Cold
            case 48: // Increase Magic Poison
            case 49: // Increase Magic Disease
            case 50: // Increase Magic Resist
            case 55: // Increase Absorb Damage
            case 69: // Increase Max Hitpoints
            case 78: // Increase Absorb Magic Damage
            case 79: // Increase HP when cast
            case 92: // Increase hate
            case 97: // Increase Mana Pool
            case 111: // Increase All Resists
            case 112: // Increase Effective Casting
            case 116: // Decrease Curse Counter
            case 118: // Increase Singing Skill
            case 159: // Decrease Stats
            case 167: // Pet Power Increase
            case 192: // Increase hate
            default:
				$name = "";
				if ($spell["effectid$n"] == 0) {
					if ($max < 0 || $spell["goodEffect"]) {
						$name = "Decrease ";
					}
					else {
						$name = "Increase ";
					}
				}
				$name .= $dbspelleffects[$spell["effectid$n"]];
                if ($max < 0 || (!$spell["goodEffect"] && ($spell["effectid$n"] != 35 && $spell["effectid$n"] != 36 && $spell["effectid$n"] != 116 && $spell["effectid$n"] != 369))) {
                    $name = str_replace("Increase", "Decrease", $name);
                }
				if (($spell["effectid$n"] != 92 || $spell["effectid$n"] != 192) && $max > 0) {
					$name = str_replace("Decrease", "Increase", $name);
				}
				if ($spell["targettype"] == 13) {
					$name = "Lifetap";
				}
                $print_buffer .= $name;
                if ($min != $max) {
					if ($spell["targettype"] == 13) {
						$print_buffer .= " for ";
					}
					else {
						$print_buffer .= " by ";
					}
					$print_buffer .= "". abs($min) . " (L$minlvl) to " . abs($max) . " (L$maxpotencylevel)";
					if ($min < 0) {
                        $min = -$min;
                    }
					if ($max < 0) {
                        $max = -$max;
                    }
					if ($duration >= 30 && $spell["max$n"] == 0 && ($spell["effectid$n"] == 0 || $spell["effectid$n"] == 15)) {
						$print_buffer .= " per tick";
					}
					if ($duration > 0 && $duration < 30 && $spell["max$n"] == 0 && ($spell["effectid$n"] == 0 || $spell["effectid$n"] == 15)) {
						if ($durationmin == $durationmax) {
							$print_buffer .= " per tick for " . $duration . " ticks (total " . abs($min * $duration) . " (L$minlvl) to " . abs($max * $duration) . " (L$maxpotencylevel))";
						} else {
							$print_buffer .= " per tick for " . $durationmin . " (L$minlvl) to " . $durationmax . " (L$maxpotencylevel) ticks (total " . abs($min * $durationmin) . " (L$minlvl) to " . abs($max * $durationmax) . " (L$maxpotencylevel))";
						}
					}
					if ($AEDuration >= 2500) {
						$print_buffer .= " for " . $AEDuration / 2500 . " waves (total " . abs($min * ($AEDuration / 2500)) . " (L$minlvl) to " . abs($max * ($AEDuration / 2500)) . " (L$maxpotencylevel))";
					}
                } else {
					if ($spell["targettype"] == 13) {
						$print_buffer .= " for ";
					}
					else {
						$print_buffer .= " by ";
					}
					$print_buffer .= "" . abs($max);
                    if ($max < 0) {
                        $max = -$max;
                    }
					if ($duration >= 30 && $spell["max$n"] == 0 && ($spell["effectid$n"] == 0 || $spell["effectid$n"] == 15)) {
						$print_buffer .= " per tick";
					}
					if ($duration > 0 && $duration < 30 && $spell["max$n"] == 0 && ($spell["effectid$n"] == 0 || $spell["effectid$n"] == 15)) {
						$print_buffer .= " per tick for " . $duration . " ticks (total " . abs($max * $duration) . ")";
					}
					if ($AEDuration >= 2500) {
						$print_buffer .= " for " . $AEDuration / 2500 . " waves (total " . abs($min * ($AEDuration / 2500)) . ")";
					}
                }
                break;
        }
        $print_buffer .= '</ul>';
    }

    return $print_buffer;
}
