@component('mail::message')
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #F4F6FE; font-family: inherit;">
	<center style="width: 100%; background-color: #F4F6FE;">
		<div style="display: none; font-size: 16px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: inherit;"></div>
		<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;" class="email-container">
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
				<tr>
					<td class="bg_white logo" style="padding: .85em 2.5em; text-align: center">
						<h1 style="margin-bottom: 0;">
						<a href="#">
							<img src="https://main.d2jle80mf7dzx8.amplifyapp.com/_next/image?url=%2Fimages%2Flogo%2Flogo-dntrademark-final.png&w=640&q=75">
						</a>
						</h1> 
					</td>
				</tr>
				<tr>
					<td valign="middle" class="hero" style="background-image: url(https://images.pexels.com/photos/374720/pexels-photo-374720.jpeg); background-size: cover; background-position: top center; height: 300px;"></td>
				</tr>
				<tr>
					<td class="bg_white">
						<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td class="bg_dark email-section" style="text-align:center; padding: 2.5em 2.5rem 1.5rem;">
									<div class="heading-section heading-section-white">
										<h1>Welcome To DNTrademark!</h1>
										<p style="font-size: 17px;">Thank you for signing up! <span style="font-weight: bold;">{{ $name }}</span>. We will contact you as soon as possible.</p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="bg_white email-section" style="text-align:center; padding: 1.5em 2.5rem 4.5rem;">
									<a href="https://dash.dntrademark.com/auth/verify/{{ $verification_link }}" style="background: #E91E63;  padding: 1rem;  color: #fff;  text-decoration: none;  border-radius: 0.5rem;  font-weight: bold;">
									Learn more on DNTrademark.com
									</a>								
								</td>
							</tr>							
						</table>
					</td>
				</tr>
			</table>
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">				
				<tr>
					<td valign="middle" class="bg_black footer email-section" style="background-color: #E1E9FF; padding: 2.5em;">
						<table>
							<tr>
								<td valign="top" width="100%">
									<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
										<tr>
											<td style="text-align: left; padding-right: 10px;">
												<p>&copy; {{ date('now') }} dntrademark.com. All Rights Reserved</p>
											</td>
										</tr>
									</table>
								</td>
								<td valign="top" width="33.333%">
									<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
										<tr>
											<td style="text-align: right; padding-left: 5px; padding-right: 5px;">
												<p><a href="#" style="color: rgba(0,0,0,.5);">Unsubcribe</a></p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</center>
</body>
@endcomponent