<div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div>  
        <?php        
          if(!empty($university_attachments))
          {

            echo '<div class="col-md-12 col-sm-12 col-xs-12" >';
            echo "<div class = 'row'>";
            echo '<div class="col-md-10 col-sm-10 col-xs-10" style = "border: 1px solid black; align :center;">';
            echo 'University Attachment';
            echo "</div></div>";
            echo "<div class = 'row'>";
            echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
            echo 'Image';
            echo "</div>";
            echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
            echo 'Attachment';
            echo "</div></div></div>";
                      
            for($i=0; $i < count($university_attachments); $i++) 
            { 
                
              if($university_attachments[$i]['status'] == "1")
              {  
                $url  = "'".SITE_URL.UNIVERSITY_PIC;
                $actual_file  = $university_attachments[$i]['file_name']."'";
                $myWin  = "'"."myWin"."'";
                $attribute  = "'"."height=250,width=480"."'";

                echo '<div class="col-md-12 col-sm-12 col-xs-12">';
                echo "<div class = 'row'>";
                echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                               
               
                echo '<a href="javascript:;" onClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';
                echo "</div>";

                echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';               
               
                echo "<label><input type='checkbox' name='university_add_file[]' value='".$university_attachments[$i]['file_name']."' id='university_add_file' > Add Attachment</label>";
                              
                echo '</div></div></div>';
              }
            }
          }
       ?>

      </div>             
    </div>