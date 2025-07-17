<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_header'); ?>
<style type="text/css">
	.ml-auto {
    margin-left: 85px;

}
.form-control {
    display: block;
    width: 100%;
   
}
@media (min-width: 576px)
.col-sm-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
}

.div1 {
  border: 1px solid black;
  margin-left: 15px;
  margin-right: 15px;
  background-color: #2b4664;
  width: 100%;
  color : aliceblue;
}

</style>
<main>
      <br>
        <div class="row">
            <div class="col-sm-11 ml-auto">
<body>
	<table style="width:800px; max-width: 800px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
		<tr class="email-head">
			<td>
				<h3 style="text-align: justify"><b>Dear Sir/Madam,</b></h3>
			</td>
		</tr>
		<tr class="email-body">
			<td>
				<p style="text-align: justify"><b><?php echo CRMNAME; ?></b> is a background verification company conducting employment verification services for their clients. We authenticate the details that were presented by candidates during their selection process. These candidates have either joined or have been shortlisted by our clients for employment.</p>
				<p style="text-align: justify">For that purpose, we request you to provide a quick feedback with reference to <b><?=strtoupper($email_info['CandidateName']) ?>.</b></p>
				<p style="text-align: justify">Thank you for your valuable time and feedback in advance.</p>
			</td>
		</tr>
	</table>
	<br>
	<table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
		<tbody>
			<tr>
			<th colspan="2"  style='background-color: #EDEDED;'> Please provide your input inside the box</th>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;' ><b>Name of Reference</b></td>
			<td> <?= ucwords($email_info['name_of_reference']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Ability to Handle Pressure</b></td>
			<td><?= ucwords($email_info['handle_pressure_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Attendance/Punctuality</b></td>
			<td><?= ucwords($email_info['attendance_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Integrity, Character & Honesty</b></td>
			<td><?= ucwords($email_info['integrity_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Leadership Skills</b></td>
			<td><?= ucwords($email_info['leadership_skills_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Responsibilities & Duties</b></td>
			<td><?= ucwords($email_info['responsibilities_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b> Specific Achievements</b></td>
			<td><?= ucwords($email_info['achievements_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b> Strengths</b></td>
			<td><?= ucwords($email_info['strengths_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Weakness</b></td>
			<td><?= ucwords($email_info['weakness_value']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Team Player</b></td>
			<td> <?= ucwords($email_info['team_player_value']) ?></td>
		</tr>
		
		<tr>
			<td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b> Overall Performance (On a Scale of 1 to 10) <br>
			(If below 4 or equal to 4 please provide comments below)</b></td>
			<td><?= ucwords($email_info['overall_performance']) ?></td>
		</tr>
		<tr>
			<td  style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b> Additional Comments</b></td>
			<td><?= ucwords($email_info['additional_comments']) ?></td>
		</tr>
		</tbody>
	</table>
	 <br>
    <table style="width:800px; max-width: 800px;background-color: #fff;" border=0>
        <tr><td>
    <p style="text-align: justify"><b><?php echo CRMNAME; ?>.</b> is an organization providing professional consulting and training to various verticals across all sectors. It is headquartered in Singapore and has multi-country presence. We provide a wide array of services which include training, employee background screening, consultancy and solutions to businesses and educational institutes. If you like to partner with us or know more, you can email us at <a href="mailto:<?php echo DISCEMAIL ?>"> <?php echo DISCEMAIL ?> </a> or call us at +91 <?php echo MOBILENO ?></p>
        </td></tr>
    </table>
	</br>
<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_signature'); ?>

</body>
</html>