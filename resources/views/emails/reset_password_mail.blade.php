<head>
    <title>Password Reset</title>
</head>
<body>
<h1>Password Reset Request</h1>
<p>Hello {{ $name }},</p>
<p>We received a request to reset your password. You can reset it by clicking the link below:</p>
<a href="{{ url('password/reset', ['token' => $token, 'user_id' => $user_id]) }}">Reset Password</a>
<p>If you did not request a password reset, please ignore this email.</p>
<br>
<p>© Fakebook</p>
</body>
