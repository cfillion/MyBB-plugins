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

function loadtemplates_info()
{
	return array(
		"name"			=> "Load Templates",
		"description"	=> "This plugin allows you to load some custom templates so they can be used in other templates without core edit.",
		"website"		=> "http://www.1er-mouvement.ca",
		"author"		=> "cfillion",
		"authorsite"	=> "http://www.1er-mouvement.ca",
		"version"		=> "1.3",
		"guid" 			=> "6b0cb3af112fb72816e14631aabb3155",
		"compatibility" => "16*"
	);
}

function loadtemplates_activate()
{
	global $db, $mybb;

	$result = $db->simple_select('settinggroups', 'MAX(disporder) AS max_disporder');
	$max_disporder = $db->fetch_field($result, 'max_disporder');

	$loadtemplates = array(
		"name" => "loadtemplates",
		"title" => "Load Templates",
		"description" => "Manage Load Templates plugin (by cfillion)",
		"disporder" => intval($max_disporder) + 1,
		"isdefault" => "0",
	);
	$group['gid'] = $db->insert_query('settinggroups', $loadtemplates);
	$gid = $db->insert_id();

	$new_setting1 = array(
		'name'			=> 'loadtemplates_enable',
		'title'			=> 'Enable plugin',
		'description'	=> '',
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> '1',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting1);

	$new_setting2 = array(
		'name'			=> 'loadtemplates_templates',
		'title'			=> 'Templates List',
		'description'	=> 'Enter wich templates to load. Variable name will be the same as template name. One template by line.<br />
You can add some conditions:
<ul><li><strong>template|online</strong> = User is online</li>
<li><strong>template|u=1</strong> = User ID is 1</li>
<li><strong>template|g=4</strong> = Displaygroup ID is 4</li>
<li><strong>template|p=index</strong> = Script name is index</li>
<li><strong>template|u=1|g=4</strong> = User ID is 1 <b>or</b> displaygroup is 4</li>
<li><strong>template|u=1&g=4</strong> = User ID is 1 <b>and</b> displaygroup is 4</li></ul>
If you need to use a global variable in your template, proceed like this:
<ul><li><strong>template~varName</strong></li>
<li><strong>template~var1,var2</strong></li>
<li><strong>template|online~var1</strong></li></ul>',
		'optionscode'	=> 'textarea',
		'value'			=> '',
		'disporder'		=> '2',
		'gid'			=> intval($gid)
	);
	$db->insert_query('settings', $new_setting2);

	rebuild_settings();
}

function loadtemplates_deactivate()
{
	global $db, $mybb;

	$db->delete_query("settinggroups", "name IN('loadtemplates')");
	$db->delete_query("settings", "name LIKE '%loadtemplates%'");
	rebuild_settings();
}

$plugins->add_hook('global_start', 'loadtemplates');
function loadtemplates()
{
	global $mybb, $templates, $db, $theme, $lang;
	if($mybb->settings['loadtemplates_enable'] != 0)
	{
		// The $theme var is defined after the global_start hook. We can not use global_end hook because many templates are loaded between these hooks.
		{
			$loadstyle = '';
			if(isset($style['style']) && $style['style'] > 0)
			{
				// This theme is forced upon the user, overriding their selection
				if($style['overridestyle'] == 1 || !isset($mybb->user['style']))
				{
					$loadstyle = "tid='".intval($style['style'])."'";
				}
			}

			// After all of that no theme? Load the board default
			if(empty($loadstyle))
			{
				$loadstyle = "def='1'";
			}

			$query = $db->simple_select("themes", "name, tid, properties, stylesheets", $loadstyle, array('limit' => 1));
			$theme = $db->fetch_array($query);

			// No theme was found - we attempt to load the master or any other theme
			if(!$theme['tid'])
			{
				// Missing theme was from a forum, run a query to set any forums using the theme to the default
				if($load_from_forum == 1)
				{
					$db->update_query("forums", array("style" => 0), "style='{$style['style']}'");
				}
				// Missing theme was from a user, run a query to set any users using the theme to the default
				else if($load_from_user == 1)
				{
					$db->update_query("users", array("style" => 0), "style='{$style['style']}'");
				}
				// Attempt to load the master or any other theme if the master is not available
				$query = $db->simple_select("themes", "name, tid, properties, stylesheets", "", array("order_by" => "tid", "limit" => 1));
				$theme = $db->fetch_array($query);
			}
			$theme = @array_merge($theme, unserialize($theme['properties']));
		}

		$tplList = explode("\n", $mybb->settings['loadtemplates_templates']);
		$tplList = array_filter($tplList, 'strlen');
		$tplList = array_unique($tplList, SORT_STRING);

		foreach($tplList as $tpl)
		{
			$tpl = str_replace("\r", '', $tpl);
			$tpl = str_replace("\n", '', $tpl);
			$tpl = str_replace('\s', '', $tpl);

			if(preg_match('#~#', $tpl))
			{
				$varList = explode(',', preg_replace('#^.+~(.+)$#U', '$1', $tpl));
				$tpl = preg_replace('#^(.+)~.+$#U', '$1', $tpl);
				foreach($varList as $globalVar)
				{
					global ${$globalVar};
				}
			}

			if(preg_match('#\|#', $tpl))
			{
				$cond = preg_replace('#^.+\|(.+)$#U', '$1', $tpl);
				$tpl = preg_replace('#^(.+)\|.+$#U', '$1', $tpl);
				if(preg_match('#^(((u|g|p)(=|!=)[0-9a-zA-Z]+|online)($|\||&))+#', $cond))
				{
					$g = intval($mybb->user['displaygroup']);
					if($g < 1)
						$g = intval($mybb->user['usergroup']);

					$cond = preg_replace('#u(=|!=)#', intval($mybb->user['uid']).'$1', $cond);
					$cond = preg_replace('#g(=|!=)#', $g.'$1', $cond);
					$cond = preg_replace('#p(=|!=)#', '"'.preg_replace('#\..+$#', '', THIS_SCRIPT).'"$1', $cond);
					$cond = preg_replace('#online#', (($mybb->user['uid'] > 0) ? 'true' : 'false'), $cond);
					$cond = preg_replace('#\|#', ' || ', $cond);
					$cond = preg_replace('#&#', ' && ', $cond);
					$cond = preg_replace('#=#', '==', $cond);
					$cond = preg_replace('#!==#', '!=', $cond);

					eval('$condresult = ('.$cond.');');
					if(!$condresult)
						continue;
				}
			}

			$varName = str_replace('$', '', $tpl);
			$varName = preg_replace('[^a-zA-Z0-9_]', '_', $varName);

			global ${$varName};
			if(!isset(${$varName}))
			{
				eval("\${{$varName}} = \"".$templates->get($tpl)."\";");
			}
		}
	}
}
