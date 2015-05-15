<?php
$ImageArray = array();

$File = glob("/var/www/images/motion/b8:27:eb:d5:dc:f0/*.{jpg,avi}", GLOB_BRACE);
usort($File, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));

foreach ($File as $filename) {
	array_push($ImageArray, str_replace("/var/www/", "", $filename));
}
print_r($ImageArray);
?>