<head>
    <title>Password Reset</title>
</head>
<body>
<h1>Password Reset Request</h1>
<p>Hello {{ $name }},</p>
<p>We received a request to reset your password. You can reset it by clicking the link below:</p>
<a href="{{ url('password/reset', ['token' => $token]) }}">Reset Password</a>
<p>This link will expire in 15 minutes.</p>
<p>If you did not request a password reset, please ignore this email.</p>
<br>
<p>© Fakebook</p>
</body>
