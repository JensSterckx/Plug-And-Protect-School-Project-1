<?php

//FILE MOET IN DE ALGEMENE INCLUDE STAAN, ANDERS KAPOEET peisk

/*
error_reporting(-1);
ini_set('display_errors', 'On');
*/

require_once('SessionHandler.php');

$session = new SessionHandlerA();

// add db data
$session->setDbDetails('localhost', 'root', 'Vm123456', 'PHPSESSIONS');

// OR alternatively send a MySQLi ressource
// $session->setDbConnection($mysqli);

$session->setDbTable('session_handler_table');
session_set_save_handler(array($session, 'open'),
                         array($session, 'close'),
                         array($session, 'read'),
                         array($session, 'write'),
                         array($session, 'destroy'),
                         array($session, 'gc'));

// The following prevents unexpected effects when using objects as save handlers.
register_shutdown_function('session_write_close');

//session_start();
//Nu kan de sessie code kome

?>