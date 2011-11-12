<?php
/**
 * If the script is running in the root then do NOT fill it up.
 * But if it is in a folder put here that folder name ( DO NOT USE 'FORWARD SLASH'=> / NOR 'BACK SLASH'=> \ ) .
 */
define('SCRIPT_ROOT_FOLDER_NAME', 'RastgoFramework');

/**
 * Web site protocol that are useing, like (http, https) .
 */
define('WEB_PROTOCOL', 'http');

/**
 * The Template Folder Name.
 */
define('TEMPLATE_FOLDER_NAME', 'defaultTemplate');

/*
 * If its set to true then the port number will be added to the end of the domain (or IP number), otherwise (in false) nothing will be added.
 */
define('Port_Number_In_URL', TRUE);
