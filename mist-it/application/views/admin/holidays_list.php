<<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Admin - Holiday - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>holidays">Holiday</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">

                     <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_admin_holiday_add']) ? ADMIN_SITE_URL.'holidays/add/' : ''?>"><i class="fa fa-plus"></i> Add Holiday</button></li>
                      <li><button type="button" class="btn btn-secondary waves-effect btn-sm" data-toggle = "modal" data-target="#myModal1" ><i class="fa fa-upload"></i> Export</button></li> 

                   </ol>
                  </div>
            </div>
          </div>
       </div>

      <div class="row">
        <div class="col-12">
           <div class="card m-b-20">
            <div class="card-body">
             <div class="row"> 
            <div class="col-sm-2 form-group">

            <?php
          $month= array(''=> 'Select Month','01'=> 'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');

             echo form_dropdown('filter_by_month', $month, set_value('filter_by_month'), 'class="form-control select2" id="filter_by_month"');?>
          </div>
          <div class="col-sm-2 form-group">
            <?php
          $year= array(''=> 'Select Year','2021'=> '2021','2020'=> '2020','2019'=>'2019','2018'=>'2018','2017'=>'2017','2016'=>'2016','2015'=>'2015','2014'=>'2014','2013'=>'2013','2012'=>'2012','2011'=>'2011','2010'=>'2010','2009'=>'2009','2008'=>'2008','2007'=>'2007','2006'=>'2006','2005'=>'2005','2004'=>'2004','2003'=>'2003','2002'=>'2002','2001'=>'2001','2000'=>'2000');

             echo form_dropdown('filter_by_year',$year, set_value('filter_by_year'), 'class="form-control select2" id="filter_by_year"');?>
          </div>

        <div class="col-sm-2 form-group">
          <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
          <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
        </div>
      </div>
          
            
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr >
                    <th>#</th>
                    <th>Date</th>
                    <th>Remarks</th>
                    <th>Created By</th>
                    <th>Created On</th>
                    <th>Delete</th>
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

<div id="myModal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_holiday_frm','id'=>'export_to_excel_holiday_frm')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Export Holidays</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label> From </label>
            <input type="text" name="start_from" id="start_from" value="" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label> To </label>
             <input type="text" name="end_to" id="end_to" value="" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export_holiday" name="export_holiday" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
  $('#export_to_excel_holiday_frm').validate({ 
        rules: {
          start_from : {
            required : true
          },
          end_to : {
            required : true
          }
        },
        messages: {
          start_from : {
            required : "Select Start Date"
          },
          end_to : {
            required : "Select End Date"
          }
        },
        submitHandler: function(form) 
        {      
            var start_from = $('#start_from').val();
            var end_to = $('#end_to').val();
            var dataString = 'start_from=' + start_from + '&end_to='+ end_to;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'holidays/export_to_excel_holiday'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export_holiday').text('exporting..');
              $('#export_holiday').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_holiday').text('Export');
             // $('#export_holiday').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModal1").modal('hide');
              $('#export_to_excel_holiday_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xls");
            $a[0].click();
            $a.remove();
        });     
        }
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
