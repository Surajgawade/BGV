<?php
    function compress_image($source, $destination, $quality) {
      
        $info = pathinfo($source);
        $exif = exif_read_data($source);
        #print_r($exif);exit();
        if ($info['extension'] == 'jpeg') 
        {
            $image = imagecreatefromjpeg($source);
        }
        elseif ($info['extension'] == 'jpg') 
        {
            $image = imagecreatefromjpeg($source);
        }
        elseif ($info['extension'] == 'gif') 
        {
            $image = imagecreatefromgif($source);
        }

        elseif ($info['extension'] == 'png') 
        {
            $image = imagecreatefrompng($source);
        }
        
       
     

       imagejpeg($image, $destination, $quality);
       #imagecopyresampled($destination, $source, 0, 0, 0, 0, $exif['COMPUTED']['Width'], $exif['COMPUTED']['Width'], $width, $height);
        
        return $destination;
    }



 compress_image('./16548315047033334682850721281389_9654_2022-06-10-08-57-26.jpg', './16548315047033334682850721281389_9654_2022-06-10-08-57-261.jpg', 80);



 
?>