<?php

/**
* phpBB Extension - marttiphpbb Extra Javascript
* @copyright (c) 2018 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [

	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_CREATE'				
		=> 'Create file',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETE'				
		=> 'Delete',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETE_NAME'			
		=> 'Delete %s',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_SIZE'					
		=> 'Size',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME'					
		=> 'Name',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_RULES'			=> 
		'The name must begin with a lowercase alphabetical character and can contain only
		lowercase alphanumerical characters and enclosed dashes.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE'						
		=> 'File',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_SAVE_CONFIRM'				
		=> 'Do you want to save the file %s?',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_SAVE'						
		=> 'Save',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_SAVED'				
		=> 'The file %s has been saved successfully!',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_EMPTY'			
		=> 'The file name was empty.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_ALREADY_EXISTS'		
		=> 'The file name %s already exists.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_NAME_INVALID_FORMAT'		
		=> 'The file name %s is an invalid format.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETE_CONFIRM'		
		=> 'Delete file %s ?',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DELETED'				
		=> 'The file %s has been deleted.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_FILE_DOES_NOT_EXIST'		
		=> 'The file %s does not exist.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_NO_FILES'
		=> 'There are no extra Javascript files to edit yet. You can create one in the page <a href="%s">"files"</a>.',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_SCRIPT_NAMES'
		=> 'Script names',
	'ACP_MARTTIPHPBB_EXTRAJAVASCRIPT_SCRIPT_NAMES_EXPLAIN'
		=> 'A comma separated list of script names (without the .php extension) 
		to define when this Javascript file should be loaded. i.e. viewforum, viewtopic, index 
		Javascript file content should as much be grouped together in order to avoid the number of files loaded.',		
]);
