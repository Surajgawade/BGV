 <!--<!DOCTYPE html>
 <html>
 <head>
     <meta charset="utf-8">
     <title></title>
    
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css">
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
       
 </head>
 <body>
    <input type="text" name="current_lat" id="current_lat" value="19.1271868">
<input type="text" name="current_long" id="current_long" value="72.9838411">
   <div class="col-12">
      <div class="card m-b-20">    
        <h4 class="mt-0 header-title">Locator View</h4>
          <div id="map" class="gmaps"></div>
      </div>
  </div>
 </body>
 </html>
<script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/waves.min.js"></script>
    <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>-->
   <!-- <script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCetWiu5VcxfP2f77rdIONvFHG_DZjFqBQ&#038;ver=1"></script>-->
   <!-- <script src="assets/js/bootstrap-notify.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/custom.js"></script>-->


    <?php 
/*function IPtoLocation($ip){ 
    $apiURL = 'http://freegeoip.app/json/'.$ip; 
     
    // Make HTTP GET request using cURL 
    $ch = curl_init($apiURL); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $apiResponse = curl_exec($ch); 
    print_r($apiResponse);
    if($apiResponse === FALSE) { 
        $msg = curl_error($ch); 
        curl_close($ch); 
        return false; 
    } 
    curl_close($ch); 
     
    // Retrieve IP data from API response 
    $ipData = json_decode($apiResponse, true); 
     
    // Return geolocation data 
    return !empty($ipData)?$ipData:false; 
}
    $userIP = '162.222.198.75'; 
$locationInfo = IPtoLocation($userIP);
print_r($locationInfo);
*/
/*$ip = '115.112.52.186';
$ch = curl_init('http://ipwho.is/'.$ip);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$ipwhois = json_decode(curl_exec($ch), true);
curl_close($ch);
//print_r($ipwhois);
//echo $ipwhois['country'];
echo var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip)));*/

/*$ip = '98.229.152.237';
$xml = simplexml_load_file("http://ipinfodb.com/ip_query.php?ip=$ip");
print_r($xml);*/
/*
$ip = '115.112.52.186'; 

$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
if($query && $query['status'] == 'success') {
echo 'My IP: '.$query['query'].', '.$query['isp'].', '.$query['org'].', '.$query ['country'].', '.$query['regionName'].', '.$query['city'].'!';
} else {
echo 'Unable to get location';
}*/


    //Send request and receive latitude and longitude
   /* $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=44.4647452,7.3553838&sensor=false';
    $json = @file_get_contents($url);
    print_r($json);
    $data = json_decode($json);
    $status = $data->status;
    if($status=="OK"){
        $location = $data->results[0]->formatted_address;
    }else{
        $location =  'No location found.';
    }
    echo $location; */

/*
    $command = "python /path/to/python_script.py 2>&1";
$pid = popen( $command,"r");
while( !feof( $pid ) )
{
 echo fread($pid, 256);
 flush();
 ob_flush();
 usleep(100000);
}
pclose($pid);*/
/*$lon = '19.067421';
$lat = '72.99382';

function getAddress($RG_Lat,$RG_Lon)
{
  $json = "http://nominatim.openstreetmap.org/reverse?format=json&lat=".$RG_Lat."&lon=".$RG_Lon."&zoom=27&addressdetails=1";
 
  $ch = curl_init($json);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  $jsonfile = curl_exec($ch);
  print_r($jsonfile);
  curl_close($ch);



  $RG_array = json_decode($jsonfile,true);

  return $RG_array['display_name'];
  // $RG_array['address']['city'];
  // $RG_array['address']['country'];
}

$addr = getAddress($lat,$lon);
echo "Address: ".$addr;
*/

//$output = shell_exec('C://python/convert.py');
//print ($output);

$pyscript = 'C:\\wamp\\www\\testing\\scripts\\imageHandle.py';
$python = 'C:\\Python27\\python.exe';

$cmd = "python $pyscript $filePath";
echo $cmd;
`$cmd`
?>