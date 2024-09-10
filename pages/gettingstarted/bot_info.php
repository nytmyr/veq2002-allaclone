<?php

echo
'

<h2><u><font color=green>Bot Information and Features</u><font color=black></h2>

-----------------------------------------------------------------------------------------
<br>
<br>
    <i>Also viewable in <a href="https://discord.com/channels/579332053363982396/629347946718035978">#bot-commands</a> on the Discord.</i>
	
<b>
	<br><br>
	<font size=3>^create help - information on how to create a bot.
    <br>^? - pulls up a list of all commands
	<br>Type "help" after any command for a description - ^attack help
	<font size=2>
	<br>
	<br>
	Bots are extremely intelligent and like you\'ve never seen before.
	<br><br>
	<li>Spawnable Bots are unlocked as you level, beginning at level 5. (1 @ L5, 2 @ L10, 3 @ L25, 4 @ L40, 5 @ L50).<br>
    <li>After level 50 you can start unlocking more bots by killing raid targets, with unlocks listed at the bottom of this page.<br>
	<li>Your progress of killed/unkilled raid targets and points can be tracked on <a href="http://vegaseq.com/charbrowser/index.php">CharBrowser</a> by clicking the Raid button on your character\'s profile.
	<li>You can also talk to Guide Varn in The Hub for your point/unlock status.
	<br><br>
	<li>Bots can be used in raids.
	<li>They will heal and buff across the raid as needed.
	<li>Monk bots use their epic in combat automatically if it is equipped, Mage bots can summon their epic if it is equipped. Use ^petsettype epic/^pst epic.
	<li>Bots can click any clickable equipped item via ^clickitem. IE (^clickitem 13 byclass 8) would cause all Bard bots to click their primary item.
	<li>Bots can hold and adjust all of their spells/spell types and when they can cast as well as customize all healing thresholds for bots and players. (See below for more details).
	<li>Death Touch will hit Bots if they are the primary target.
	<li>Bots will auto-invis in The Plane of Knowledge; use see-invis or invite bots to groups to interact.
	<li>Bots gain most of the passive AAs for their class automatically upon leveling.
	<li>They will honor Blocked Buffs on a player and not cast those spells on the targeted player.
	<li>Bot messages can be filtered by the Pet Responses filter.
	<br><br>
	<li>...and so many more perks to the bots you\'ll experience here.
	<br><br>
	<h2><u><font color=green>Tips</u><font color=black></h2>
	<li>When gearing your bots, use ^iu (^itemuse) to see bots that can use the item on your cursor. Use (^iu help) for even more options.
	<li>Stances are ONLY used for Pet Heals (more info under "Pet Healing Info") and for making Non-Warriors enter a taunting state by default if set to Aggressive (^stance 5).
	<li>---This requires a zone or camp to take initial effect but will carry over thereafter. This can still be toggled off by ^taunt as usual.
	<li>If a bot isn\'t casting a group spell you want (Selo`s for example), make sure to click the buff off the bot that would normally cast the spell so they\'ll recast.
	<li>If you don\'t want bots to auto aggro, you can turn off autodefend and they will only attack when told. ^oo autodefend disable.
	<li>--- This can be coupled with ^guard spawned to set up a camp to pull to and then told to ^attack spawned.
	<br><br>
	<font size=3><u>These are Macros for easy spawn, invite and grouping of raids for boxes and bots --- <font color=red>Instructions inside so read carefully</u>
	<font size=2 color=black>
	<br>
	<li><a href="https://discord.com/channels/579332053363982396/629347946718035978/1277841613443563602">Raid Macros</a>
	</i><font color=black>
	<br><br>
	For a generic list of bot commands, go to <a href="https://docs.eqemu.io/server/bots/bot-commands/">EQEmu Bot Commands</a>. This does not include any VegasEQ commands, see below for details.
	<br>
	<h2><u><font color=green>Command-Based Spell Commands</u><font color=black></h2>
	<li>^bind/^bindaffinity
	<li>^calm
	<li>^charm - use ^petremove to remove pet and prep for charming. Or use ^holdpets to prevent casting of pets and ^pgl if a pet is already summoned.
	<li>^cure (blindness | poison | disease)
	<li>^eb
	<li>^identify
	<li>^invis/^invisibility (living | undead | animal | see)
	<li>^lev/^levitation
	<li>^lore
	<li>^lull
	<li>^mesmerize/^mez
	<li>^movementspeed
	<li>^pacify
	<li>^petsettype/^pst
	<li>^resist/^resistance (fire | cold | poison | disease | magic)
	<li>^rez/^resurrect
	<li>^revive
	<li>^root
	<li>^snare
	<li>^sow
	<li>^waterbreathing/^wb
	
	<h2><u><font color=green>Port Commands</u><font color=black></h2>
	<li>^cir/^circle destination name | list (Druids)
	<li>^depart destination name | list (Both)
	<li>^escape
	<li>^gate
	<li>^port/^portal destination name | list (Wizards)
	<li>^sendhome
	
	<h2><u><font color=green>Spell List Commands</u><font color=black></h2>
	<u>These settings allow you to control the bot\'s spell list and modify it from the pre-defined lists.</u>
	<br><br>
	<i>Priority and Min/Max HP are unused in these options</i>
	<br><br>
	<li>^enforcespellsettings - Toggles your Bot to cast only spells in their spell settings list which you can customize below.
	<li>^spells - Lists all Spells available to the Bot from the server list.
	<li>^spellsettings - Lists a bot\'s spell setting entries.
	<li>^spellsettingsadd - Add a bot spell setting entry.
	
	<h2><u><font color=green>Spell Type Hold Commands</u><font color=black></h2>
	<u>These commands allow you to tell a bot to hold a specify type of spell and not cast it.</u>
	<br><br>
	<li>^holdaenukes
	<li>^holdaerains
	<li>^holdbuffs
	<li>^holdcompleteheals
	<li>^holdcharms
	<li>^holdcures
	<li>^holddebuffs
	<li>^holddispels
	<li>^holdds (damage shield buffs)
	<li>^holddots
	<li>^holdescapes
	<li>^holdfastheals
	<li>^holdgroupheals
	<li>^holdhateredux
	<li>^holdheals
	<li>^holdhotheals
	<li>^holdincombatbuff
	<li>^holdincombatbuffsongs
	<li>^holdlifetaps
	<li>^holdlulls
	<li>^holdmez
	<li>^holdnukes
	<li>^holdoutofcombatbuffsongs
	<li>^holdpets
	<li>^holdpetbuffs
	<li>^holdpetheals
	<li>^holdprecombatbuffs
	<li>^holdprecombatbuffsongs
	<li>^holdregularheals
	<li>^holdresists (resist stat buffs)
	<li>^holdrez
	<li>^holdroots
	<li>^holdslows
	<li>^holdsnares
	
	<h2><u><font color=green>Casting Interval Commands</u><font color=black></h2>
	<u>These commands allow you to control how often a bot will cast a type of spell after the previous one is finished casting.</u>
	<br><br>
	<li>^buffdelay
	<li>^curedelay
	<li>^debuffdelay
	<li>^dispeldelay
	<li>^dotdelay
	<li>^escapedelay
	<li>^hatereduxdelay
	<li>^incombatbuffdelay
	<li>^lifetapdelay
	<li>^mezdelay
	<li>^nukedelay
	<li>^rootdelay
	<li>^slowdelay
	<li>^snaredelay
	
	<h2><u><font color=green>Maximum Threshold Commands</u><font color=black></h2>
	<u>These commands allow you to control when a bot will START casting a type of spell based off the target\'s health.</u>
	<br><br>
	<li>^buffthreshold
	<li>^completehealthreshold
	<li>^curethreshold
	<li>^debuffthreshold
	<li>^dispelthreshold
	<li>^dotthreshold
	<li>^escapethreshold
	<li>^fasthealthreshold
	<li>^hatereduxthreshold
	<li>^healthreshold
	<li>^hothealthreshold
	<li>^incombatbuffthreshold
	<li>^lifetapthreshold
	<li>^mezthreshold
	<li>^nukethreshold
	<li>^rootthreshold
	<li>^slowthreshold
	<li>^snarethreshold
	
	<h2><u><font color=green>Minimum Threshold Commands</u><font color=black></h2>
	<u>These commands allow you to control when a bot will STOP casting a type of spell based off the target\'s health.</u>
	<br><br>
	<li>^buffminthreshold
	<li>^cureminthreshold
	<li>^debuffminthreshold
	<li>^dispelminthreshold
	<li>^dotminthreshold
	<li>^escapeminthreshold
	<li>^hatereduxminthreshold
	<li>^incombatbuffminthreshold
	<li>^lifetapminthreshold
	<li>^mezminthreshold
	<li>^nukeminthreshold
	<li>^rootminthreshold
	<li>^slowminthreshold
	<li>^snareminthreshold
	
	<br><br>
	<u><font size=3>The exceptions to the target\'s percentages are Escapes, HateRedux, Lifetaps and InCombatBuffs for Shaman ONLY (Canni). Those ^threshold commands are based off the bot\'s HP instead of the target\'s.</u>
	<font size=2><br>
	
	<h2><u><font color=green>Heal Commands</u><font color=black></h2>
	<u>These commands allow you to control when a bot or player will receive heals.</u>
	<br><br>
	<li>^completehealdelay
	<li>^fasthealdelay
	<li>^healdelay
	<li>^hothealdelay
	
	<i>
	<br><br>
	<u><font size=3>NOTES: <font size=2></u>
	<br>Heal Thresholds can be used to set on any bot or player (use # commands for players, ie #healthreshold) to receive that type of heal at the set % and delay.
	<br>ALL bots regardless of who owns them will honor each individual bot\'s and player\'s chosen settings.
	<br>These are set ON each bot or player for them to be healed at that threshold, not on the bots DOING the healing.
	</i>
	<br><br>
	<u><font size=3>EXAMPLE:<font size=2></u> 
	<br>You want your Tank to only receive CHs every 2 seconds starting @ 80% and Fast Heals every 1 second starting @ 40%:
	<br><u>Target your tank bot and type:</u>
	<li>^healthreshold 0 (disables regular heals), ^hothealthreshold 0 (disables HoT Heals), ^completehealthreshold 80 (receive CHs at 80% and below) and ^fasthealthreshold 40 (receive Fast Heals at 40% and below).
	<li>^completehealdelay 2000 (receive CHs every 2 seconds), ^fasthealdelay 1000 (receive Fast Heals every 1 second).
	<br><br>
	<u>There are Fast Heals, Regular Heals, HoT (Heal Over Time) Heals and Complete Heals.</u>
	
	<h2><u><font color=green>Miscellaneous Commands</u><font color=black></h2>
	<li>^behindmob - Tells the bot to try to stay behind the target.
	<li>^casterrange - Sets the range for which the caster to initiate the target at.
	<li>^clickitem <slotID> - Tells a bot to click an item they have equipped.
	<li>^removefromraid - Forcibly removes your targeted bot from a raid.
	<li>^useepic - Forces a bot to use their epic if it is equipped.
	<li>^holdsettings - lists the bot\'s hold settings.
	<li>^delaysettings - lists the bot\'s delay settings.
	<li>^minthresholdsettings - lists the bot\'s minimum threshold settings.
	<li>^thresholdsettings - lists the bot\'s maximum threshold settings.
	<li>^copysettings - copies settings from the targeted bot to the named bot.
	
	<h2><u><font color=green>Pet Healing Info</u><font color=black></h2>
	<li>Pets will be healed using the default delay settings and can be toggled on/off with ^holdpetheals
	<li>You can control when they start healing pets by stances
	<br><br>
	<u>The thresholds for stances are as follows:</u>
	<li>Balanced (default) will start with Regular Heals @ 55% and Fast @ 35%. No CHs, no HoTs.
	<li>Efficient will start with CHs @ 70%, Regular Heals @ 55%, Fast Heals @ 35%. No HoTs.
	<li>Reactive will do all the regular default heals starting with HoTs @ 85%, CHs @ 70%,  Regular Heals @ 55% and Fast Heals @ 35%
	<li>Aggressive will not heal at all.
	<li>Burn will only cast Fast Heals/Regular Heals starting at 35%.
	<li>BurnAE will only Fast Heals/Regular Heals starting @ 25%.
	
	<h2><u><font color=green>Raid Point Bot Unlock Thresholds</u><font color=black></h2>
	<font size=3><u>Killing raid targets gains you raid points that unlock the progression path for unlocking more spawnable bots and the unlock thresholds are as follows:</u>
	<font size=2>
	<br><br>
    <li>2 points <-- --> 6 bots
    <li>4 points <-- --> 7 bots
    <li>6 points <-- --> 8 bots
    <li>9 points <-- --> 9 bots
    <li>12 points <-- --> 10 bots
    <li>15 points <-- --> 11 bots
    <li>19 points <-- --> 12 bots
    <li>23 points <-- --> 13 bots
    <li>28 points <-- --> 14 bots
    <li>33 points <-- --> 15 bots
    <li>39 points <-- --> 16 bots
    <li>45 points <-- --> 17 bots
    <li>52 points <-- --> 18 bots
    <li>60 points <-- --> 19 bots
    <li>70 points <-- --> 20 bots
    <li>80 points <-- --> 21 bots
    <li>90 points <-- --> 22 bots
    <li>100 points <-- --> 23 bots
    <li>115 points <-- --> 24 bots
    <li>130 points <-- --> 25 bots
    <li>145 points <-- --> 26 bots
    <li>160 points <-- --> 27 bots
    <li>175 points <-- --> 28 bots
    <li>190 points <-- --> 29 bots
    <li>205 points <-- --> 30 bots
    <li>220 points <-- --> 31 bots
    <li>240 points <-- --> 32 bots
    <li>260 points <-- --> 33 bots
    <li>280 points <-- --> 34 bots
    <li>300 points <-- --> 35 bots
	<br><br>
	<li>Your progress of killed/unkilled raid targets and points can be tracked on <a href="http://vegaseq.com/charbrowser/index.php">CharBrowser</a> by clicking the Raid button on your character\'s profile.
	<li>You can also talk to Guide Varn in The Hub for your point/unlock status.
</b>

';