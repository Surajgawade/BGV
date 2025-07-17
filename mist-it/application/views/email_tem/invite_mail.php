<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_header'); ?>
</head>
<body>
<table style="width:600px; max-width: 600px;background-color: #fff;">
<tr class="email-head">
	<td>
		<h3><b>Dear Sir/Madam,</b></h3>
	</td>
</tr>
<tr class="">
	<td>
		
		<p><b>MIST IT Services Private Ltd </b> is a background verification company conducting employment verification services for <b><?php echo  ucwords($email_info['clientname']) ?></b> We authenticate the details that were provided by the candidates during their selection process.</p>
		<p><?php echo  ucfirst($email_info['address']) ?></b></p>
		<p>We request you to copy or visit the link on your smartphone and complete the verification at the earliest. </p>
		<p> <?php echo $email_info['login_url']; ?></p></br>
		<p>Thanking you in advance and wish you all the best for your new role.</p></br>
	</td>
</tr>
<tr class="email-footer">
	<td>
		<h4>Regards,</h4>
		<p>BGV Team</p>
	</td>
</tr>
</table>
</body>
</html>