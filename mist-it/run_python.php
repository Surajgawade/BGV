<?php 
$latitude = "19.0545745";
$longitude= "73.017123";

$item='example';
exec("python3 /var/www/html/mist-it/convert.py $latitude $longitude 2>&1", $output, $ret_code);
print_r($output[0]);
exit();
$output = passthru('python3 /var/www/html/mist-it/convert.py $latitude $longitude');
//$r = shell_exec("python3 /var/www/html/mist-it/convert.py 2>&1"); 
echo $output;
    
exit();

?>