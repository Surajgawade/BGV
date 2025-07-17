<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-warning text-center">
                                    <i class="ti-server"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" id="wip">
                                    <p>WIP</p>0
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <a href="<?= CLIENT_SITE_URL.'candidates_wip'?>">View Details </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-success text-center">
                                    <i class="ti-wallet"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" id="insufficiency">
                                    <p >Insufficiency</p>0
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                              <a href="<?= CLIENT_SITE_URL.'candidates_insufficiency'?>">View Details </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-danger text-center">
                                    <i class="ti-pulse"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" id="closed">
                                    <p>Closed</p>0
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                              <a href="<?= CLIENT_SITE_URL.'candidates_closed'?>">View Details </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="icon-big icon-info text-center">
                                    <i class="ti-user"></i>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="numbers" id="Total cases">
                                    <p>Total Cases</p>0
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <a href="<?= CANDIDATE_SITE_URL.'candidates'; ?>">View Details </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Sales Records</h4>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-striped">
                            <thead>
                                <th>#</th>
                                <th>Lot No</th>
                                <th>Data Uploaded On</th>
                                <th>Sale Count</th>
                                <th>Total Premium Count</th>
                            </thead>
                            <tbody id="append-data"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

    $.ajax({
        type:'POST',
        url: '<?php echo SITE_URL."disposition/disposistion_count"; ?>',      
        dataType: 'json',
        beforeSend :function(){
            $("#total_records").html('<p>Total Records</p>0');
            $("#disposition_added").html('<p >Contact</p>0');
            $("#remaining_record").html('<p>Not Contact</p>0');
            $("#agents").html('<p>Agents</p>0');
            $("#append-data").html('<tr><td colspan="5" class="text-center">No Records Found</td></tr>');
        },
        complete:function(){
        //$('#wip,#Insufficiency,#discrepancy,#stop-check,#unable-to,#completed,#total').html('0');
        },
        success:function(responce)
        {
          if(responce.status == <?php echo SUCCESS_CODE; ?>)
          {   
            $("#total_records").html('<p>Total Records</p>'+responce.total_records);
            $("#disposition_added").html('<p >Contact</p>'+responce.total_desposition);
            $("#remaining_record").html('<p>Not Contact</p>'+responce.remaining_record);
            $("#agents").html('<p>Agents</p>'+responce.agents_counts); 
            $("#append-data").html(responce.sales_records); 
            return true;
          }
          else
          {
            $("#total_records").html('<p>Total Records</p>0');
            $("#disposition_added").html('<p >Contact</p>0');
            $("#remaining_record").html('<p>Not Contact</p>0');
            $("#agents").html('<p>Agents</p>0');
            $("#append-data").html('<tr><td colspan="5" class="text-center">No Records Found</td></tr>');
            return false;
          }
        }
    });
});
</script>