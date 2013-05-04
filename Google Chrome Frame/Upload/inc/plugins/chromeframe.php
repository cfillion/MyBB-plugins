<?php
/**
 * Copyright (C) 2011 Fillion Christian
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br /><a href=\"../../index.php\">Return to homepage.</a>");
}

$plugins->add_hook("global_start", "enable_chromeframe");

function chromeframe_info()
{
	return array(
		"name"			=> "Enable Chrome Frame plugin for IE",
		"description"	=> "Google Chrome Frame is an open source plug-in that seamlessly brings Google Chrome's open web technologies and speedy JavaScript engine to Internet Explorer.",
		"website"		=> "http://mods.mybb.com/",
		"author"		=> "cfillion",
		"authorsite"	=> "http://www.1er-mouvement.ca",
		"version"		=> "1.0",
		"guid" 			=> "c8a6dc38a841aef4b6cfa3c71a1bab16",
		"compatibility" => "16*"
	);
}

function enable_chromeframe()
{
	@header('X-UA-Compatible: chrome=1');
}

function is_chromeframe_installed()
{
	return preg_match('#chromeframe#is', $_SERVER['HTTP_USER_AGENT']);
}

?>
