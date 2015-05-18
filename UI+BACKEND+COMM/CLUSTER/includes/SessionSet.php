<?php
$session = new SessionHandlerMySQL();

// add db data
//$session->setDbDetails('localhost', 'SeSHandler', 'User-123', 'PHPSESSIONS');
$session->setDbDetails('127.0.0.1', 'SeSHandler', 'User-123', 'PHPSESSIONS');


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

session_start();

?>