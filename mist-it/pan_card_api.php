<?php 
$api_key = "A8GMCVY-V6W4S6K-K0RSSAW-C1AJ3FN";
$api_id = "62b6a19dda7e35001d91e4ea";
$pan_number = "BRSPG4424N";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://test.zoop.one/api/v1/in/identity/pan/lite");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, array("api-key:" . $api_key,"app-id:" . $api_id));  
curl_setopt($ch, CURLOPT_POSTFIELDS,array("customer_pan_number:" . $pan_number,"consent:" . "Y","consent_text": "I hear by declare my consent agreement for fetching my information via ZOOP API."));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
if(curl_errno($ch)){
    echo 'Request Error:' . curl_error($ch);
}
curl_close ($ch);

$result_output = json_decode($server_output, true);
//if($result_output->result->pan_status == "Valid") {
   print_r($result_output->result);
//}

   //$pan_number = $result_output->result->pan_number; 
   //$pan_status = $result_output->result->pan_status; 
   //$pan_name = $result_output->result->user_full_name;

   $pan_number = $result_output->result->pan_number;
   $pan_status = $result_output->result->pan_status;
   $pan_name = $result_output->result->user_full_name;

  // $font = 'C:\xampp\htdocs\arial.TTF';
   $font = '/var/www/html/mist-it/arial.ttf';

   $font_size = 12;

   $img = imagecreate(500, 450);
  
   $image_surce = "./correct.jpg";
   
   $str=imagecreatefromjpeg($image_surce);
   $bgcolor = imagecolorallocate($img, 255, 255, 255);
   $fontcolor = imagecolorallocate($img, 0, 0, 0);
   $text_color = imagecolorallocate($img, 0, 0, 255);
   imagecopy($img, $str, 180, 2, 0, 0, 150, 144);

   
   imagestring($img, $font_size, 110, 160, "Pan Card Verification Completed", $fontcolor);
   imagesetthickness($img, 5);
   imageline($img, 50, 180, 450, 180, $text_color);//blue line
   imagesetthickness($img, 2);
   imageline($img, 50, 190, 450, 190, $fontcolor);//top line
   
   imageline($img, 50, 190, 50, 340, $fontcolor);//Left Line

   imagestring($img, $font_size, 70, 200, "Pan No", $fontcolor);
   imagestring($img, $font_size, 150, 200, $pan_number, $fontcolor);

   imagestring($img, $font_size, 70, 220, "Name", $fontcolor);

   
   $character_count = strlen($pan_name);

    if($character_count > '33')
    {
   	   $pan_name_detials = explode(' ',$pan_name);

   	   imagestring($img, $font_size, 150, 220, $pan_name_detials[0], $fontcolor);
       imagestring($img, $font_size, 150, 240, $pan_name_detials[1], $fontcolor);
       imagestring($img, $font_size, 150, 260, $pan_name_detials[2], $fontcolor);
       imagestring($img, $font_size, 150, 280, $pan_name_detials[3], $fontcolor);
       imagestring($img, $font_size, 150, 300, $pan_name_detials[4], $fontcolor);
    }
    else
    {
    	imagestring($img, $font_size, 150, 220,$pan_name, $fontcolor);

    }
  

   imagestring($img, $font_size, 70, 320, "Satus", $fontcolor);
   imagestring($img, $font_size, 150, 320, $pan_status, $fontcolor);

   imageline($img, 450, 190, 450, 340, $fontcolor);//right line
   imageline($img, 50, 340, 450, 340, $fontcolor);//Bottom Line
   imageline($img, 140, 190, 140, 340, $fontcolor);// middle Line
   imageline($img, 50, 220, 450, 220, $fontcolor);//Pan No line
   imageline($img, 50, 320, 450, 320, $fontcolor);//Bottom Line


   header("Content-Type: image/png");
   imagepng($img);
   imagedestroy($img);

  
?>
