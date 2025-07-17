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
				<p style="text-align: justify"> Employment Cases Insuff Details</p>
			</td>
		</tr>
	</table>
	<br>
	<table class="table-content" style="width:600px; max-width: 600px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
		<tbody>
		<tr>
			<td> Case initiated Date</td>
			<td> <?= $email_info['caserecddate'] ?></td>
		</tr>
		<tr>
			<td> Raised By</td>
			<td> <?= ucwords($this->user_info['full_name']) ?></td>
		</tr>
		<tr>
			<td> Status</td>
			<td> <?= $email_info['verfstatus'] ?></td>
		</tr>
		<tr>
			<td> Time Stamp</td>
			<td> <?= date("d-m-Y h:i A",time()) ?></td>
		</tr>
		<tr>
			<td> Insuff. Raised Date 1</td>
			<td> <?= $email_info['insuffraiseddate'] ?></td>
		</tr>
		<tr>
			<td> Insuff. Remarks 1</td>
			<td> <?= $email_info['insuffremark'] ?></td>
		</tr>
		<tr>
			<td> Insuff. Raised Date 2</td>
			<td> <?= $email_info['insuff_raised_date_2'] ?></td>
		</tr>
		<tr>
			<td> Insuff. Remarks 2</td>
			<td> <?= $email_info['insuff_remarks_2'] ?></td>
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