<!--<div class="row">
    <div class="col-sm-4 form-group">
       
          <?php echo $details['CandidatesContactNumber']; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   
          <a href="tel://<?php echo $details['CandidatesContactNumber'] ?>"><i class="fas fa-phone fa-text-height fa-3x" aria-hidden="true"></i></a>
    </div>
</div> 
<div class="row">   
    <div class="col-sm-4 form-group">
      
         <?php echo $details['ContactNo1']; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
         
         <a href="tel://<?php echo $details['ContactNo1'] ?>"><i class="fas fa-phone fa-text-height fa-3x" aria-hidden="true"></i></a>
    </div>
</div> 
<div class="row">    
     <div class="col-sm-4 form-group">
      
          <?php echo $details['ContactNo2']; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

         <a href="tel://<?php echo $details['ContactNo2'] ?>"><i class="fas fa-phone fa-text-height fa-3x" aria-hidden="true"></i></a>
    </div>
</div>-->

<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

</style>

<table>
   <?php if(!empty( $details['CandidatesContactNumber'])) { ?>
  <tr>

    <td><?php echo $details['CandidatesContactNumber']; ?></td>
    <td><a href="tel://<?php echo $details['CandidatesContactNumber'] ?>"><i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true"></i></a></td>
   
  </tr>
  <?php } ?>
  <?php if(!empty( $details['ContactNo1'])) { ?>
  <tr>
    <td> <?php echo $details['ContactNo1']; ?></td>
    <td>  <a href="tel://<?php echo $details['ContactNo1'] ?>"><i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true"></i></a></td>
 
  </tr>
  <?php } ?>
  <?php if(!empty( $details['ContactNo2'])) { ?>
  <tr>
    <td> <?php echo $details['ContactNo2']; ?></td>
    <td>  <a href="tel://<?php echo $details['ContactNo2'] ?>"><i class="fas fa-phone fa-text-height fa-2x" aria-hidden="true"></i></a></td>
 
  </tr>
   <?php } ?>
</table>