<?php 

echo PHP_VERSION;
echo version_compare(PHP_VERSION, '5.0.0') < 0 ? "less" : "greater";

phpinfo(); 
?>

