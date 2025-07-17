<div class="content-wrapper">
    <section class="content-header">
      <?php if(isset($client_info))
         {
         
            echo '<table  style="width:100%" border="1">
             <tr><td>Client Name</td><td>'.$client_info[0]['client_name'].'</td></tr>
             <tr><td>Entity Name</td><td>'.$client_info[0]['entity_name'].'</td></tr>  
             <tr><td>Package Name</td><td>'.$client_info[0]['package_name'].'</td></tr>
             <tr><td>Client Co-ordinator</td><td>'.$client_info[0]['package_name'].'</td></tr>
             <tr><td>Interim Report</td><td>'.$client_info[0]['package_name'].'</td></tr>
             <tr><td>Final Report</td><td>'.$client_info[0]['package_name'].'</td></tr>
            </table>';

       } 
     
        ?>

    <?php 

       $mode_of_verification_value = json_decode($client_info[0]['mode_of_verification']);
       $scope_of_work_value = json_decode($client_info[0]['scope_of_work']);

     ?>
      <table  style="width:100%" border="1">
        <tr>
          <th> Component</th>
          <th> Scope of work</th>
          <th> Mode of verification</th>
          <th> TAT (Working days)</th>
          <th> Purticular</th>
          <th> Selection</th>
          <th> Remark</th>
        </tr>
        
        
        <?php

   
        foreach ($getInfo as $getIn => $value) {?>
          
                <tr colspan="4">
                <td><?php echo $value['client_component']; ?></td>
          
                <td><?php echo $scope_of_work_value->addrver;  ?></td>
                <td><?php echo $mode_of_verification_value->addrver;  ?></td>
                <td><?php echo $client_info[0]['tat_addrver']  ?></td>
                <td><?php echo $value['question']; ?></td>
                <td><?php echo $value['selected_selection']; ?></td>
                <td><?php echo $value['remarks']; ?></td>

    <?php    }  

        ?>
       </tr> 
      </table>
     
     </section>
   </div>