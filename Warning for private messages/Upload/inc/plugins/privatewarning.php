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

function privatewarning_info()
{
	return array(
		"name"			=> "Warning for private messages",
		"description"	=> "Displays a custom warning before sending a private message to user.",
		"website"		=> "http://www.premiermouvement.ca/?privatewarning=v1.0",
		"author"		=> "cfillion",
		"authorsite"	=> "http://www.premiermouvement.ca/?privatewarning=v1.0",
		"version"		=> "1.0",
		"guid" 			=> "f2630f2e07d63d38e2450553d1145e0e",
		"compatibility" => "16*"
	);
}

function privatewarning_install()
{
	global $db;

	$db->query("ALTER TABLE ".TABLE_PREFIX."users ADD privatewarning varchar(200) DEFAULT NULL");
}

function privatewarning_is_installed()
{
	global $db;

	return ($db->field_exists('privatewarning', 'users'));
}

function privatewarning_uninstall()
{
	global $db;

	$db->query("ALTER TABLE ".TABLE_PREFIX."users DROP privatewarning");
}

function privatewarning_activate()
{
	global $db, $mybb;

	$infos = privatewarning_info();
	$result = $db->simple_select('settinggroups', 'MAX(disporder) AS max_disporder');
	$max_disporder = $db->fetch_field($result, 'max_disporder');

	$privatewarning = array(
		"name" => "privatewarning",
		"title" => $infos['name'],
		"description" => "Manage Warning for private messages plugin (by cfillion)",
		"disporder" => $max_disporder + 1,
		"isdefault" => "0",
	);
	$group['gid'] = $db->insert_query('settinggroups', $privatewarning);
	$gid = $db->insert_id();

	$new_setting1 = array(
		'name'			=> 'privatewarning_status',
		'title'			=> 'Enable plugin',
		'description'	=> '',
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> '1',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting1);

	$new_setting2 = array(
		'name'			=> 'privatewarning_allow',
		'title'			=> 'Who can set a warning',
		'description'	=> 'Comma-separated list. Type * for wildcard',
		'optionscode'	=> 'text',
		'value'			=> '*',
		'disporder'		=> '2',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting2);

	$new_setting3 = array(
		'name'			=> 'privatewarning_deny',
		'title'			=> 'Who can not set a warning',
		'description'	=> 'Comma-separated list. No wildcard here.',
		'optionscode'	=> 'text',
		'value'			=> '',
		'disporder'		=> '3',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting3);

	$new_setting4 = array(
		'name'			=> 'privatewarning_showInProfile',
		'title'			=> 'Show warning message in profile',
		'description'	=> '',
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> '4',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting4);

	rebuild_settings();

	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('member_profile', '#'.preg_quote('<td class="{$bgcolors[\'pm\']}"><strong>').'#', '<td class="{$bgcolors[\'pm\']}" style="vertical-align: top;"><strong>');
	find_replace_templatesets('member_profile', '#'.preg_quote('{$lang->send_pm}</a>').'#', "{\$lang->send_pm}</a>\n{\$privatewarning}");
	find_replace_templatesets('usercp_options', '#'.preg_quote('{$pms_from_buddys}').'#', "{\$privatewarning}\n{\$pms_from_buddys}");
	find_replace_templatesets('modcp_editprofile', '#'.preg_quote('{$customfields}').'#', "{\$privatewarning}\n{\$customfields}");
	find_replace_templatesets('private_send', '#'.preg_quote('{$send_errors}').'#', "{\$send_errors}\n{\$privatewarning}");
}

function privatewarning_deactivate()
{
	global $db, $mybb;

	$db->delete_query("settinggroups", "name IN('privatewarning')");
	$db->delete_query("settings", "name LIKE '%privatewarning_%'");
	rebuild_settings();

	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('member_profile', '#'.preg_quote('<td class="{$bgcolors[\'pm\']}" style="vertical-align: top;"><strong>').'#', '<td class="{$bgcolors[\'pm\']}"><strong>', 0);
	find_replace_templatesets('member_profile', '#\n'.preg_quote('{$privatewarning}').'#', '', 0);
	find_replace_templatesets('usercp_options', '#'.preg_quote('{$privatewarning}').'\n#', '', 0);
	find_replace_templatesets('modcp_editprofile', '#'.preg_quote('{$privatewarning}').'\n#', '', 0);
	find_replace_templatesets('private_send', '#\n'.preg_quote('{$privatewarning}').'#', '', 0);
}

$plugins->add_hook("member_profile_start", "privatewarning_profile");
function privatewarning_profile()
{
	global $mybb, $privatewarning, $db;
	$privatewarning = '';

	if($mybb->settings['privatewarning_status'] != 1 || $mybb->settings['privatewarning_showInProfile'] != 1)
		return;

	if($mybb->input['uid'])
	{
		$uid = intval($mybb->input['uid']);
	}
	else
	{
		$uid = $mybb->user['uid'];
	}

	$query = $db->simple_select('users', 'privatewarning,usergroup,displaygroup,receivepms', 'uid='.intval($uid), array('limit' => '1'));
	$memprofile = $db->fetch_array($query);

	if(!empty($memprofile['privatewarning']) && privatewarning_isAllowed($memprofile) && $memprofile['receivepms'])
	{
		$privatewarning = '<br />' . htmlspecialchars_uni($memprofile['privatewarning']);
	}
}

$plugins->add_hook("usercp_options_start", "privatewarning_usercp");
function privatewarning_usercp()
{
	global $mybb, $privatewarning, $lang;
	$lang->load('privatewarning');
	if($mybb->settings['privatewarning_status'] != 1 || !privatewarning_isAllowed())
		return;

	$privatewarning = '<tr>
<td></td><td><span class="smalltext"><label for="privatewarning">'.$lang->privatewarning.'</label></span><br />
<textarea id="privatewarning" name="privatewarning" style="width: 100%; height: 50px;">'.htmlspecialchars_uni($mybb->user['privatewarning']).'</textarea></td>
</tr>';
}

$plugins->add_hook("modcp_editprofile_start", "privatewarning_modcp");
function privatewarning_modcp()
{
	global $mybb, $privatewarning, $lang, $theme, $db;
	$lang->load('privatewarning');
	if($mybb->settings['privatewarning_status'] != 1)
		return;

	$query = $db->simple_select('users', 'privatewarning,usergroup,displaygroup', 'uid='.intval($mybb->input['uid']), array('limit' => '1'));
	$user = $db->fetch_array($query);

	if(!privatewarning_isAllowed($user))
		return;

	$privatewarning = '<br />
<fieldset class="trow2">
	<legend><strong>'.$lang->privatewarning.'</strong></legend>
	<table cellspacing="0" cellpadding="'.$theme['tablespace'].'" style="width: 100%;">
		<tr>
			<td colspan="3">
				<textarea id="privatewarning" name="privatewarning" style="width: 100%; height: 60px;">'.htmlspecialchars_uni($user['privatewarning']).'</textarea>
			</td>
		</tr>
	</table>
</fieldset>';
}

$plugins->add_hook("datahandler_user_validate", "privatewarning_save");
function privatewarning_save($handler)
{
	global $db, $mybb, $lang;
	if(!my_strpos($_SERVER['REQUEST_URI'], 'usercp.php') || $mybb->settings['privatewarning_status'] != 1 || !privatewarning_isAllowed())
		return $handler;

	$lang->load('privatewarning');
	$privatewarning = $mybb->input['privatewarning'];

	// Validation
	if($privatewarning != null)
	{
		if(my_strlen($privatewarning) < 8)
			$handler->set_error($lang->warning_tooshort);
		if(my_strlen($privatewarning) > 200)
			$handler->set_error($lang->warning_toolong);
	}

	// Save
	if(count($handler->get_errors()) < 1)
	{
		$handler->user_update_data['privatewarning'] = $privatewarning;
	}
	return $handler;
}

$plugins->add_hook("modcp_do_editprofile_update", "privatewarning_save_modcp");
function privatewarning_save_modcp()
{
	global $extra_user_updates, $mybb, $db;
	$extra_user_updates['privatewarning'] = $db->escape_string($mybb->input['privatewarning']);
}

$plugins->add_hook("private_send_start", "privatewarning_send");
$plugins->add_hook("private_send_do_send", "privatewarning_send");
function privatewarning_send()
{
	global $mybb, $privatewarning, $db, $lang, $send_errors;
	$warnings = array();
	$displayedWarnings = array();

	// Wich warnings we already displayed?
	if($mybb->request_method == "post" && !empty($mybb->input['privatewarning']) && !$mybb->input['preview'])
	{
		$displayedWarnings = explode(',', $mybb->input['privatewarning']);
	}

	// We restore subject and clear errors. Preview mode is required to keep "to" and "bcc" fields.
	if($mybb->input['_pm_subject'])
	{
		$mybb->input['subject'] = $mybb->input['_pm_subject'];

		$send_errors = '';
		$mybb->input['preview'] = 'Preview';
	}

	$users = array();
	if($mybb->input['uid'])
		$users = array($mybb->input['uid']);
	else
	{
		$to = explode(",", $mybb->input['to']);
		$to = array_map("trim", $to);
		$bcc = array();
		if(!empty($mybb->input['bcc']))
		{
			$bcc = explode(",", $mybb->input['bcc']);
			$bcc = array_map("trim", $bcc);
		}
		$usernames = array_merge($to, $bcc);
		foreach($usernames as $un)
		{
			$query = $db->simple_select('users', 'uid', 'username=\''.$db->escape_string($un).'\'', array('limit' => '1'));
			$data = $db->fetch_array($query);
			if($data['uid'])
			{
				if(in_array($data['uid'], $displayedWarnings))
					continue;

				$users[] = $data['uid'];
			}
		}
	}

	// Getting available warnings
	foreach($users as $uid)
	{
		$query = $db->simple_select('users', 'privatewarning,usergroup,displaygroup,username', 'uid='.intval($uid), array('limit' => '1'));
		$user = $db->fetch_array($query);

		if(!privatewarning_isAllowed($user) || $user['privatewarning'] == null)
			continue;

		$text = htmlspecialchars_uni($user['privatewarning']);
		if(count($users) > 1 || count($displayedWarnings) >= 1)
			$text .= ' <i>(' . htmlspecialchars_uni($user['username']) . ')</i>';

		$warnings[] = $text;
	}

	if(count($warnings) > 0)
	{
		$lang->load('privatewarning');

		if($mybb->input['action'] == "do_send" && !$mybb->input['preview'])
		{
			// We display warnings & cancel pm submission
			$privatewarning = inline_error($warnings, $lang->privatewarning_warning_send);
			$mybb->input['_pm_subject'] = $mybb->input['subject'];
			$mybb->input['subject'] = '';
		}
		else
		{
			// If we do not already print warnings, we do it now.
			if($privatewarning == '')
			{
				$privatewarning = inline_error($warnings, $lang->privatewarning_warning)."\n";
			}
		}
	}

	// Saving warnings that already got displayed
	if(my_strpos($privatewarning, '<input type="hidden" name="privatewarning"') === false)
	{
		$privatewarning .= '<input type="hidden" name="privatewarning" value="'.htmlspecialchars_uni(implode(',', array_merge($users, $displayedWarnings))).'" />';
	}
}

$plugins->add_hook("private_send_end", "privatewarning_removePreview");
function privatewarning_removePreview()
{
	global $preview, $mybb;

	// Triggering preview is the only way to cancel PM submission without losing "to" and "bcc" fields.
	// We undo this here.
	if($mybb->input['_pm_subject'] && $mybb->input['preview'])
	{
		$preview = '';
	}
}

function privatewarning_isAllowed($user = null)
{
	global $mybb;
	if($user == null)
		$user = $mybb->user;

	if(!$user['displaygroup'] || $user['displaygroup'] == $user['usergroup'])
		$group = intval($user['usergroup']);
	else
		$group = intval($user['displaygroup']);

	$allow = explode(',', $mybb->settings['privatewarning_allow']);
	$deny = explode(',', $mybb->settings['privatewarning_deny']);

	if(in_array($group, $deny))
		return false;
	else if(in_array($group, $allow) || in_array('*', $allow))
		return true;
	else
		return false;
}
