$message = "<p>Team,</p><p>Please find attached list of pending cases.</p>
                  <p><b>Address :</b></p>";
                  $message .= "<div style = 'width: 50%;float: left;'><h5>IN TAT :</h5></div>";
                  $message .= "<div style = 'width: 50%;float: left;'><h5 style= 'color:red;'><b>OUT TAT :</h5></div>";
                  $message .= "<div style = 'width: 50%;float: left;'>";
                    $message .= "<table border = '1'>
                  <tr>
                  <th style='text-align:center'>Allocated date </th>
                  <th style='text-align:center'>Cases</th>
                  <th style='text-align:center'>Days</th>
                  </tr>";
                    $total = 0;
                    foreach ($count_datewise_record as $count_datewise_records) {
                        $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));

                        if($hold_day < 7)
                        {
                         

                        $message .= '<tr>
                  <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                  <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                  <td style="text-align:center">' . $hold_day . '</td>
                  </tr>';
                        $total += $count_datewise_records['count_record'];

                        }
                    }

                    $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center" colspan="2"><b>' . $total . '</b></tr>';
                    $message .= "</table></div></br>";
                    
                    
                    $message .= "<div style = 'width: 50%;float: left;'>
                    <table border = '1'>
                    <tr>
                    <th style='text-align:center'>Allocated date </th>
                    <th style='text-align:center'>Cases</th>
                    <th style='text-align:center'>Days</th>
                    </tr>";
                      $total = 0;
                      foreach ($count_datewise_record as $count_datewise_records) {
                          $hold_day = getNetWorkDays($count_datewise_records['date'], date("d-m-Y"));
  
                          if($hold_day > 6)
                          {
                             
                          $message .= '<tr>
                    <td style="text-align:center">' . $count_datewise_records['date'] . '</td>
                    <td style="text-align:center">' . $count_datewise_records['count_record'] . '</td>
                    <td style="text-align:center">' . $hold_day . '</td>
                    </tr>';
                          $total += $count_datewise_records['count_record'];

                          }
                      }
  
                      $message .= '<tr><td style="text-align:center"><b>Total Cases</b></td><td style="text-align:center;color:red;" colspan="2"><b>' . $total . '</b></tr>';
                      $message .= "</table></div></br>";

                    $message .= "<div style = 'width: 100%;float: left;'><p><b>Note :</b> <I>This is an auto generated email. Request you to write back in case a check is closed as per your knowledge but is showing as pending.</I></p><div>";
         
