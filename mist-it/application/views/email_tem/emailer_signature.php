<table style="width:525px; font-size:10pt; font-family:Arial, sans-serif;"  cellspacing="0" cellpadding="0" border="0">

   <tr>
      <td style="font-size:10pt; width:18px; padding-right:10px; vertical-align:middle;" valign="middle">
         <p style="margin-bottom:25px; line-height:1.2">
         <strong>
            <span style="font-size:12pt; font-family:Tahoma, sans-serif; color:#f3a825"><?php echo ucwords($user_profile_info['firstname']) ?> <?php echo ucwords($user_profile_info['lastname']) ?></span>
         </strong>
         <br>
             <span style="font-family:Tahoma, sans-serif; font-size:9pt; color:#000000;"><?php echo ucwords($user_profile_info['designation']) ?> - <?php echo ucwords($user_profile_info['department']) ?></span>
         </p>
   
         <a href="<?php echo HTTPWEBSITE ?>" target="_blank" rel="noopener"> <img alt="logo" style="width:143px; height:40px; border:0;" src="<?php echo COMPANYLOGO ?>" width="143" border="0"> 
         </a>						
      </td>
  
      <td style="font-family:Tahoma, sans-serif; padding-left:15px; vertical-align:top; line-height:1.2; border-left:solid 2px #cccccc" valign="top">

      <span style="font-size:9pt; font-family:Tahoma, sans-serif;color:#000000;">O: <?php echo $this->user_info['office_phone']; ?></span>
      <span style="font-size:9pt; font-family:Tahoma, sans-serif;color:#000000;"><br>M: +91 <?php echo $this->user_info['mobile_phone']; ?>
       </span>  
      <span style="font-size:9pt; font-family:Tahoma, sans-serif;color:#000000;"><br>E: <a href="mailto:<?php echo $this->user_info['email']; ?>"><?php echo $this->user_info['email']; ?></a><br></span>
   
      <span>
         <strong>
            <a href="<?php echo HTTPWEBSITE  ?>" target="_blank" rel="noopener" style="text-decoration:none;">
               <span style="font-size:9pt; font-family:Tahoma, sans-serif; color:#01527d;"><?php echo WEBSITE  ?> </span>
            </a>
         </strong>
      </span>   
    
      <p style="margin-top:5px; margin-bottom:0">
         <strong style="font-family:Tahoma, sans-serif; font-size:9pt; color:#000000;"><?php echo CRMNAME ?></strong>
            <span style="font-size:8pt; font-family:Tahoma, sans-serif;color:#000000;"><br><?php echo COMPANYADDRESS ?> </span>
            <span style="font-size:8pt; font-family:Tahoma, sans-serif;color:#000000;"><?php echo COMPANYADDRESSCITYPIN ?></span>   
      </p>

      <p style="margin-top:20px; margin-bottom:20px"></p>
   
      <a href="<?php echo HTTPWEBSITE ?>" target="_blank" rel="noopener" style="text-decoration:none;"><img style="width:257px; height:35px; border:0;"  src="<?php echo NASSCOM ?>" width="257" border="0"></a>   
   </td>
 </tr>
</table>
