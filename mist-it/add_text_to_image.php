<?php
     
     function watermarkImage($fileget,$latitude,$longitude,$watermarktext,$address, $saveto) 
     { 
         list($width, $height) = getimagesize($fileget);
         $extension = pathinfo($fileget, PATHINFO_EXTENSION);
    
         $image_p = imagecreatetruecolor($width, $height);
         $size = getimagesize($fileget);
         $dest_x = $size[0] - $width - 5;  
         $dest_y = $size[1] - $height - 5;
         switch ($extension)
         {
           case 'png':
             $image = imagecreatefrompng($fileget);
           break;
           case 'gif':
             $image = imagecreatefromgif($fileget);
           break;
           case 'jpeg':
             $image = imagecreatefromjpeg($fileget);
           break;
           case 'jpg':
             $image = imagecreatefromjpeg($fileget);
           break;
           default:
             throw new InvalidArgumentException('File "'.$fileget.'" is not valid jpg, png or gif image.');
           break;  
         }
 
         imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
         $white = imagecolorallocate($image_p, 255, 255, 255);
         $black = imagecolorallocate($image_p, 0, 0, 0 );
         $font = 'C:\xampp\htdocs\arial.TTF';
         
         $font_size = 12;
        
  
      //   $address_bbx = imagettfbbox($font_size, 0, $font,require 'map.php');
   
         $latitude_bbx = imagettfbbox($font_size, 0, $font, $latitude);
         $localtime_bbox = imagettfbbox($font_size, 0, $font, date('h:i:s A'));
         $gmttime_bbox = imagettfbbox($font_size, 0, $font, gmdate('h:i:s A'));
         
         $longitude_bbx = imagettfbbox($font_size, 0, $font, $longitude);
         $mist_bbox = imagettfbbox($font_size, 0, $font, "Mist IT Services Pvt Ltd");
         $bbox = imagettfbbox($font_size, 0, $font, $watermarktext);
 
       //  $x_address =  $address_bbx[1] + 50;
        // $y_address = $address_bbx[1] + (imagesy($image)) - ($address_bbx[5] / 2) - 130;
 
         $x_latitude =  $longitude_bbx[2];
         $y_latitude = $latitude_bbx[1] + (imagesy($image)) - ($latitude_bbx[5] / 2) - 80;
         
         $x_latitude =  $longitude_bbx[2];
         $y_latitude = $latitude_bbx[1] + (imagesy($image)) - ($latitude_bbx[5] / 2) - 80;
       
         $x_localtime =  $longitude_bbx[2];
         $y_localtime = $localtime_bbox[1] + (imagesy($image)) - ($localtime_bbox[5] / 2) - 50;
 
         $x_gmttime =  $longitude_bbx[2];
         $y_gmttime = $gmttime_bbox[1] + (imagesy($image)) - ($gmttime_bbox[5] / 2) - 20;
     
 
         $x_longitude = $longitude_bbx[0] + (imagesx($image)) - ($longitude_bbx[4] / 2) - 200;
         $y_longitude = $longitude_bbx[1] + (imagesy($image)) - ($longitude_bbx[5] / 2) - 80;
 
         $x = $bbox[0] + (imagesx($image)) - ($bbox[4] / 2) - 160;
         $y = $bbox[1] + (imagesy($image)) - ($bbox[5] / 2) - 50;
 
         $x_mist = $mist_bbox[0] + (imagesx($image)) - ($mist_bbox[4] / 2) - 160;
         $y_mist = $mist_bbox[1] + (imagesy($image)) - ($mist_bbox[5] / 2) - 20;
 
         imagefilledrectangle($image_p,0,imagesy($image),imagesx($image), imagesy($image) - 150, $black);
         imagettftext($image_p, $font_size, 0, $x_latitude,$y_latitude, $white, $font, require 'map.php');

       //  imagettftext($image_p, $font_size, 0, $x_latitude,$y_latitude, $white, $font, "Latitude : ".$latitude);
         imagettftext($image_p, $font_size, 0, $x_localtime,$y_localtime, $white, $font, "Local ". date('h:i:s A'));
         imagettftext($image_p, $font_size, 0, $x_gmttime,$y_gmttime, $white, $font, "GMT ". gmdate('h:i:s A'));
 
         imagettftext($image_p, $font_size, 0, $x, $y, $white, $font, $watermarktext);
         imagettftext($image_p, $font_size, 0, $x_longitude,$y_longitude, $white, $font, "Longitude : ".$longitude);
         imagettftext($image_p, $font_size, 0, $x_mist, $y_mist , $white, $font,"Mist IT Services Pvt Ltd");
 
         switch ($extension)
         {
           case 'png':
             imagepng ($image_p, $saveto, 9); 
           break;
           case 'gif':
             imagegif($image_p, $saveto, 9);
           break;
           case 'jpeg':
             imagejpeg($image_p, $saveto, 9);
           break;
           case 'jpg':
             imagejpeg($image_p, $saveto, 9);
           break;
           default:
             throw new InvalidArgumentException('File "'.$fileget.'" is not valid jpg, png or gif image.');
           break;  
         }
         
       
         imagedestroy($image); 
         imagedestroy($image_p); 
     }

    function watermarkImage1($fileget,$latitude,$longitude,$watermarktext, $saveto) 
    { 
        list($width, $height) = getimagesize($fileget);
        $extension = pathinfo($fileget, PATHINFO_EXTENSION);
  
 
        $image_p = imagecreatetruecolor($width, $height);
        $size = getimagesize($fileget);
        $dest_x = $size[0] - $width - 5;  
        $dest_y = $size[1] - $height - 5;
        switch ($extension)
        {
          case 'png':
            $image = imagecreatefrompng($fileget);
          break;
          case 'gif':
            $image = imagecreatefromgif($fileget);
          break;
          case 'jpeg':
            $image = imagecreatefromjpeg($fileget);
          break;
          case 'jpg':
            $image = imagecreatefromjpeg($fileget);
          break;
          default:
            throw new InvalidArgumentException('File "'.$fileget.'" is not valid jpg, png or gif image.');
          break;  
        }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
        $white = imagecolorallocate($image_p, 255, 255, 255);
        $black = imagecolorallocate($image_p, 0, 0, 0 );
        $font = 'C:\xampp\htdocs\arial.TTF';
    
        $font_size = (1.5 / 100) * imagesy($image);
        $address_loc = "Dr. Babasaheb Ambedkar,Juinagar,Navi Mumbai,Mumbai Metropolitan Region,Thane,Maharashtra,8546454,India";
        $arrdress_array = explode(",",$address_loc);
         
        $address_count =  count($arrdress_array);
       
        if($address_count % 2 == 0){
          $division =   $address_count / 2;
          
        }
        else{
          $division =   $address_count / 2;
          $division =  $division - 0.5;
        }
        $address = '';
        foreach ($arrdress_array as $key => $value) {
         if($division > 3)
         {
          if($key ==  3)
          {
            $address .= "\n";
          }
         }
         else{
          if($key ==  $division)
          {
            $address .= "\n";
          }
         }
         
          $address .= $value;
          
          if($address_count - 1 != $key)
          {
            $address .= ',';
          }
         
          
        }
      //  $address = $arrdress_array[0].",".$arrdress_array[1].",".$arrdress_array[2].",". $arrdress_array[3]."\n";
      //  $address .= $arrdress_array[4].",".$arrdress_array[5].",".$arrdress_array[6].",".$arrdress_array[7];
    
        #$address = nl2br($address,50)
        //$address_bbx = imagettfbbox($font_size, 0, $font, $address);
       // $r = shell_exec("python3 /var/www/html/mist-it/convert.py 2>&1"); 
       // echo $r;
        /*ob_start();
        passthru('C:\Program Files\Python310 ../../../python/convert.py arg1 arg2');
        $output = ob_get_clean(); 
   print_r($output);
        echo $output;*/

        $address_bbx = imagettfbbox($font_size, 0, $font, $address);
      
        $latitude_bbx = imagettfbbox($font_size, 0, $font, "Latitude : ".$latitude);
        $localtime_bbox = imagettfbbox($font_size, 0, $font, "Local ". date('h:i:s A'));
        $gmttime_bbox = imagettfbbox($font_size, 0, $font,"GMT ". gmdate('h:i:s A'));
        
        $longitude_bbx = imagettfbbox($font_size, 0, $font, "Longitude : ". $longitude);
        $mist_bbox = imagettfbbox($font_size, 0, $font, "Mist IT Services Pvt Ltd");
        $bbox = imagettfbbox($font_size, 0, $font, $watermarktext);

        $x_address =  $address_bbx[2];
        $y_address = $address_bbx[1] + (imagesy($image)) - ($address_bbx[5] / 2);

        $x_latitude =  $latitude_bbx[2];
        $y_latitude = $latitude_bbx[1] + (imagesy($image)) - ($latitude_bbx[5] / 2);
        
        
      
        $x_localtime =  $localtime_bbox[2];
        $y_localtime = $localtime_bbox[1] + (imagesy($image)) - ($localtime_bbox[5] / 2);

        $x_gmttime =  $gmttime_bbox[2];
        $y_gmttime = $gmttime_bbox[1] + (imagesy($image)) - ($gmttime_bbox[5] / 2);

        $x_longitude =  $longitude_bbx[0] + (imagesx($image)) - ($longitude_bbx[4]);
        $y_longitude = $longitude_bbx[1] + (imagesy($image)) - ($longitude_bbx[5] / 2);

        $x = $bbox[0] + (imagesx($image)) - ($bbox[4]);
       
        $y = $bbox[1] + (imagesy($image)) - ($bbox[5] / 2);

        $x_mist = $mist_bbox[0] + (imagesx($image)) - ($mist_bbox[4]);
       
        $y_mist = $mist_bbox[1] + (imagesy($image)) - ($mist_bbox[5] / 2);
    //print_r(imagesy($image));print_r('hii');print_r(imagesx($image));print_r('hii');print_r(imagesy($image) - imagesx($image));exit();
        imagefilledrectangle($image_p,0,imagesy($image),imagesx($image),imagesy($image) -  ((15 / 100) * imagesy($image)), $black);

        imagettftext($image_p, $font_size, 0,(5 / 100) * $x_address,(84 / 100) * $y_address, $white, $font, $address);

        imagettftext($image_p, $font_size, 0,(5 / 100) * $x_latitude,(92 / 100) * $y_latitude, $white, $font, "Latitude : ".$latitude);
        imagettftext($image_p, $font_size, 0,(5 / 100) * $x_localtime,(95 / 100) * $y_localtime, $white, $font, "Local ". date('h:i:s A'));
        imagettftext($image_p, $font_size, 0,(5 / 100) * $x_gmttime,(98 / 100) *  $y_gmttime, $white, $font, "GMT ". gmdate('h:i:s A'));

        imagettftext($image_p, $font_size, 0,$x_longitude - (5 / 100) * $x_longitude,(92 / 100) * $y_longitude, $white, $font, "Longitude : ".$longitude);
        imagettftext($image_p, $font_size, 0,$x - (5 / 100) * $x_longitude,(95 / 100) * $y, $white, $font, $watermarktext);
        imagettftext($image_p, $font_size, 0,$x_mist - (5 / 100) * $x_longitude, (98 / 100) *  $y_mist , $white, $font,"Mist IT Services Pvt Ltd");

        switch ($extension)
        {
          case 'png':
            imagepng ($image_p, $saveto, 9); 
          break;
          case 'gif':
            imagegif($image_p, $saveto, 9);
          break;
          case 'jpeg':
            imagejpeg($image_p, $saveto, 9);
          break;
          case 'jpg':
            imagejpeg($image_p, $saveto, 9);
          break;
          default:
            throw new InvalidArgumentException('File "'.$fileget.'" is not valid jpg, png or gif image.');
          break;  
        }
        
      
        imagedestroy($image); 
        imagedestroy($image_p); 
    }

    watermarkImage1('./uploads/address/vendor_file/test.jpg','19.0842','72.8851',date('l  d-m-Y' ),'./uploads/address/vendor_file/test1.jpg')

?>
  
   

