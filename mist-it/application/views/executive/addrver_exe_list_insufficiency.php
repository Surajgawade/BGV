<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="wrapper-page">
  <div class="card">
    

      <div class="card-body">
        <a  href="<?=VENDOR_EXECUTIVE_SITE_URL?>logout?id=<?=$this->vendor_executive_info['id']?>"  style="float: right;" ><i class='fas fa-power-off fa-fw' style='font-size:36px;color:red'></i><br>Logout<a>
          <h3 class="text-center m-0">
            <a href="#" class="logo logo-admin">
              <img src="<?php echo SITE_IMAGES_URL; ?>logo.png" height="60" alt="logo">
            </a>
          </h3>

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_EXECUTIVE_SITE_URL ?>">Home</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_EXECUTIVE_SITE_URL ?>addrver_exe/addrver_exe_insufficiency">Address Insufficiency</a></li>
                     <li class="breadcrumb-item active">List View</li>
                      <a href="<?php echo VENDOR_EXECUTIVE_SITE_URL ?>"><i class="fas fa-home" aria-hidden="true" style="font-size: 30px; position: absolute;right: 20px;"></i></a> 
                  </ol>
          
            </div>
          </div>
      </div>
    
        <div class="row">
          <div class="col-12">
            <div class="card m-b-20">
              <div class="card-body">
                        
                        <div class="clearfix"></div>
                        
                       <!-- <table  id="tbl_datatable"  class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">-->
                      <table  id="tbl_datatable"   class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                               <tr>
                                <th>Received</th>
                                <th>ID</th>
                               	<th>Name</th>
                                <th>Address</th>
                              	<th>City</th>
                                <th>Client</th>
                                <th>Sub Client</th>
                                 <th>Action</th>
                                </tr> 
                            </thead>
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





<div id="showAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'address_form_view','id'=>'address_form_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Address Form</h4>
      </div>
      <div class="modal-body"> 
         <div class ="append_result_model"></div>
      </div>
    
      <div class="modal-footer">
     
        <button type="submit" id="sblogresult" name="sblogresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="addmobileModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md">

    <?php echo form_open_multipart("#", array('name'=>'address_form_mobile_view','id'=>'address_form_mobile_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Contact No</h4>
      </div>
      <div class="modal-body"> 
         <div class ="append_mobile_model"></div>
      </div>
    
      <div class="modal-footer">
    
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script type="text/javascript">

var $ = jQuery;

$(document).ready(function() {
  
  var oTable =  $('#tbl_datatable').DataTable( {
        "serverSide": true,
        "processing": true,
        bSortable: true,
        bRetrieve: true,
        scrollY:  "200px",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            right: 1
        },
        "iDisplayLength": 15, // per page
        "lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "All"]],
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/address_view_datatable_insufficiency_executive'; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 1, 'asc' ]],
       
        "columns":[{'data' :'created_on'},{"data":"ID"},{"data":"CandidateName"},{'data' :'address'},{'data' :'city'},{'data':'clientname'},{'data':'entity_name'},{'data' :'action'}]
  });

  $('div.dataTables_filter input').unbind();
  $('div.dataTables_filter input').bind('keyup', function(e) {
     
    if(e.keyCode == 13) {
      var search_value = this.value;
      var search_value_details = $.trim(search_value);
     
      oTable.search( search_value_details ).draw();
      
    }
  });

  
   $('.dataTables_filter input[type="search"]').css(
     {'width':'200px','display':'inline-block'}
   );

});



 $(document).on('click','.showAddResultModel',function() {

    var url = $(this).data('url');

    window.location = url;
  
});

$(document).on('click','.showviewcallModel',function() {

    var url = $(this).data('url');

     $('.append_mobile_model').load(url,function(){
      $('#addmobileModel').modal('show');
      $('#addmobileModel').addClass("show");
      $('#addmobileModel').css({background: "#0000004d"}); 
    });
});
</script>
