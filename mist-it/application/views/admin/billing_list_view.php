<div class="content-wrapper">
    <section class="content-header">
      <h1>Billing  - Client</h1>
      <ol class="breadcrumb">

        <li><button class="btn btn-sm btn-default" data-toggle="modal" data-target="#myModelImport"><i class="fa fa-download"></i> Import</button></li>
        <li><button type="button" class="btn btn-default btn-sm" data-toggle = "modal" data-target="#myModal1" ><i class="fa fa-upload"></i> Export</button></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
         
            <div class="filterable">
            
            <div class="box-body">
              <table id="tbl_datatable" class="table table-bordered table-hover">
                <thead>
                  <tr class="filters">
                    <th><input type="text" class="form-control" placeholder="#"></th>
                    <th><input type="text" class="form-control" placeholder="Client Ref No"></th>
                    <th><input type="text" class="form-control" placeholder="<?php echo REFNO; ?>"></th>
                    <th><input type="text" class="form-control" placeholder="Client Name"></th>
                    <th><input type="text" class="form-control" placeholder="Entity"></th>
                    <th><input type="text" class="form-control" placeholder="Package"></th>
                    <th><input type="text" class="form-control" placeholder="Case Initiated"></th> 
                    <th><input type="text" class="form-control" placeholder="Candidate Name"></th>
                    <th><input type="text" class="form-control" placeholder="Location"></th>
                    <th><input type="text" class="form-control" placeholder="Department"></th>
                    <th><input type="text" class="form-control" placeholder="Package"></th> 
                    <th><input type="text" class="form-control" placeholder="Package"></th> 
                    <th><input type="text" class="form-control" placeholder="Package"></th> 
                    <th><input type="text" class="form-control" placeholder="Package"></th>
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
    </section>
</div>


<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Billing Records</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import','id'=>'frm_download_tem_and_import')); ?>   
      <div class="modal-body">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Client <span class="error"> *</span></label>
            <?php
              echo form_dropdown('clientid',$clients, set_value('clientid'), 'class="form-control" id="clientid"');
              echo form_error('clientid');
            ?>
          </div>
          <div class="clearfix"></div>
          
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Component Type<span class="error"> *</span></label>
             <?php 
             $component_type = array('' => 'Select','address' => 'Address','employment' => 'Employment','education' => 'Education','reference' => 'Reference','court' => 'Court','global' => 'Global Database','pcc' => 'PCC','identity' => 'Identity','credit' => 'Credit Report','drugs' => 'Drugs');
              echo form_dropdown('component_type',$component_type, set_value('component_type'), 'class="form-control" id="component_type"');
              echo form_error('component_type');
              ?>
          </div>
           <div class="clearfix"></div>
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="billing_bulk_sheet" id="billing_bulk_sheet" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-info pull-left btn-md" id="download_billing_template">Template</button>
        <button type="submit" class="btn btn-info btn-md" id="import_billing_template">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
var $ = jQuery;
var select_one = '';
$(document).ready(function() {


  var oTable =  $('#tbl_datatable').DataTable( {
        "serverSide": true,
        "processing": true,
        "ordering": true,
        bSortable: true,
        bRetrieve: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
        "iDisplayLength": 25, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": jQuery('.body_loading').show()
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'holidays/holiday_view_datatable'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_year':function(){return $("#filter_by_year").val(); },'filter_by_month':function(){return $("#filter_by_month").val(); }, }
        } ),
        order: [[ 1, 'desc' ]],
        'columnDefs': [
           {
              'targets': 0,
              
           }
        ],
        'select': {
           'style': 'multi'
        },
        "columns":[{'data' :'id'},{'data' :'holiday_date'},{"data":"remark"},{"data":"created_user_name"},{"data":"created_on"},{'data' :'delete'}]
  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  
    $('#filter_by_year,#filter_by_month').on('change', function(){

    var filter_by_month = $('#filter_by_month').val();
    var filter_by_year = $('#filter_by_year').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'holidays/holiday_view_datatable'; ?>?filter_by_month="+filter_by_month+"&filter_by_year="+filter_by_year;
    oTable.ajax.url(new_url).load();
  });

  $('#searchrecords').on('click', function() {
    var filter_by_month = $('#filter_by_month').val();
    var filter_by_year = $('#filter_by_year').val();

    var new_url = "<?php echo ADMIN_SITE_URL.'holidays/holiday_view_datatable'; ?>?filter_by_month="+filter_by_month+"&filter_by_year="+filter_by_year;
    oTable.ajax.url(new_url).load();
   
  });


});

</script>
<script>
$(document).ready(function(){

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

 $(document).on('change', '#entity', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package').html(html);
            }
        });
    }
  });


  $('#frm_download_tem_and_import').validate({ 
      rules: {
        clientid : {
          required : true,
          greaterThan: 0
        },
        component_type : {
          required : true,
          greaterThan: 0
        },
        billing_bulk_sheet : {
          required : true
        }
      },
      messages: {
        clientid : {
          required : "Enter Client Name",
          greaterThan : "Select Client Name"
        },
        component_type : {
          required : "Select Component Type",
          greaterThan: "Select Component Type"
        },
        billing_bulk_sheet : {
          required : "Select file to upload",
          extension : "Please upload .xlsx file"
        } 
      },
     
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Billing/bulk_upload_billing'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#import_candidate_template').attr('disabled','disabled');
          },
          complete:function(){
           // $('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success',true);
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });

$('#download_billing_template').on('click',function(){
    

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Billing/template_download'; ?>',
          data : '',
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_billing_template').text('downloading..');
              $('#download_billing_template').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_billing_template').text('Template');
              $('#download_billing_template').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelImport").modal('hide');
              $('#frm_download_tem_and_import')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>) {

                show_alert(message,'success'); 
              } else {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xlsx");
            $a[0].click();
            $a.remove();
        });
  });
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
  
    $('.filterable .btn-filter').click(function(){

        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
      
        /* Ignore tab key */
        var code = e.keyCode || e.which;
      
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');

        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
        var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });
});
</script>
<style type="text/css">
  .filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
    size:5px;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
    text-align: center; 

}

</style>
