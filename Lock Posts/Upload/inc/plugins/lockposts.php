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

function lockposts_info()
{
	return array(
		"name"			=> "Lock Posts",
		"description"	=> "Lock posts for to being edited.",
		"website"		=> "http://www.premiermouvement.ca",
		"author"		=> "cfillion",
		"authorsite"	=> "http://www.premiermouvement.ca",
		"version"		=> "1.0",
		"guid" 			=> "26f2084b26938594d2bd658065724955",
		"compatibility" => "16*"
	);
}

function lockposts_install()
{
	global $db;
	$db->query("ALTER TABLE ".TABLE_PREFIX."posts ADD locked TINYINT(1) DEFAULT 0");
}

function lockposts_is_installed()
{
	global $db;

	if($db->field_exists('locked', 'posts'))
	{
		return true;
	}
	return false;
}

function lockposts_uninstall()
{
	global $db;

	$db->query("ALTER TABLE ".TABLE_PREFIX."posts DROP locked");
}

$plugins->add_hook('editpost_start', 'lockposts');
function lockposts()
{
	global $mybb, $db, $lang;
	$lang->load('lockposts');

	// Get post info
	$pid = intval($mybb->input['pid']);

	$query = $db->simple_select("posts", "*", "pid='$pid'");
	$post = $db->fetch_array($query);

	$fid = $post['fid'];
	if($post['locked'] != 0)
	{
		if(is_moderator($fid, "candeleteposts") && $mybb->input['action'] == "deletepost")
			return; // Allow delete by moderators
		else if(!is_moderator($fid, "caneditposts"))
		{
			if(THIS_SCRIPT != 'xmlhttp.php')
				error($lang->post_locked);
			else
				xmlhttp_error(str_replace('<br />', " ", $lang->post_locked));
			return;
		}
	}
}

$plugins->add_hook('editpost_end', 'lockposts_checkbox');
function lockposts_checkbox()
{
	global $lang, $disablesmilies, $postoptions, $fid, $post;

	if(!is_moderator($fid, "caneditposts"))
		return;

	if($postoptions['lockpost'] == 1 || ($mybb->request_method != "post" && $post['locked'] != 0))
	{
		$postoptionschecked['lockpost'] = " checked=\"checked\"";
	}
	$disablesmilies .= "\n<br /><label><input type=\"checkbox\" class=\"checkbox\" name=\"postoptions[lockpost]\" value=\"1\" tabindex=\"6\"{$postoptionschecked['lockpost']} /> {$lang->options_lock}</label>";
}

$plugins->add_hook('datahandler_post_update', 'lockposts_save');
function lockposts_save()
{
	global $fid, $post, $db, $mybb;
	if(!is_moderator($fid, "caneditposts") || THIS_SCRIPT == 'xmlhttp.php')
		return;

	$locked = array(
		"locked" => intval($mybb->input['postoptions']['lockpost']),
	);
	$db->update_query("posts", $locked, "pid='{$post['pid']}'");
}

$plugins->add_hook('postbit', 'lockposts_postbit');
function lockposts_postbit($post)
{
	global $fid, $lang, $mybb, $theme;
	if($post['locked'] == 0)
		return;

	if(!is_moderator($fid, "caneditposts"))
	{
		$post['button_edit'] = '';
	}
	if(!is_moderator($fid, "candeleteposts"))
	{
		$post['button_quickdelete'] = '';
	}

	if(!is_moderator($fid) && $post['uid'] != $mybb->user['uid'])
		return;

	$lang->load('lockposts');

	$post['button_edit'] = '<img src="'.$theme['imgdir'].'/lock.png" alt="'.htmlspecialchars_uni($lang->locked).'" title="'.htmlspecialchars_uni(str_replace('<br />', "\n", $lang->post_locked)).'" /> '.$post['button_edit'];
	return $post;
}

$plugins->add_hook('xmlhttp', 'lockposts_xmlhttp');
function lockposts_xmlhttp()
{
	global $mybb;
	if($mybb->input['action'] == "edit_post")
		lockposts();
}

$plugins->add_hook('showthread_end', 'lockposts_posttools');
function lockposts_posttools()
{
	global $moderationoptions, $lang;
	$lang->load('lockposts');

	$moderationoptions = str_replace("<span class=\"smalltext\"><strong>{$lang->inline_post_moderation}</strong></span>
<select name=\"action\">
<optgroup label=\"{$lang->standard_mod_tools}\">", "<span class=\"smalltext\"><strong>{$lang->inline_post_moderation}</strong></span>
<select name=\"action\">
<optgroup label=\"{$lang->standard_mod_tools}\">
	<option value=\"lockposts\">{$lang->inline_lock_posts}</option>
	<option value=\"unlockposts\">{$lang->inline_unlock_posts}</option>", $moderationoptions);
}

$plugins->add_hook('moderation_start', 'lockposts_moderation');
function lockposts_moderation()
{
	global $mybb, $lang, $db;
	if($mybb->input['modtype'] != 'inlinepost')
		return;

	$lang->load('lockposts');

	$tid = intval($mybb->input['tid']);
	$fid = intval($mybb->input['fid']);
	$posts = getids($tid, 'thread');
	if(count($posts) < 1)
	{
		$mybb->input['action'] = 'multiunapprovethreads'; // Display correct error
		return;
	}

	$lockpost = 0;
	if($mybb->input['action'] == 'lockposts')
		$lockpost = 1;
	else if($mybb->input['action'] != 'unlockposts')
		return;

	foreach($posts as $pid)
	{
		$post = get_post($pid);
		if($post['pid'] > 0)
		{
			$locked = array(
				"locked" => intval($lockpost),
			);
			$db->update_query("posts", $locked, "pid='{$post['pid']}'");
		}
	}

	clearinline($tid, 'thread');
	if($lockpost)
		moderation_redirect(get_post_link($posts[0])."#pid{$posts[0]}", $lang->redirect_inline_postslocked);
	else
		moderation_redirect(get_post_link($posts[0])."#pid{$posts[0]}", $lang->redirect_inline_postsunlocked);
}
