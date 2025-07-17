<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                        <div class="col-md-2 col-sm-12 col-xs-2 form-group reject_reason" style="display: none;">
                        <input type="text" name="reject_reason" maxlength="200" id="reject_reason" placeholder="Reason" class="form-control">
                        </div>
                        <input type="button" name="btn_cccept" id="btn_cccept" class="btn btn-info btn-sm" value="Accept">
                        <input type="button" name="btn_decline" id="btn_decline" class="btn btn-warning btn-sm" value="Decline"><br>
                        <div class="clearfix"></div>
                        <table class="table table-hover dataTable no-footer" id="tbl_datatable">
                            <thead>
                                <th><input name="select_all" value="1" id="chk_datatable" class="chk_datatable" type="checkbox" /></th>
                                <th>Action</th>
                                <th>Check Rec Dt</th>
                            	<th>Trans Id</th>
                                <th>Component ID</th>
                            	<th>Candidate Name</th>
                            	<th>Address</th>
                            	<th>City</th>
                                <th>Pincode</th>
                                <th>State</th>
                                <th>Executive</th>
                                <th>TAT</th>
                                <th>Due Date</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($lists as $key => $value) {

                                echo "<tr>";
                                echo "<td><input type='checkbox' name='cases_id[]' id='cases_id' value='".$value['vendor_master_log_id']."' class='select-checkbox'></td>";
                                echo "<td><input type='button' id='".$value['vendor_master_log_id']."' class='btn btn-warning btn-sm' value='Action'></td>";
                                echo "<td>".convert_db_to_display_date($value['allocated_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                                echo "<td>".$value['trasaction_id']."</td>";
                                echo "<td>".$value['component_ref']."</td>";
                                echo "<td>".$value['CandidateName']."</td>";
                                echo "<td>".$value['address']."</td>";
                                echo "<td>".$value['city']."</td>";
                                echo "<td>".$value['pincode']."</td>";
                                echo "<td>".$value['state']."</td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="append-data">
      </div>
    </div>
  </div>
</div>