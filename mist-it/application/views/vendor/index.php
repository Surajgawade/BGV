<div class="content-page">
  <div class="content">
    <div class="container-fluid"> 

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
          </div>
        </div>
      </div>
<div class="row">
  <div class="col-12">
    <div class="card m-b-20">
      <div class="card-body">      

      <div class="row">
          <div class="col-sm-4 form-group">
            <?php
              $month = array('0' => 'Select Month','01' => 'January','02' => 'Feb','03' => 'March','04' => 'April','05' => 'May','06' => 'June','07' => 'July','08' => 'August','09' => 'September','10' => 'Oct','11' => 'Nov','12' => 'Dec');
              echo form_dropdown('month',$month, set_value('month',date('m')),'class="select2" id="month"');
            ?>
          </div>
          <div class="col-sm-4 form-group">
            <?php
             $year  = array('0' => 'Select Year','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022');
             echo form_dropdown('year', $year, set_value('year',date('Y')), 'class="select2" id="year"');
              ?>
          </div> 

          <div class="col-sm-4 form-group">
                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
          </div>
      </div>   
         

      <?php
        
        $rename_status = array('addrver'=>'Address', 'empver'=>'Employment' , 'eduver'=>'Education', 'refver'=>'Reference' ,'courtver'=>'Court' ,'globdbver'=>'Global' ,'narcver'=>'Drugs' ,'crimver'=>'PCC' ,'identity'=>'Identity' ,'cbrver'=>'Credit Report');

        foreach($this->vendor_nemus as $key => $value) {

          if($value['component_key'] == 'addrver' || $value['component_key'] == 'empver'||  $value['component_key'] == 'eduver'||  $value['component_key'] == 'courtver'||  $value['component_key'] == 'globdbver'||  $value['component_key'] == 'narcver'|| $value['component_key'] == 'crimver'|| $value['component_key'] == 'identity' || $value['component_key'] == 'cbrver')
            {
              
                if(array_key_exists($value['component_key'], $rename_status))
                {
                  $component_name = $rename_status[$value['component_key']];
                }

                echo '<h5>'.$component_name.'</h5>';
                         

            ?>
         

        <div class="row">
          <div class="col-xl-4 col-md-4">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="mdi mdi-cube-outline float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">WIP</h6>
                  <h4 class="mb-4"><div class="numbers" id="wip_<?php echo $value['component_key'] ?>">0</div></h4>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-xl-4 col-md-4">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="mdi mdi-buffer float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">Insufficiency</h6>
                  <h4 class="mb-4"> <div class="numbers" id="insufficiency_<?php echo $value['component_key'] ?>">0</div></h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-md-4">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="mdi mdi-tag-text-outline float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">Closed</h6>
                  <h4 class="mb-4"> <div class="numbers" id="closed_<?php echo $value['component_key'] ?>">0</div></h4>
                </div>
              </div>
            </div>
          </div>

        <!--  <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="mdi mdi-briefcase-check float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">Total</h6>
                  <h4 class="mb-4"><div class="numbers" id="total_<?php echo $value['component_key'] ?>">0</div></h4>
                </div>
              </div>
            </div>
          </div>-->

        </div>

    <?php
       }

    }?>
     </div>
  </div>
</div>
</div>

       
     
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
 
   var month = $('#month').val();
   var year =  $('#year').val();


  $.ajax({
    type:'POST',
    url: '<?php echo VENDOR_SITE_URL."users/status_count"; ?>',
    data : 'month='+month+'&year='+year,      
    dataType: 'json',
    beforeSend :function(){
    $('#wip_addrver,#insufficiency_addrver,#closed_addrver,#total_addrver,#wip_empver,#insufficiency_empver,#closed_empver,#total_empver,#wip_eduver,#insufficiency_eduver,#closed_eduver,#total_eduver,#wip_courtver, #insufficiency_courtver, #closed_courtver,#total_courtver,#wip_globdbver,#insufficiency_globdbver,#closed_globdbver,#total_globdbver,#wip_crimver,#insufficiency_crimver,#closed_crimver,#total_crimver,#wip_identity,#insufficiency_identity,#closed_identity,#total_identity,#wip_cbrver,#insufficiency_cbrver,#closed_cbrver,#total_cbrver,#wip_narcver,#insufficiency_narcver,#closed_narcver,#total_narcver').html("0");
    },
    complete:function(){
    //$('#wip,#Insufficiency,#discrepancy,#stop-check,#unable-to,#completed,#total').html('0');
    },
    success:function(responce)
    {

      if(responce.status == <?php echo SUCCESS_CODE; ?>)
      {   
          var type = responce.message;
          
          if(typeof(type.address) != "undefined")
          { 
          $("#wip_addrver").html(type.address.address_wip);
          $("#insufficiency_addrver").html(type.address.address_insufficiency);
          $("#closed_addrver").html(type.address.address_closed);
          //$("#total_addrver").html(type.address.total);
          }
          if(typeof(type.employment) != "undefined")
          { 
          $("#wip_empver").html(type.employment.employment_wip);
          $("#insufficiency_empver").html(type.employment.employment_insufficiency);
          $("#closed_empver").html(type.employment.employment_closed);
         // $("#total_empver").html(type.employment.total); 
          }
          if(typeof(type.education) != "undefined")
          { 
          $("#wip_eduver").html(type.education.education_wip);
          $("#insufficiency_eduver").html(type.education.education_insufficiency);
          $("#closed_eduver").html(type.education.education_closed);
         // $("#total_eduver").html(type.education.total); 
          } 
          if(typeof(type.court) != "undefined")
          {
          $("#wip_courtver").html(type.court.court_wip);
          $("#insufficiency_courtver").html(type.court.court_insufficiency);
          $("#closed_courtver").html(type.court.court_closed);
         // $("#total_courtver").html(type.court.total);
          }
          if(typeof(type.global) != "undefined")
          {
          $("#wip_globdbver").html(type.global.global_database_wip);
          $("#insufficiency_globdbver").html(type.global.global_database_insufficiency);
          $("#closed_globdbver").html(type.global.global_database_closed);
         // $("#total_globdbver").html(type.global.total); 
          }
          if(typeof(type.pcc) != "undefined")
          {
          $("#wip_crimver").html(type.pcc.pcc_wip);
          $("#insufficiency_crimver").html(type.pcc.pcc_insufficiency);
          $("#closed_crimver").html(type.pcc.pcc_closed);
        //  $("#total_crimver").html(type.pcc.total);
          }
          if(typeof(type.identity) != "undefined")
          { 
          $("#wip_identity").html(type.identity.identity_wip);
          $("#insufficiency_identity").html(type.identity.identity_insufficiency);
          $("#closed_identity").html(type.identity.identity_closed);
        //  $("#total_identity").html(type.identity.total);
          }
          if(typeof(type.credit_report) != "undefined")
          {
          $("#wip_cbrver").html(type.credit_report.credit_report_wip);
          $("#insufficiency_cbrver").html(type.credit_report.credit_report_insufficiency);
          $("#closed_cbrver").html(type.credit_report.credit_report_closed);
        //  $("#total_cbrver").html(type.credit_report.total); 
          }
          if(typeof(type.drugs) != "undefined")
          {     
          $("#wip_narcver").html(type.drugs.drugs_wip);
          $("#insufficiency_narcver").html(type.drugs.drugs_insufficiency);
          $("#closed_narcver").html(type.drugs.drugs_closed);
       //   $("#total_narcver").html(type.drugs.total); 
          } 
          return true;
      }
      else
      {
        $('#wip_addrver,#insufficiency_addrver,#closed_addrver,#wip_empver,#insufficiency_empver,#closed_empver,#wip_eduver,#insufficiency_eduver,#closed_eduver,#wip_courtver, #insufficiency_courtver, #closed_courtver,#wip_globdbver,#insufficiency_globdbver,#closed_globdbver,#wip_crimver,#insufficiency_crimver,#closed_crimver,#wip_identity,#insufficiency_identity,#closed_identity,#wip_cbrver,#insufficiency_cbrver,#closed_cbrver,#wip_narcver,#insufficiency_narcver,#closed_narcver').html("0");
        return false;
      }
    }
  });


});

$('#searchrecords').on('click', function() {
  
  var month = $('#month').val();
   var year =  $('#year').val();


  $.ajax({
    type:'POST',
    url: '<?php echo VENDOR_SITE_URL."users/status_count"; ?>',
    data : 'month='+month+'&year='+year,      
    dataType: 'json',
    beforeSend :function(){
    $('#wip_addrver,#insufficiency_addrver,#closed_addrver,#total_addrver,#wip_empver,#insufficiency_empver,#closed_empver,#total_empver,#wip_eduver,#insufficiency_eduver,#closed_eduver,#total_eduver,#wip_courtver, #insufficiency_courtver, #closed_courtver,#total_courtver,#wip_globdbver,#insufficiency_globdbver,#closed_globdbver,#total_globdbver,#wip_crimver,#insufficiency_crimver,#closed_crimver,#total_crimver,#wip_identity,#insufficiency_identity,#closed_identity,#total_identity,#wip_cbrver,#insufficiency_cbrver,#closed_cbrver,#total_cbrver,#wip_narcver,#insufficiency_narcver,#closed_narcver,#total_narcver').html("0");
    },
    complete:function(){
    //$('#wip,#Insufficiency,#discrepancy,#stop-check,#unable-to,#completed,#total').html('0');
    },
    success:function(responce)
    {

      if(responce.status == <?php echo SUCCESS_CODE; ?>)
      {   
          var type = responce.message;
          
          if(typeof(type.address) != "undefined")
          { 
          $("#wip_addrver").html(type.address.address_wip);
          $("#insufficiency_addrver").html(type.address.address_insufficiency);
          $("#closed_addrver").html(type.address.address_closed);
          //$("#total_addrver").html(type.address.total);
          }
          if(typeof(type.employment) != "undefined")
          { 
          $("#wip_empver").html(type.employment.employment_wip);
          $("#insufficiency_empver").html(type.employment.employment_insufficiency);
          $("#closed_empver").html(type.employment.employment_closed);
         // $("#total_empver").html(type.employment.total); 
          }
          if(typeof(type.education) != "undefined")
          { 
          $("#wip_eduver").html(type.education.education_wip);
          $("#insufficiency_eduver").html(type.education.education_insufficiency);
          $("#closed_eduver").html(type.education.education_closed);
         // $("#total_eduver").html(type.education.total); 
          } 
          if(typeof(type.court) != "undefined")
          {
          $("#wip_courtver").html(type.court.court_wip);
          $("#insufficiency_courtver").html(type.court.court_insufficiency);
          $("#closed_courtver").html(type.court.court_closed);
         // $("#total_courtver").html(type.court.total);
          }
          if(typeof(type.global) != "undefined")
          {
          $("#wip_globdbver").html(type.global.global_database_wip);
          $("#insufficiency_globdbver").html(type.global.global_database_insufficiency);
          $("#closed_globdbver").html(type.global.global_database_closed);
         // $("#total_globdbver").html(type.global.total); 
          }
          if(typeof(type.pcc) != "undefined")
          {
          $("#wip_crimver").html(type.pcc.pcc_wip);
          $("#insufficiency_crimver").html(type.pcc.pcc_insufficiency);
          $("#closed_crimver").html(type.pcc.pcc_closed);
        //  $("#total_crimver").html(type.pcc.total);
          }
          if(typeof(type.identity) != "undefined")
          { 
          $("#wip_identity").html(type.identity.identity_wip);
          $("#insufficiency_identity").html(type.identity.identity_insufficiency);
          $("#closed_identity").html(type.identity.identity_closed);
        //  $("#total_identity").html(type.identity.total);
          }
          if(typeof(type.credit_report) != "undefined")
          {
          $("#wip_cbrver").html(type.credit_report.credit_report_wip);
          $("#insufficiency_cbrver").html(type.credit_report.credit_report_insufficiency);
          $("#closed_cbrver").html(type.credit_report.credit_report_closed);
        //  $("#total_cbrver").html(type.credit_report.total); 
          }
          if(typeof(type.drugs) != "undefined")
          {     
          $("#wip_narcver").html(type.drugs.drugs_wip);
          $("#insufficiency_narcver").html(type.drugs.drugs_insufficiency);
          $("#closed_narcver").html(type.drugs.drugs_closed);
       //   $("#total_narcver").html(type.drugs.total); 
          } 
          return true;
      }
      else
      {
        $('#wip_addrver,#insufficiency_addrver,#closed_addrver,#wip_empver,#insufficiency_empver,#closed_empver,#wip_eduver,#insufficiency_eduver,#closed_eduver,#wip_courtver, #insufficiency_courtver, #closed_courtver,#wip_globdbver,#insufficiency_globdbver,#closed_globdbver,#wip_crimver,#insufficiency_crimver,#closed_crimver,#wip_identity,#insufficiency_identity,#closed_identity,#wip_cbrver,#insufficiency_cbrver,#closed_cbrver,#wip_narcver,#insufficiency_narcver,#closed_narcver').html("0");
        return false;
      }
    }
  });

});
</script>

