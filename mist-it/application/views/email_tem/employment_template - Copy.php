<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_header'); ?>
<body>
	<table style="width:800px; max-width: 800px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
		<tr class="email-head">
			<td>
				<h3><b>Dear Sir/Madam,</b></h3>
			</td>
		</tr>
		<tr class="email-body">
			<td>
				<p style="text-align: justify"><b><?php echo CRMNAME; ?>.</b> is a background verification company. We verify the details of the candidates as presented by them in their application forms. The candidates are either already on board or are been considered for employment by our client.</p>
				<p>In this regard we are reaching out to you with reference to <b><?= ucwords($email_info['CandidateName']) ?></b> who claims to have been working for your company <b><?= ucwords(strtolower($email_info['coname'])) ?></b>. We request you to kindly verify the details enumerated below so that we may process the applicant’s details efficiently.</p>
				<p>Thank you for your valuable time and feedback in advance.</p>
			</td>
		</tr>
	</table>
	<br>
	<table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
		<tbody>
		<tr>
			<th style='background-color: #EDEDED;width: 400px'></th>
			<th style='background-color: #EDEDED;width: 200px'><b>Provided by the candidate</b></th>
			<th style='background-color: #EDEDED;width: 200px'><b>Your input</b></th>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Candidate Name</td>
			<td colspan="2"><?= ucwords($email_info['CandidateName']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Company Name</td>
			<td colspan="2"><?= ucwords($email_info['coname']) ?></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Employee Id</td>
			<td><?= (ucwords($email_info['empid']) != "") ? ucwords($email_info['empid']) : 'Please Provide' ?></td>
			<td></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Period of Employment</td>
			<td><?= $email_info['period_of_emp']; ?></td>
			<td></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Designation</td>
			<td><?= (!empty($email_info['designation']) ? ucwords($email_info['designation']) : 'Please Provide') ?></td>
			<td></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Remuneration</td>
			<td><?= (!empty($email_info['remuneration']) ? ucwords($email_info['remuneration']) : 'Please Provide') ?></td>
			<td></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Supervisor's Name & Designation</td>
			<td><?= (!empty($email_info['reportingmanager']) ? ucwords($email_info['reportingmanager']) : 'Please Provide') ?></td>
			<td></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Reason for Leaving</td>
			<td><?= (!empty($email_info['reasonforleaving']) ? ucwords($email_info['reasonforleaving']) : 'Please Provide') ?></td>
			<td></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Integrity/Disciplinary/Personal issues if any</td>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Eligible for re-hire (<b><i>If No please provide reason</i></b>)</td>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Exit formalities completed (<b><i>If No please provide whether pending from Company or Candidate's end</i></b>)</td>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Verifier’s Name & Designation</td>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td style='background-color: #EDEDED;'>Additional HR Comments</td>
			<td colspan="2"></td>
		</tr>
		</tbody>
	</table>
	<br>
	<table style="width:800px; max-width: 800px;background-color: #fff;" border=0>
		<tr><td>
	<p><b><?php echo CRMNAME; ?>.</b> is an organization providing professional consulting and training to various verticals across all sectors. It is headquartered in Singapore and has multi-country presence. We provide a wide array of services which include training, employee background screening, consultancy and solutions to businesses and educational institutes. If you like to partner with us or know more, you can email us at <?php echo FROMEMAIL: ?> or call us at +91 22 62852713.</p>
		</td></tr>
	</table>
	<table style="width:800px; max-width: 800px;background-color: #fff;" border=0>
		<tr>
			<td colspan="2"><p>Thanks and Regards,</p><p><?= ucwords($this->user_info['firstname']) ?></p></td>
		</tr>
		<tr>
			<td colspan="2"><p><b>Employee Verification - Operations</b></p></td>
		</tr>
		<tr class="email-footer">
			<td>
				<p><img  style="width:200px;border:0;padding-bottom: 30px;"  hspace="10" vspace="20" width="200" id="logo" src="" alt="Logo"/></p>
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td colspan="2" style="color: #4CAF50"><b>Think Green & Save Trees: Print this e-mail only if you really need a hard copy!</b></td>
		</tr>
		<tr>
			<td colspan="2"><br><p style="text-align: justify;">This message may contain confidential information. If you have received this message by mistake, please inform the sender by sending an e-mail reply. At the same time please delete the message and any attachments from your system without making, distributing or retaining any copies. Although all our e-mails messages and any attachments upon sending are automatically virus scanned we assume no responsibility for any loss or damage arising from the receipt and/or use.</p></td>
		</tr>
	</table>
</body>
</html>