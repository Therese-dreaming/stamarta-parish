<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Support Ticket</title>
</head>
<body style="margin:0;padding:0;background:#f6f6f6;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
	<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f6f6;padding:24px 0;">
		<tr>
			<td align="center">
				<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
					<tr>
						<td style="background:#0d5c2f;color:#ffffff;padding:16px 20px;">
							<h1 style="margin:0;font-size:18px;">New Support Ticket</h1>
							<p style="margin:4px 0 0;font-size:12px;opacity:0.85;">Ticket #{{ $ticket->id }} • {{ $ticket->created_at->format('M d, Y H:i') }}</p>
						</td>
					</tr>
					<tr>
						<td style="padding:20px;">
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:separate;border-spacing:0 8px;">
								<tr>
									<td style="width:160px;color:#6b7280;font-size:12px;">From</td>
									<td style="font-size:14px;color:#111827;">{{ $ticket->name }} &lt;{{ $ticket->email }}&gt;</td>
								</tr>
								<tr>
									<td style="width:160px;color:#6b7280;font-size:12px;">Subject</td>
									<td style="font-size:14px;color:#111827;">{{ $ticket->subject }}</td>
								</tr>
								<tr>
									<td style="width:160px;color:#6b7280;font-size:12px;vertical-align:top;">Message</td>
									<td style="font-size:14px;color:#111827;line-height:1.5;white-space:pre-wrap;">{{ $ticket->message }}</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding:16px 20px;border-top:1px solid #e5e7eb;color:#6b7280;font-size:12px;">
							<p style="margin:0;">Status: <strong style="color:#111827;">{{ ucfirst($ticket->status) }}</strong></p>
							<p style="margin:4px 0 0;">Reply directly to the sender's email to respond.</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html> 