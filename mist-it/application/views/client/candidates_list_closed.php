
<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
               <!-- <form>
                   <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                         <?php echo form_dropdown('filter_by_status', $status, set_value('filter_by_status'), 'class="form-control" id="filter_by_status"');?>
                  </div>
                 </form>-->
                 <div class=clearfix></div>
                  <div class="card-body">
                  <div class="table-responsive" style="padding: 10px;">
                      <table id="tbl_datatable1" class="table table-striped table-bordered nowrap">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Candidate Name</th>
                            <th>Case Initiated</th>
                            <th>Client Ref No</th>
                            <th><?php echo REFNO; ?></th>
                            <th>Overall Status</th>
                            <th>Closure Date</th>
                            <th>Remarks</th>
                            <th>View</th>
                            <th>Report</th>
                            <th>Pending </th>
                            <th>Insufficiency</th>
                            <th>Closed </th>
                           
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" >
  <div class="modal-dialog" style="width:1000px;">
    <div class="modal-content">
      <div class="append-data">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
//var status = "<?php echo $this->uri->segment(2); ?>";

$('input[type="search"]').val(status);

$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '<?php echo CLIENT_SITE_URL.'candidates_closed/data_table_cands_view'; ?>/',
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

        "iDisplayLength": 15, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo CLIENT_SITE_URL.'candidates_closed/data_table_cands_view'; ?>",
            pages: 5, // number of pages to cache
            async: true 
        } ),
        "columns":[{"data":"id"},{"data":"clientname"},{"data":"CandidateName"},{"data":"caserecddate"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"},{"data":"overallstatus"},{"data":"overallclosuredate"},{"data":"remarks"},{"data":"view"},{"data":"encry_id"},{'data':'pending_check'},{'data':'insufficiency_check'},{'data':'closed_check'}]
    } );
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });
  });



  $(document).on('click', '.view_details', function(){
    var id = "<?php echo CLIENT_SITE_URL.'candidates_closed/view_details/' ?>"+$(this).data("raw_id");

    $('.append-data').load(id,function(){
        $('#myModal').modal('show');
  });
});

$(document).on('click', '.status_alert', function(){

  var status = $(this).data('overallstatus');
  url = $(this).data('href');
  
  var win= window.open(url, '_blank');
 /* if(status === "WIP")
  {
    var result_ = confirm("The case is not completed, do you want to see the report.");

    if(result_ === true)
    {
      url = $(this).data('href');
      var win= window.open(url, '_blank');
    }
    else
    {
      return true;
    }
  }
  else
  {
    url = $(this).data('href');
    var win= window.open(url, '_blank');
  }*/
    
  

});
</script>