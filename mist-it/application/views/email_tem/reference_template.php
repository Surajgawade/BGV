<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_header'); ?>
<body>
	<table style="width:600px; max-width: 600px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
		<tr class="email-head">
			<td>
				<h3>Dear Sir/Madam,</h3>
			</td>
		</tr>
		<tr class="email-body">
			<td>
				<p style="text-align: justify"><b><?php echo CRMNAME; ?>.</b> is an organization providing professional consulting and training to various verticals across all sectors. It is headquartered in Singapore and has multi-country presence.
				<b><?php echo CRMNAME; ?></b> provides a wide array of services which include training. Consultancy and solutions to businesses and educational institutes. We verify the particulars of the candidates as presented by them in their application forms. The candidates are either already working for our clients or being considered for employment.</p>
				<p>In this regard, we request you to kindly assist with the reference verification of <?=strtoupper($email_info['CandidateName']) ?>.</p>
				<p>A quick feedback from you will facilitate the candidate to join one of the reputed companies of India.</p>
			</td>
		</tr>
	</table>
	<br>
	<table class="table-content" style="width:600px; max-width: 600px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
		<tbody>
			<tr>
			<th> Particulars</th>
			<th> Verified information</th>
		</tr>
		<tr>
			<td> Reference Name</td>
			<td> <?= ucwords($email_info['referencename']) ?></td>
		</tr>
		<tr>
			<td> Designation</td>
			<td> <?= ucwords($email_info['designation']) ?></td>
		</tr>
		<tr>
			<td> Contact Number</td>
			<td><?= ucwords(strtolower($email_info['contact_number'])) ?></td>
		</tr>
		<tr>
			<td colspan="2" style='text-align: center;'><i>Details have to be filled in regard to the candidate</i></td>
		</tr>
		<tr>
			<td> Responsibilities & Duties</td>
			<td></td>
		</tr>
		<tr>
			<td> Attendance</td>
			<td></td>
		</tr>
		<tr>
			<td> Strengths</td>
			<td></td>
		</tr>
		<tr>
			<td> Weakness</td>
			<td></td>
		</tr>
		<tr>
			<td> Team Player</td>
			<td></td>
		</tr>
		<tr>
			<td> Leadership Skills</td>
			<td></td>
		</tr>
		<tr>
			<td> Ability to Handle Pressure</td>
			<td></td>
		</tr>
		<tr>
			<td> Specific Achievements</td>
			<td></td>
		</tr>
		<tr>
			<td> Overall Performance (On a Scale of 1 to 10)</td>
			<td></td>
		</tr>
		<tr>
			<td> Integrity, Character & Honesty</td>
			<td></td>
		</tr>
		<tr>
			<td> Additional Comments</td>
			<td></td>
		</tr>
		</tbody>
	</table>
	<br>
	<table style="width:600px; max-width: 600px;background-color: #fff;" border=0>
		<tr>
			<td colspan="2"><p>Thanks and Regards,</p></td>
		</tr>
		<tr>
			<td colspan="2"><p><b>Background Verification – Operations</b></p></td>
		</tr>
		<tr class="email-footer">
			<td>
				<p><img  style="width:200px;border:0;padding-bottom: 30px;"  hspace="10" vspace="20" width="200" id="logo" src="http://www..com/img/logo-base.png" alt="Logo"/></p>
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