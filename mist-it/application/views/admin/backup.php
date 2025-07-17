<div class="content-page">
<div class="content">
<div class="container-fluid">
<style>
h3{
font-family: Verdana;
font-size: 18pt;
font-style: normal;
font-weight: bold;
color:red;
text-align: center;
}
table{
font-family: Verdana;
color:black;
font-size: 12pt;
font-style: normal;
font-weight: bold;
text-align:left;
border-collapse: collapse;
}
.error{
color:red;
font-size: 11px;
}
</style>
<h3>Database Backup</h3>
<?php echo form_open(ADMIN_SITE_URL.'backup/take_backup',array('name' => 'backup')); ?>
<table align="center" cellpadding = "5">
<tr>
<?php   if($this->user_info['tbl_roles_id'] == '1') {  ?>
<td colspan="5" align="center">
<input type="submit" name="backup" class="btn btn-success" value="Take Backup"/></td>

</tr>
</table>
<?php echo form_close();?>
<?php
$folder = SITE_BASE_PATH . UPLOAD_FOLDER . 'backup/';
if ($dir = opendir($folder))
{
   echo '<table align="center" cellpadding = "5" border = "1">
      <tr>
      <th>SrID</th>
      <th>File Name</th>
      <th>Size</th>
      <th>Action</th></tr>';
    $i = 1;
    
	while (($file = readdir($dir)) !== false)
	{
	 	if($file != "." && $file != ".."){

	 		$files = explode('_',$file);
	 		$date = $files[1];
	 		$today_date = date('Y-m-d');
            $week_less = date("Y-m-d", strtotime("-7 days", strtotime($today_date)));
	 	    echo "<tr>";
	 	    $filesize = filesize(SITE_BASE_PATH . UPLOAD_FOLDER . 'backup/'.$file);
	 	    echo form_open(ADMIN_SITE_URL.'backup/delete_backup',array('name' => 'backup'));
	 		echo "<input type='hidden' name='file_name' value='".$file."'>";
	 	    echo "<td>".$i."</td>";
	 	    echo "<td>".$file."</td>";
	 	    echo "<td>".round($filesize / 1024 / 1024, 1)." MB"."</td>";
	 	    if($date < $week_less)
	 	    {
	 	      echo "<td><input type='submit' name='delete_file' value='Delete File'></td>";
	 	    }
	 	    else
	 	    {
                echo "<td></td>";
	 	    }
	 	    echo form_close();
	 	    echo "</tr>";
	 	   
	
	      $i++;
	    }
	}
	
	echo '</table>';
   
   closedir($dir);
}
?>
<?php } ?>
</div>
</div>
</div>