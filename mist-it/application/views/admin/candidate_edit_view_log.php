<style type="text/css">
/* table tbody, table thead
{
  display: block;
}*/
/*table tbody 
{
   overflow: auto;
   height: 500px;
}
*/
</style>
<div class="box-primary">
  
  <?php echo form_open('#', array('name'=>'frm_update_candidates','id'=>'frm_update_candidates')); ?>
<table border="1" align="center" style="display: block;  overflow: auto;">
   <thead style="display: block;">
  <tr>
  <th width = "130px";>Field</th>
  <th width = "240px";>Old Value</th>
  <?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
  <th width = "240px";>New Value</th>
  <th width = "240px";>Created On</th>
<?php  } ?>
</tr>
 </thead>
   <tbody style="display: block; overflow: auto; height: 500px; " >
   
    </tr>
<tr>
   <?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td colspan="3"> 
<?php  }else{?>
<td colspan="2"> 

<?php  }?>
  <h5 class="box-title">Case Details</h5></td>
<td rowspan="38" width = "240px";><p style="background-color:#f58d0c"> <?php
        echo  convert_db_to_display_date($candidate_details['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12);
        
      ?></p>
        
</td>
</tr>

<?php 
if(isset($candidate_details1['clientid']))
{

   if($candidate_details['clientid'] !=  isset($candidate_details1['clientid']))
        {
          
            $style = 'style="background-color:#f58d0c"';

        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>
<tr <?php echo $style; ?>>
<td> <label >Client</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  <?php
        echo form_dropdown('clientid', $clients, set_value('clientid',$candidate_details1['clientid']), 'class="form-control" id="clientid"'.$style );
       
      ?>
</td>
<?php  } ?>
<td> <?php
        echo form_dropdown('clientid', $clients, set_value('clientid',$candidate_details['clientid']), 'class="form-control" id="clientid"'.$style);
        
      ?>
        
</td>


  </tr>
<?php 
if(isset($candidate_details1['entity']))
{


if($candidate_details['entity'] !=  $candidate_details1['entity'])
        {
          
           $style = 'style="background-color:#f58d0c"';

        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
 ?>
  <tr <?php echo $style; ?>>

<td>  <label >Entity</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
  <?php
        echo form_dropdown('entity', $entity_list, set_value('entity',$candidate_details1['entity']), 'class="form-control" id="entity"'.$style);
        echo form_error('entity');
      ?>
</td>
<?php  } ?>
<td> <?php
        echo form_dropdown('entity', $entity_list, set_value('entity',$candidate_details['entity']), 'class="form-control" id="entity"'.$style);
        echo form_error('entity');
      ?>
        
</td>
</tr>
<?php 
if(isset($candidate_details1['package']))
{


if($candidate_details['package'] !=  $candidate_details1['package'])
        {
          
           $style = 'style="background-color:#f58d0c"';

        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
 ?>
<tr <?php echo $style; ?>>

<td> <label >Package</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td> 

<?php
        echo form_dropdown('package', $package_list, set_value('package',$candidate_details1['package']), 'class="form-control" id="package"'.$style);
      
      ?>      
</td>
<?php  } ?>
<td>
 <?php
        echo form_dropdown('package', $package_list, set_value('package',$candidate_details['package']), 'class="form-control" id="package"'.$style);
       
      ?> 
         
</td>

  </tr>
<?php 
if(isset($candidate_details1['caserecddate']))
{

if($candidate_details['caserecddate'] !=  $candidate_details1['caserecddate'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>
<tr <?php echo $style; ?>>
<td><label>Case Received Date</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
  <input type="text" name="caserecddate" id="caserecddate" value="<?php echo set_value('caserecddate',convert_db_to_display_date($candidate_details1['caserecddate'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
</td>
<?php  } ?>
<td>  
  <input type="text" name="caserecddate" id="caserecddate" value="<?php echo set_value('caserecddate',convert_db_to_display_date($candidate_details['caserecddate'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
        
</td>

</tr>
<?php 

if(isset($candidate_details1['ClientRefNumber']))
{

if($candidate_details['ClientRefNumber'] !=  $candidate_details1['ClientRefNumber'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td>  <label >Client Ref Number </label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
  <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details1['ClientRefNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  } ?>
<td> 
  <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>" class="form-control" <?php echo $style; ?>>
        
</td>

</tr>

<?php 


if(isset($candidate_details1['cmp_ref_no']))
{

if($candidate_details['cmp_ref_no'] !=  $candidate_details1['cmp_ref_no'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>
<tr <?php echo $style; ?>>
<td>  <label ><?php echo REFNO; ?> </label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
   <input type="text" name="cmp_ref_no" id="cmp_ref_no" value="<?php echo set_value('cmp_ref_no',$candidate_details1['cmp_ref_no']); ?>" class="form-control"  <?php echo $style; ?>>
</td>
<?php  } ?>
<td> 
   <input type="text" name="cmp_ref_no" id="cmp_ref_no"  value="<?php echo set_value('cmp_ref_no',$candidate_details['cmp_ref_no']); ?>" class="form-control"  <?php echo $style; ?>>
        
</td>

</tr>

<?php 

if(isset($candidate_details1['overallstatus']))
{
if($candidate_details['overallstatus'] !=  $candidate_details1['overallstatus'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
 ?>
<tr <?php echo $style; ?>>
<td>  <label >Overall Status </label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
   <?php
        echo form_dropdown('overallstatus', $status, set_value('overallstatus',$candidate_details1['overallstatus'],1), 'class="form-control" id="overallstatus"'.$style);
        
      ?>
</td>
<?php  } ?>
<td> 
  <?php
        echo form_dropdown('overallstatus', $status, set_value('overallstatus',$candidate_details['overallstatus'],1), 'class="form-control" id="overallstatus"'.$style);
        
      ?>
        
</td>

</tr>
<?php 


if(isset($candidate_details1['build_date']))
{
if($candidate_details['build_date'] !=  $candidate_details1['build_date'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td> <label>Billed Date</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',convert_db_to_display_date($candidate_details1['build_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
</td>
<?php  } ?>
<td> 
  <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',convert_db_to_display_date($candidate_details['build_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
        
</td>

</tr>
<?php 
if(isset($candidate_details1['overallclosuredate']))
{
if($candidate_details['overallclosuredate'] !=  $candidate_details1['overallclosuredate'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }

}
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td> <label>Closure Date</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
   <input type="text" name="overallclosuredate" id="overallclosuredate" value="<?php echo set_value('overallclosuredate',convert_db_to_display_date($candidate_details1['overallclosuredate'])); ?>" class="form-control" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
</td>
<?php  } ?>
<td> 
  <input type="text" name="overallclosuredate" id="overallclosuredate" value="<?php echo set_value('overallclosuredate',convert_db_to_display_date($candidate_details['overallclosuredate'])); ?>" class="form-control" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
        
</td>

</tr>

<tr>   <?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td colspan="3"> 
<?php  }else{?>
<td colspan="2"> 

<?php  }?>
 <h5 class="box-title">Joining Details</h5></td>
</tr>

<?php 

if(isset($candidate_details1['DateofJoining']))
{
if($candidate_details['DateofJoining'] !=  $candidate_details1['DateofJoining'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 

}
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td>  <label>Date of Joining</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
     <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining',convert_db_to_display_date($candidate_details1['DateofJoining'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
   <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining',convert_db_to_display_date($candidate_details['DateofJoining'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
        
</td>

</tr>
<?php
if(isset($candidate_details1['DesignationJoinedas']))
{ 
if($candidate_details['DesignationJoinedas'] !=  $candidate_details1['DesignationJoinedas'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td><label>Designation</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
      <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas',$candidate_details1['DesignationJoinedas']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
    <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas',$candidate_details['DesignationJoinedas']); ?>" class="form-control" <?php echo $style; ?>>
        
</td>
</tr>

<?php 

if(isset($candidate_details1['Location']))
{ 
if($candidate_details['Location'] !=  $candidate_details1['Location'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 

 }
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td> <label>Branch Location</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
     <input type="text" name="Location" id="Location" value="<?php echo set_value('Location',$candidate_details1['Location']); ?>" class="form-control" <?php echo $style; ?>>
</td>

<?php  }?>
<td> 
    <input type="text" name="Location" id="Location" value="<?php echo set_value('Location',$candidate_details['Location']); ?>" class="form-control" <?php echo $style; ?>>
        
</td>

</tr>


<?php
if(isset($candidate_details1['Department']))
{  
if($candidate_details['Department'] !=  $candidate_details1['Department'])
        {
          
          
           $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>


<tr <?php echo $style; ?>>
<td> <label>Department</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <input type="text" name="Department" id="Department" value="<?php echo set_value('Department',$candidate_details1['Department']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
    <input type="text" name="Department" id="Department" value="<?php echo set_value('Department',$candidate_details['Department']); ?>" class="form-control" <?php echo $style; ?>>
        
</td>

</tr>

<?php 

if(isset($candidate_details1['EmployeeCode']))
{  
if($candidate_details['EmployeeCode'] !=  $candidate_details1['EmployeeCode'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>

<tr <?php echo $style; ?>>
<td> <label>Employee Code</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
    <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode',$candidate_details1['EmployeeCode']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
      <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode',$candidate_details['EmployeeCode']); ?>" class="form-control" <?php echo $style; ?>>
        
</td>

</tr>
<?php  

if(isset($candidate_details1['branch_name']))
{ 
if($candidate_details['branch_name'] !=  $candidate_details1['branch_name'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
 ?>
<tr <?php echo $style; ?>>
<td> <label>Branch Name</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <input type="text" name="branch_name" id="branch_name" value="<?php echo set_value('branch_name',$candidate_details1['branch_name']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
   <input type="text" name="branch_name" id="branch_name" value="<?php echo set_value('branch_name',$candidate_details['branch_name']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>

<?php 
if(isset($candidate_details1['region']))
{ 
if($candidate_details['region'] !=  $candidate_details1['region'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>
<tr <?php echo $style; ?>>
<td> <label>Region</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <input type="text" name="region" id="region" value="<?php echo set_value('region',$candidate_details1['region']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
   <input type="text" name="region" id="region" value="<?php echo set_value('region',$candidate_details['region']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>
 <?php 
if(isset($candidate_details1['remarks']))
{
if($candidate_details['remarks'] !=  $candidate_details1['remarks'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td>  <label >Remarks</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control" <?php echo $style; ?>><?php echo set_value('remarks',$candidate_details1['remarks']); ?></textarea>
</td>
<?php  }?>
<td> 
    <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control" <?php echo $style; ?>><?php echo set_value('remarks',$candidate_details['remarks']); ?></textarea>
</td>

</tr>

<tr>
 <?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td colspan="3"> 
<?php  }else{?>
<td colspan="2"> 

<?php  }?> 
<h5 class="box-title">Candidate Details</h5></td>
</tr>
 <?php 
 if(isset($candidate_details1['CandidateName']))
{

if($candidate_details['CandidateName'] !=  $candidate_details1['CandidateName'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Candidate Name </label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details1['CandidateName']); ?>" class="form-control" <?php echo $style; ?>>
</td>

<?php  }?>
<td> 
    <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details['CandidateName']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>
<?php 
 if(isset($candidate_details1['gender']))
{
if($candidate_details['gender'] !=  $candidate_details1['gender'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Gender </label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details1['gender']), 'class="form-control" id="gender"'.$style);
        echo form_error('gender');
      ?>
</td>

<?php  }?>
<td> 
   <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details['gender']), 'class="form-control" id="gender"'.$style);
        echo form_error('gender');
      ?>
</td>

</tr>

<?php 
 if(isset($candidate_details1['DateofBirth']))
{ 
if($candidate_details['DateofBirth'] !=  $candidate_details1['DateofBirth'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Date of Birth</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
    <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details1['DateofBirth'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
    <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' <?php echo $style; ?>>
</td>

</tr>
<?php 
 if(isset($candidate_details1['NameofCandidateFather']))
{ 

if($candidate_details['NameofCandidateFather'] !=  $candidate_details1['NameofCandidateFather'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
  }
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Father's Name</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details1['NameofCandidateFather']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
 <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details['NameofCandidateFather']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>

<?php 

 if(isset($candidate_details1['MothersName']))
{ 
if($candidate_details['MothersName'] !=  $candidate_details1['MothersName'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }  
}
else
{
  $style = '';
}    
?>
<tr <?php echo $style; ?>>
<td> <label>Mother's Name</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName',$candidate_details1['MothersName']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
  <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName',$candidate_details['MothersName']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>
<?php 
 if(isset($candidate_details1['CandidatesContactNumber']))
{
if($candidate_details['CandidatesContactNumber'] !=  $candidate_details1['CandidatesContactNumber'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td><label>Primary Contact</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details1['CandidatesContactNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
   <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details['CandidatesContactNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>


</tr>

<?php 
 if(isset($candidate_details1['ContactNo1']))
{
if($candidate_details['ContactNo1'] !=  $candidate_details1['ContactNo1'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>



<tr <?php echo $style; ?>>
<td><label>Contact No (2)</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
 <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details1['ContactNo1']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
  <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details['ContactNo1']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>

<?php
 if(isset($candidate_details1['ContactNo2']))
{ 
if($candidate_details['ContactNo2'] !=  $candidate_details1['ContactNo2'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Contact No (3)</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <input type="text" name="ContactNo2" id="ContactNo2" maxlength="12" value="<?php echo set_value('ContactNo2',$candidate_details1['ContactNo2']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
 <input type="text" name="ContactNo2" id="ContactNo2" maxlength="12" value="<?php echo set_value('ContactNo2',$candidate_details['ContactNo2']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>
<?php 
 if(isset($candidate_details1['cands_email_id']))
{ 
if($candidate_details['cands_email_id'] !=  $candidate_details1['cands_email_id'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Email ID</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id',$candidate_details1['cands_email_id']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
 <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id',$candidate_details['cands_email_id']); ?>" class="form-control" <?php echo $style; ?> >
</td>

</tr>

<?php 
if(isset($candidate_details1['PANNumber']))
{ 
if($candidate_details['PANNumber'] !=  $candidate_details1['PANNumber'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
?>


<tr <?php echo $style; ?>>
<td> <label>PAN No.</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
     <input type="text" name="PANNumber" id="PANNumber" value="<?php echo set_value('PANNumber',$candidate_details1['PANNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
   <input type="text" name="PANNumber" id="PANNumber" value="<?php echo set_value('PANNumber',$candidate_details['PANNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>


<?php 
if(isset($candidate_details1['AadharNumber']))
{ 
if($candidate_details['AadharNumber'] !=  $candidate_details1['AadharNumber'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
?>
<tr <?php echo $style; ?>>
<td> <label>AADHAR No.</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
     <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber',$candidate_details1['AadharNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
    <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber',$candidate_details['AadharNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>

<?php
if(isset($candidate_details1['PassportNumber']))
{  
if($candidate_details['PassportNumber'] !=  $candidate_details1['PassportNumber'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 

}
else
{
  $style = '';
}    
?>
<tr <?php echo $style; ?>>
<td> <label>Passport No.</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
     <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber',$candidate_details1['PassportNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
   <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber',$candidate_details['PassportNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>

<?php 
if(isset($candidate_details1['BatchNumber']))
{
if($candidate_details['BatchNumber'] !=  $candidate_details1['BatchNumber'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }
}
else
{
  $style = '';
}    
?>
<tr <?php echo $style; ?>>
<td> <label>Batch No.</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
   <input type="text" name="BatchNumber" id="BatchNumber" value="<?php echo set_value('BatchNumber',$candidate_details1['BatchNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
  <input type="text" name="BatchNumber" id="BatchNumber" value="<?php echo set_value('BatchNumber',$candidate_details['BatchNumber']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>

<tr>
  <?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td colspan="3"> 
<?php  }else{?>
<td colspan="2"> 

<?php  }?> 
 <h5 class="box-title">Candidate Address</h5></td>
</tr>
<?php 
if(isset($candidate_details1['prasent_address']))
{
if($candidate_details['prasent_address'] !=  $candidate_details1['prasent_address'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        } 
}
else
{
  $style = '';
}    
?>

<tr <?php echo $style; ?>>
<td> <label>Street Address</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>

<td>  
  <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control" <?php echo $style; ?>><?php echo set_value('prasent_address',$candidate_details1['prasent_address']); ?></textarea>
</td>

<?php  }?>
<td> 
  <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control" <?php echo $style; ?>><?php echo set_value('prasent_address',$candidate_details['prasent_address']); ?></textarea>
</td>

</tr>
<?php
if(isset($candidate_details1['cands_city']))
{ 
if($candidate_details['cands_city'] !=  $candidate_details1['cands_city'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }     
}
else
{
  $style = '';
}    
?>


<tr <?php echo $style; ?>>
<td> <label>City</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
  <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city',$candidate_details1['cands_city']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
  <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city',$candidate_details['cands_city']); ?>" class="form-control" <?php echo $style; ?>>
</td>
</tr>
<?php 
if(isset($candidate_details1['cands_pincode']))
{
if($candidate_details['cands_pincode'] !=  $candidate_details1['cands_pincode'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }     
 }
else
{
  $style = '';
}    
?>


<tr <?php echo $style; ?>>
<td> <label>PIN Code</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
  <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode',$candidate_details1['cands_pincode']); ?>" class="form-control" <?php echo $style; ?>>
</td>
<?php  }?>
<td> 
  <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode',$candidate_details['cands_pincode']); ?>" class="form-control" <?php echo $style; ?>>
</td>

</tr>
<?php
if(isset($candidate_details1['cands_state']))
{ 
if($candidate_details['cands_state'] !=  $candidate_details1['cands_state'])
        {
          
          $style = 'style="background-color:#f58d0c"';
        }
        else
        {
           $style = '';
        }    
}
else
{
  $style = '';
}    
?>
<tr <?php echo $style; ?>>
<td> <label>State</label></td>
<?php if(isset($candidate_details1) && !empty($candidate_details1)){   ?>
<td>  
 <?php
        echo form_dropdown('cands_state', $states, set_value('cands_state',$candidate_details1['cands_state']), 'class="form-control" id="cands_state"'.$style);
       
      ?>
        
</td>
<?php  }?>

<td> 
  <?php
        echo form_dropdown('cands_state', $states, set_value('cands_state',$candidate_details['cands_state']), 'class="form-control" id="cands_state"'.$style);
       
      ?>
</td>
</tr>

</tbody>
</table>

<div class="clearfix"></div>
   </div>
   
  <?php echo form_close(); ?>
</div>
<script>
function myOpenWindow(winURL, winName, winFeatures, winObj)
{
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}

$(document).ready(function(){
  $('.open_attachment').on('click',function(){
    var url = $(this).data('href');
    window.open('javascript:window.open('+url+', "_self", "");window.close();', '_self');
    //window.open(url, "_blank", "toolbar=yes,width=900,height=650");
  });
  

  $("#frm_update_candidates :input").prop("disabled", true);

  $("#caserecddate").datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    daysOfWeekDisabled: [0,6],
    endDate: new Date()
  }).on('changeDate', function (selected) {
      var minDate = $(this).val();
      $('#overallclosuredate').datepicker('setStartDate', minDate);
  });

  $('#overallclosuredate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    endDate: new Date()
  });

  $('#frm_update_candidates').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      caserecddate : {
        required : true,
        validDateFormat : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : true
      },
      gender : {
        required : true,
        greaterThan: 0
      },
      cands_email_id : {
        email : true
      },

      cands_city : {
        lettersonly : true
      },
      cands_pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      NameofCandidateFather : {
        lettersonly : true
      },
      MothersName : {
        lettersonly : true
      },
      CandidatesContactNumber : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo2 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      AadharNumber : {
        digits: true,
        minlength : 12,
        maxlength : 12,
      },
      overallstatus : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
        clientid : {
          required : "Enter Client Name"
        },
        caserecddate : {
          required : "Select Case Received Date"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
         cands_email_id : {
        email : "Enter Valid Email ID"
      }, 
        ClientRefNumber : {
          required : "Enter Client Ref Number"
        },
        DateofBirth : {
          required : "Select Date of Birth"
        },
        gender : {
          required : "Select Gender"
        }
      },
      submitHandler: function(form) 
      {      
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'candidates/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update').attr('disabled','disabled');
              jQuery('.body_loading').show();
            },
            complete:function(){
              $("#frm_update_candidates :input").prop("disabled", true);
              jQuery('.body_loading').hide();
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });    
      }
  });
  
  $('#clientid').on('change',function(){
    var clientid = $(this).val();
    if(clientid != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/cmp_ref_no'; ?>',
            data:'clientid='+clientid,
            beforeSend :function(){
              jQuery('#entity').find("option:eq(0)").html("Please wait..");
            },
            success:function(jdata) {
              if(jdata.status = 200)
              {
                $('#entity').empty();
                $.each(jdata.entity_list, function(key, value) {
                  $('#entity').append($("<option></option>").attr("value",key).text(value));
                });
                
                $('#entity option[value='+$('#selected_entity').val()+']').attr("selected", "selected");
              }
            }
        });
    }
  }).trigger('change');

  $('#entity').on('change',function(){
    var entity = $(this).val() || $('#selected_entity').val();
    var selected_paclage = $('#selected_package').val();
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_paclage='+selected_paclage,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package').html(html);
            }
        });
    }
  }).trigger('change');


  $(document).on('change', '#clientid', function(){
  var clientid = $(this).val();

  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity').html(html);
          }
      });
  }
}); 

});
</script>
