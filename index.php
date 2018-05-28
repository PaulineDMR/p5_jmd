<?php 

require 'models/Artist.php';

$artist = new Artist();

$artist->setId(2);
echo $artist->getId();
echo "</br>";

var_dump($artist);