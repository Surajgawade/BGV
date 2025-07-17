<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
 
                 <div class=clearfix></div>

              <div class="filterable">
                <div class="card-body">
                  <div class="table-responsive" style="padding: 10px;">
                <table id="tbl_datatable1" class="table table-bordered table-hover">
                  <thead>
                    <tr class="filters">
                      <th><input type="text" class="form-control" placeholder="Sr No" ></th>
                      <th><input type="text" class="form-control" placeholder="Client Ref No" ></th>
                      <th><input type="text" class="form-control" placeholder="Comp Ref No" ></th>
                      <th><input type="text" class="form-control" placeholder="Comp INT" ></th>
                      <th><input type="text" class="form-control" placeholder="Client Name" ></th>
                      <th><input type="text" class="form-control" placeholder="Candidate's Name" ></th>
                      <th><input type="text" class="form-control" placeholder="Company Name" ></th>
                      <th><input type="text" class="form-control" placeholder="Status" ></th>
                      <th><input type="text" class="form-control" placeholder="Field status" ></th>
                      <!--<th><input type="text" class="form-control" placeholder="QC status" ></th>-->
                      <th><input type="text" class="form-control" placeholder="TAT status" ></th>
                      <th><input type="text" class="form-control" placeholder="Due Date" ></th>
                      <th><input type="text" class="form-control" placeholder="Last Activity" ></th>
                      <th><input type="text" class="form-control" placeholder="Mode Of Verification" ></th>
                      <th><input type="text" class="form-control" placeholder="<?php echo REFNO; ?>" ></th>

                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
</div>


<div id="addAddEmploymentModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'employment_add','id'=>'employment_add')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Employment</h4>
      </div>
      <span class="errorTxt"></span>
    
      <div id="append_add_employment_model"></div>
      <div class="modal-footer">
        <button type="button" id="addemploymentBack" name="addemploymentBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbemployment" name="sbemployment" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>

$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '<?php echo CANDIDATE_SITE_URL.'employment/employment_view'; ?>/',
        data: {'status': status},  
        method: 'POST' // Ajax HTTP method
    }, opts );
 
   // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
 
    return function ( request, drawCallback, settings ) {      
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {                
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }
                     
                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
};

$(document).ready(function() {
   var oTable =  $('#tbl_datatable1').DataTable( {
        "processing": true,
        "serverSide": true,
         bSortable: true,
         bRetrieve: true,
         scrollX: true,
         scrollCollapse: true,
         fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
        "iDisplayLength": 10, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo CANDIDATE_SITE_URL.'employment/employment_view'; ?>",
            pages: 5, // number of pages to cache
            async: true,
            method: 'POST', 
            data: { 'status':function(){return $("#status_candidate").val(); } }
        } ),
         order: [[ 1, 'desc' ]],
        'columnDefs': [
           {
              'targets': 0,
              'checkboxes': {
                 'selectRow': true
              }
           }
        ],
        'select': {
           'style': 'multi'
        },
        "columns":[{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"emp_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'coname'},{'data' :'verfstatus'},{"data":"field_status"},{'data':'tat_status'},{'data':'due_date'},{'data':"last_activity_date"},{'data':"mode_of_veri"},{'data':"cmp_ref_no"}],
    } );
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    $('#status_candidate').val('All');
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });

  $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();
    var url = data['encry_id'];
    $('#append_add_employment_model').load(url,function(){
      $('#addAddEmploymentModel').modal('show');
    });
  });
}); 

 $('#employment_add').validate({ 
           rules: {
      nameofthecompany : {
        required : function(){
       
          if($("input[name ='employment_check']:checked").val() == '1') 
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      reasonforleaving : {
        required : function(){
         
          if ($("input[name ='employment_check']:checked").val() == '1') 
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      }
    },
    messages: {
      nameofthecompany : {
        required : function(){
         
          if ($("input[name ='employment_check']:checked").val() == '1') 
          {
            return false;
          }
          else
          {
            return "Enter Company Name";
          }
        }
        
      },
      reasonforleaving : {

        required : function(){
         
          if ($("input[name ='employment_check']:checked").val() == '1') 
          {
            return false;
          }
          else
          {
            return "Enter reason for leaving";
          }
        } 
      }
    },  
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CANDIDATE_SITE_URL.'employment/save_employment'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbemployment').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
        //  $('#btn_address').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){

             $.ajax({
                url : "<?php echo CANDIDATE_SITE_URL.'Dashboard/candidate_submit_component'; ?>",
                data : $('#employment_add').serialize(),
                type: 'post',
                async:false,
                cache: false,
                processData:false,
                dataType:'json',
                beforeSend:function(){
                //  $('#vendor_result_submit').attr('disabled','disabled');
                },
                complete:function(){
                //  $('#vendor_result_submit').removeAttr('disabled');                
                },
                success: function(jdata){

                     var message =  jdata.message || '';
                     // (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                       if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                         show_alert(message,'success');
                         location.reload();
                       }
                        if(jdata.status == '<?php echo ERROR_CODE; ?>'){
                        show_alert(message,'error');
                       // location.reload(); 
                      }
                 }
            }); 
           // show_alert(message,'success');
           // location.reload();
            //window.location = jdata.redirect;
           // return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
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

