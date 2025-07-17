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
          <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="fas fa-hourglass-start float-right"></i>
                </div>
                <div class="text-white"> 
                  <h6 class="text-uppercase mb-3">WIP</h6>
                  <h4 class="mb-4"><div class="numbers" id="wip">0</div></h4>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="fas fa-ban float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">Insufficiency</h6>
                  <h4 class="mb-4"><div class="numbers" id="insufficiency">0</div></h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="fas fa-user-check float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">Closed</h6>
                  <h4 class="mb-4"><div class="numbers" id="closed">0</div></h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary">
              <div class="card-body mini-stat-img">
                <div class="mini-stat-icon">
                  <i class="mdi mdi-briefcase-check float-right"></i>
                </div>
                <div class="text-white">
                  <h6 class="text-uppercase mb-3">Total</h6>
                  <h4 class="mb-4"><div class="numbers" id="total">0</div></h4>
                </div>
              </div>
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
    url: '<?php echo CLIENT_SITE_URL."candidates/status_count"; ?>',      
    dataType: 'json',
    beforeSend :function(){
    $('#wip,#insufficiency,#closed,#total').html("0");
    },
    complete:function(){
    //$('#wip,#Insufficiency,#discrepancy,#stop-check,#unable-to,#completed,#total').html('0');
    },
    success:function(responce)
    {

      if(responce.status == <?php echo SUCCESS_CODE; ?>)
      {   
          var type = responce.message;
         
          $("#wip").html(type.WIP);
          $("#insufficiency").html(type.Insufficiency);
          $("#closed").html(type.Closed);
          $("#total").html(type.total); 
          return true;
      }
      else
      {
        $('#wip,#insufficiency,#closed,#total').html("0");
        return false;
      }
    }
  });
});

</script>