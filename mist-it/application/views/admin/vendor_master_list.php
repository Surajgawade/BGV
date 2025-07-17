<div class="content-page">
<div class="content">
<div class="container-fluid">

     <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendors - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>vendor_master">Vendors</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
             </div>
          </div>
    </div>


 
 
  <div class="nav-tabs-custom">
      
    <ul class="nav nav-pills nav-justified" role="tablist">

     <?php   
        echo "<li class='nav-item waves-effect waves-light active'  role='presentation' data-url='".ADMIN_SITE_URL."address/approval_queue/' data-can_id='1' data-tab_name='Vendor_assign_reject'  class='view_component_tab'><a class = 'nav-link active'  href='#Vendor_assign_reject' aria-controls='home' data-toggle='tab'>Vendor Master</a></li>";

        echo '<li class="nav-item waves-effect waves-light"><a class = "nav-link"  href="#result_vendor_aq" id="view_vendor_aq" data-toggle="tab">Vendor AQ</a></li>';

        echo '<li class="nav-item waves-effect waves-light"><a class = "nav-link"  href="#result_vendor_charges" id="view_vendor_charges" data-toggle="tab">Vendor Charges</a></li>';
     ?>
    </ul>
  </div>  
  <div class="tab-content">
    <div id="Vendor_assign_reject" class="tab-pane active">

      <div class="row">
        <div class="col-12">
         <div class="card m-b-20">
           <div class="card-body">
            <div class="row">
              <div class="col-sm-4 form-group">
                <input type="text" name="reject_reason" maxlength="200" id="reject_reason" placeholder="Reason" class="form-control">
              </div>
              <div class="col-sm-2 form-group">
                <input type="button" name="btn_assign" id="btn_assign" class="btn btn-info btn-md" value="Submit">
              </div>
            </div>
           
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th><input name="select_all" value="1" id="example-select-all" class="example-select-all" type="checkbox" /></th>
                    <th>Sr No</th>
                    <th>Trasaction Id</th>
                    <th>Comp Ref No</th>
                 
                    <th>Client Ref No</th>
                    <th>Component</th>
                    <th>Allocated By</th>
                    <th>Allocated On</th>
                    <th>Approval By</th>
                    <th>Approval On</th>
                    <th>Vendor Name</th>
                    <th>TAT Status</th>
                    <th>Cost</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    foreach($lists as $list)
                    {
                      echo  "<tr>";
                      echo  "<td><input type='checkbox' name='cases_id[]' id='cases_id' value='".$list['component']."||".$list['case_id']."||".$list['id']."||".$list['trasaction_id']."'> </td>";
                      echo  "<td>".$counter."</td>";
                      echo  "<td>".$list['trasaction_id']."</td>";
                      echo  "<td>".$list['component_ref']."</td>";
                      //echo  "<td>".$list['cmp_ref_no']."</td>";
                      echo  "<td>".$list['ClientRefNumber']."</td>";
                      echo  "<td>".$list['component']."</td>";
                      echo  "<td>".$list['allocated_by']."</td>";
                      echo  "<td>".convert_db_to_display_date($list['allocated_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo  "<td>".$list['approval_by']."</td>";
                      echo  "<td>".convert_db_to_display_date($list['approval_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo  "<td>".$list['vendor_name']."</td>";
                      echo  "<td>".$list['tat_status']."</td>";
                      echo  "<td>".$list['costing']."</td>";
                      echo "</tr>";
                      $counter++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

       <div id="result_vendor_aq" class="tab-pane fade in" >
         <div class="row">
          <div class="col-12">
            <div class="card m-b-20">
             <div class="card-body">
              <div class="row">
              <div class="col-sm-2 form-group">

            <?php    $assigned_option_assign = array(0 =>'select', 1 =>'Approve',2 => 'Reject' );

             echo form_dropdown('cases_assgin_vendor_aq', $assigned_option_assign, set_value('cases_assgin_vendor_aq'), 'class="select2" id="cases_assgin_vendor_aq"');?>
              </div>
              <div class="col-md-2 form-group reject_reason_vendor_aq" style="display: none;">
              <input type="text" name="reject_reason_vendor_aq" maxlength="200" id="reject_reason_vendor_aq" placeholder="Reason" class="form-control">
             <?php echo form_error('reject_reason_vendor_aq_error'); ?>
              </div>
              <div class="col-md-2">
              <input type="button" name="btn_assign_vendor_aq" id="btn_assign_vendor_aq" class="btn btn-info btn-md" value="Submit">
              </div>
              </div>

               <table id="tbl_datatable_vendor_aq" class="table table-bordered table-hover" width="100%">
                
                <tbody>
               
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>

      <div id="result_vendor_charges" class="tab-pane fade in" >
         <div class="row">
          <div class="col-12">
            <div class="card m-b-20">
             <div class="card-body">
              <div class="row">
              <div class="col-sm-2 form-group">

            <?php    $assigned_option_assign = array(0 =>'select', 1 =>'Approve',2 => 'Reject' );

             echo form_dropdown('cases_assgin_vendor_charges', $assigned_option_assign, set_value('cases_assgin_vendor_charges'), 'class="select2" id="cases_assgin_vendor_charges"');?>
              </div>
              <div class="col-sm-2 form-group reject_reason_vendor_charges" style="display: none;">
              <input type="text" name="reject_reason_vendor_charges" maxlength="200" id="reject_reason_vendor_charges" placeholder="Reason" class="form-control">
             <?php echo form_error('reject_reason_vendor_charges_error'); ?>
              </div>
              <div class="col-md-2">
              <input type="button" name="btn_assign_vendor_charges" id="btn_assign_vendor_charges" class="btn btn-info btn-md" value="Submit">
             </div>
              </div> 
               

             <table id="tbl_datatable_vendor_charges" class="table table-bordered table-hover" width="100%">
                
                <tbody>
               
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>

   </div>
    
</div>
</div>
</div>
<script>
$(function ()  {
  var table = $('#tbl_datatable').DataTable({
      "iDisplayLength": 25,
      bSortable: true,
      bRetrieve: true,
      scrollX: true,
      scrollCollapse: true,
      fixedColumns:   {
      leftColumns: 1,
      rightColumns: 1
      },
      "lengthMenu": [[20, 40, 100, -1], [20, 40, 100, "All"]],
      'columnDefs': [{
          'targets': 0,
         'searchable':false,
         'orderable':false,
      }],
      'order': [1, 'asc']
  });

     $('.example-select-all').on('click', function(){
       var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });
});





var select_one = [];
$(document).on('click', '#btn_assign', function(){
    var reject_reason = $('#reject_reason').val();
    $.each($("input[name='cases_id[]']:checked"), function(){            
        select_one.push($(this).val());
    });
    if(select_one != "" && reject_reason != "")
    { 
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'vendor_master/reject_case/' ?>',
          data : 'cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign').attr('disable','disable');
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success',true);
            }else {
              show_alert(message,'error',true);
            }
          }
        });      
    } else {
      $("#cases_assgin option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case and enter remark');
    }
});


</script>

<script type="text/javascript">

   $('#cases_assgin_vendor_aq').on('change', function(){
    var cases_assgin_action = $('#cases_assgin_vendor_aq').val();

    (cases_assgin_action == 1) ? $('.reject_reason_vendor_aq').hide() : $('.reject_reason_vendor_aq').show();
  });


  $('#view_vendor_aq').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'Vendor_master/vendor_aq_all_component/' ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_activity_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          $('#tbl_datatable_vendor_aq').html(message);
        }
        else {
          $('#tbl_datatable_vendor_aq').html(message);
        }     
      var tbl_datatable_vendor_aq =  $('#tbl_datatable_vendor_aq').DataTable( { "paging": true,  "processing": true,  "searching": false, "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 10,"lengthChange": true,"lengthMenu": [[5,10,25, 50, -1], [5,10,25, 50, "All"]],"aaSorting": [] });
      }
    }); 
  })



  var select_one = [];
$(document).on('click', '#btn_assign_vendor_aq', function(){
    var cases_assgin_action = $('#cases_assgin_vendor_aq').val();

    var reject_reason = $('#reject_reason_vendor_aq').val();
    $.each($("input[name='cases_id[]']:checked"), function(){            
        select_one.push($(this).val());
    });
    if(select_one != "" && cases_assgin_action != "")
    { 

      if(cases_assgin_action == 2 && reject_reason == "")
        {
     
        show_alert('Please insert reject reason','error'); 
        
        }
       else
       {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'vendor_master/approve_reject_case/' ?>',
          data : 'action='+cases_assgin_action+'&cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign_vendor_aq').attr('disable','disable');
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success',true);
            }else {
              show_alert(message,'error',true);
            }
          }
        });
      }      
    } else {
      $("#cases_assgin_vendor_aq option[value=0]").attr('selected', 'selected');
      alert('Please Select atleast one case');
    }
});
</script>

<script type="text/javascript">

   $('#cases_assgin_vendor_charges').on('change', function(){
    var cases_assgin_action = $('#cases_assgin_vendor_charges').val();

    (cases_assgin_action == 1) ? $('.reject_reason_vendor_charges').hide() : $('.reject_reason_vendor_charges').show();
  });


  $('#view_vendor_charges').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'Vendor_master/vendor_charges_all_component/' ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_activity_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          $('#tbl_datatable_vendor_charges').html(message);
        }
        else {
          $('#tbl_datatable_vendor_charges').html(message);
        }     
      var tbl_datatable_vendor_charges =  $('#tbl_datatable_vendor_charges').DataTable( {"paging": true,  "processing": true,  "searching": false, "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 10,"lengthChange": true,"lengthMenu": [[5,10,25, 50, -1], [5,10,25, 50, "All"]],"aaSorting": [] });
      }
    }); 
  })



  var select_one = [];
$(document).on('click', '#btn_assign_vendor_charges', function(){
    var cases_assgin_action = $('#cases_assgin_vendor_charges').val();

    var reject_reason = $('#reject_reason_vendor_charges').val();
    $.each($("input[name='cases_id[]']:checked"), function(){            
        select_one.push($(this).val());
    });
    if(select_one != "" && cases_assgin_action != "")
    { 

      if(cases_assgin_action == 2 && reject_reason == "")
        {
     
        show_alert('Please insert reject reason','error'); 
        
        }
       else
       {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'vendor_master/approve_reject_case_charges/' ?>',
          data : 'action='+cases_assgin_action+'&cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign_vendor_charges').attr('disable','disable');
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success',true);
            }else {
              show_alert(message,'error',true);
            }
          }
        });
      }      
    } else {
      $("#cases_assgin_vendor_charges option[value=0]").attr('selected', 'selected');
      alert('Please Select atleast one case');
    }
});
</script>