<?php
/* Copyright (c) 2012 by Christian Fillion.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>. */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br /><a href=\"../../index.php\">Go back.</a>");
}

function additionalgroups_info()
{
	return array(
		"name"			=> "Additional Usergroups on profiles",
		"description"	=> "This plugin shows user groups on additional profiles.",
		"website"		=> "http://www.premiermouvement.ca",
		"author"		=> "cfillion",
		"authorsite"	=> "http://www.premiermouvement.ca",
		"version"		=> "1.2",
		"guid" 			=> "ea82e9dc8a7bcdbea8378d6ff7574893",
		"compatibility" => "16*"
	);
}

function additionalgroups_activate()
{
	global $db, $mybb;

	$result = $db->simple_select('settinggroups', 'MAX(disporder) AS max_disporder');
	$max_disporder = $db->fetch_field($result, 'max_disporder');

	$additionalgroups = array(
		"name" => "additionalgroups",
		"title" => "Additional Usergroups on profiles",
		"description" => "Manage Additional Usergroups on profiles plugin (by cfillion)",
		"disporder" => intval($max_disporder) + 1,
		"isdefault" => "0",
	);
	$group['gid'] = $db->insert_query('settinggroups', $additionalgroups);
	$gid = $db->insert_id();

	$new_setting1 = array(
		'name'			=> 'additionalgroups_enable',
		'title'			=> 'Enable plugin',
		'description'	=> '',
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> '1',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting1);

	$new_setting2 = array(
		'name'			=> 'additionalgroups_displayMode',
		'title'			=> 'Display mode',
		'description'	=> '',
		'optionscode'	=> "select
auto=Auto
image=Image Only
text=Text Only",
		'value'			=> 'auto',
		'disporder'		=> '2',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting2);

	$new_setting3 = array(
		'name'			=> 'additionalgroups_noBullet',
		'title'			=> 'Hide bullets',
		'description'	=> 'Customize list style',
		'optionscode'	=> 'yesno',
		'value'			=> '0',
		'disporder'		=> '3',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting3);

	$new_setting4 = array(
		'name'			=> 'additionalgroups_headerText',
		'title'			=> 'Table header text',
		'description'	=> 'Plain text only',
		'optionscode'	=> 'text',
		'value'			=> 'Additional Usergroups',
		'disporder'		=> '4',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting4);

	$new_setting5 = array(
		'name'			=> 'additionalgroups_exlude',
		'title'			=> 'Usergroups that should not be displayed',
		'description'	=> '(Comma-separated list)',
		'optionscode'	=> 'text',
		'value'			=> '',
		'disporder'		=> '5',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting5);

	rebuild_settings();

	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('member_profile', '#'.preg_quote('{$profilefields}').'#', "{\$profilefields}\n{\$additionalgroups}");
}

function additionalgroups_deactivate()
{
	global $db, $mybb;

	$db->delete_query("settinggroups", "name IN('additionalgroups')");
	$db->delete_query("settings", "name LIKE '%additionalgroups_%'");
	rebuild_settings();

	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('member_profile', '#\n'.preg_quote('{$additionalgroups}').'#', '', 0);
}

$plugins->add_hook('member_profile_start', 'additionalgroups');
function additionalgroups()
{
	global $mybb, $cache, $additionalgroups, $theme, $headerinclude;
	$uid = intval($mybb->input['uid']);

	if(!$mybb->settings['additionalgroups_enable'])
		return;

	if(!empty($mybb->user['language']))
	{
		$language = $mybb->user['language'];
	}
	else
	{
		$language = $mybb->settings['bblanguage'];
	}

	if($uid == $mybb->user['uid'])
		$user = $mybb->user;
	else
		$user = get_user($uid);

	$groups = $cache->read("usergroups");
	$displayMode = $mybb->settings['additionalgroups_displayMode'];
	$headerText = htmlspecialchars_uni($mybb->settings['additionalgroups_headerText']);
	$exluded = explode(',', $mybb->settings['additionalgroups_exlude']);

	if($user['displaygroup'] != 0 && $user['usergroup'] != $user['displaygroup'])
		$user['additionalgroups'] = $user['usergroup'].','.$user['additionalgroups'];

	$groupsHtml = '';
	foreach(explode(',', $user['additionalgroups']) as $gid)
	{
		if(empty($gid) || in_array($gid, $exluded) || $user['displaygroup'] == $gid)
			continue;

		$group = $groups[$gid];

		$group['image'] = str_replace("{lang}", $language, $group['image']);
		$group['image'] = str_replace("{theme}", $theme['imgdir'], $group['image']);

		$item = '';
		if($displayMode == 'auto')
		{
			if(!empty($group['image']))
			{
				$item = '<img src="'.$mybb->settings['bburl'].'/'.$group['image'].'" alt="'.htmlspecialchars_uni($group['title']).'" title="'.htmlspecialchars_uni($group['title']).'" />';
			}
			else
			{
				$item = htmlspecialchars_uni($group['title']);
			}
		}
		else if($displayMode == 'image' && !empty($group['image']))
		{
			$item = '<img src="'.$mybb->settings['bburl'].'/'.$group['image'].'" alt="'.htmlspecialchars_uni($group['title']).'" title="'.htmlspecialchars_uni($group['title']).'" />';
		}
		else if($displayMode == 'text')
		{
			$item = htmlspecialchars_uni($group['title']);
		}

		if(!empty($item))
			$groupsHtml .= '<li>'.$item."</li>\n";
	}

	if(empty($groupsHtml))
		return;

	if(empty($headerText))
		$headerText = 'Additional Usergroups';

	$additionalgroups = "<br />
<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\">
<tr>
<td class=\"thead\"><strong>{$headerText}</strong></td>
</tr>
<tr>
<td class=\"trow1\" id=\"additionalgroups\">
	<ul>
		{$groupsHtml}
	</ul>
</td>
</tr>
</table>";

	$headerinclude .= '<style type="text/css">
	#additionalgroups ul
	{
		margin: 0px;';
	if($mybb->settings['additionalgroups_noBullet'])
		$headerinclude .= "\n\t\tlist-style: none;\n\t\tpadding: 0px;";

	$headerinclude .= '
	}';

	if($displayMode == 'auto' || $displayMode == 'image')
		$headerinclude .= '
	#additionalgroups li img
	{
		vertical-align: middle;
	}';
	$headerinclude .= "\n</style>";
}
