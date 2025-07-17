<?php $this->load->view('email_tem/emailer_header'); ?>
</head>
<body>
<table style="width:600px; max-width: 600px;background-color: #fff;">

<tr class="email-head">
	<td>
		<h3>Dear <span class="last-name"><?php echo isset($email_info['first_name']) ? $email_info['first_name'] : 'Sir/ Madam'; ?>,</span></h3>
	</td>
</tr>
<tr class="email-footer">
	<td>
		<p>Greetings from <a href="<?php echo SITE_URL; ?>"><?php echo CRMNAME; ?></a> You recently requested to reset your password for your <?php echo CRMNAME; ?> BGV account.</p>		
		
		<p>Click on to the link below to reset your password</p>		
		
		<div class="btn-div"><a href="<?php echo $email_info['pass_url'];?>">Change password</a></div>
		
		<p>Please ignore this email if you did not request for a password change.</p>

	</td>
</tr>
</table>
</body>
</html>