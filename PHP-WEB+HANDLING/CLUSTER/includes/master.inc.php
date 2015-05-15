<?php
/**
 *	Plug & Protect
 *
 *	-> Sterckx Jens
 *	-> Moinil Wesley
 *	-> Dauwe Jelle
 *
 *
 *
 *	File: Include file.
 *		Will include all required files in one.
**/


require_once(__DIR__ . "/SessionHandler.php");
include(__DIR__ . "/SessionSet.php");
include(__DIR__ . "/MySQL.php");
include(__DIR__ . "/LogHandler.php");
include(__DIR__ . "/AuthHandler.php");

include(__DIR__ . "/DashBoardHandler.php");
include(__DIR__ . "/SettingsHandler.php");
include(__DIR__ . "/MenuHandler.php");



?>