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

function deletetimelimit_info()
{
	return array(
		"name"			=> "Delete Time Limit",
		"description"	=> "This plugin adds a setting to define the number of minutes until regular users cannot delete their own posts.",
		"website"		=> "http://www.1er-mouvement.ca",
		"author"		=> "cfillion",
		"authorsite"	=> "http://www.1er-mouvement.ca",
		"version"		=> "1.0",
		"guid" 			=> "aec84f7bdf082b3905434d989f74f3b9",
		"compatibility" => "16*"
	);
}

function deletetimelimit_activate()
{
	global $db, $mybb;

	$query = $db->simple_select('settings', 'disporder', 'name=\'edittimelimit\'');
	$rep = $db->fetch_array($query);

	$new_setting = array(
		'name'			=> 'deletetimelimit',
		'title'			=> 'Delete Time Limit',
		'description'	=> 'The number of minutes until regular users cannot delete their own posts (if they have the permission). Enter 0 (zero) for no limit.',
		'optionscode'	=> 'text',
		'value'			=> '0',
		'disporder'		=> intval($rep['disporder']),
		'gid'			=> '13'
	);
	$db->insert_query('settings', $new_setting);

	rebuild_settings();
}

function deletetimelimit_deactivate()
{
	global $db, $mybb;

	$db->delete_query("settings", "name='deletetimelimit'");
	rebuild_settings();
}

$plugins->add_hook('editpost_deletepost', 'deletetimelimit');
function deletetimelimit()
{
	global $mybb, $fid, $post, $lang;
	$lang->load('deletetimelimit');

	if($mybb->settings['deletetimelimit'] != 0 && !is_moderator($fid, "candeleteposts"))
	{
		$time = TIME_NOW;
		if($post['dateline'] < ($time-($mybb->settings['deletetimelimit']*60)))
		{
			$lang->delete_time_limit = $lang->sprintf($lang->delete_time_limit, $mybb->settings['deletetimelimit']);
			error($lang->delete_time_limit);
		}
	}
}
