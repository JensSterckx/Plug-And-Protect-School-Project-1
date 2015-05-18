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
 *	File: Master configuration file.
 *		Contains all the information about connecting to the cluster etc.
**/

/** DEFINE: Cluster Hostname **/
$CluserHostName = "cluster.plugandprotect.eu";
//$CluserHostName = "master.plugandprotect.eu";

/** DEFINE: Clients MAC Address **/
exec("cat /sys/class/net/eth0/address", $MAC);
$MAC = $MAC[0];

/** DEFINE: Local ip Address **/
exec("hostname -I", $IP);
$IP = $IP[0];

/** DEFINE: Module's short hostname **/
exec("hostname -s", $HOSTNAME);
$HOSTNAME = $HOSTNAME[0];

?>
