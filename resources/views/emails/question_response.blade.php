<head>
    <title>Response to Your Question on FakeBook</title>
</head>
<body>
    <p>Dear {{ $question->name }},</p>
    <p>Thank you for reaching out to us.</p>
    <p>Following the question you asked on our platform, here's our response:</p>
    <p><strong>Your Question:</strong><br>{{ $question->message }}</p>
    <p><strong>Our Response:</strong><br>{{ $response }}</p>
    <p>We are more than happy to help you if you need more assistance!</p>
    <p>Best regards,<br>Team Fakebook</p>
    <br>
    <p>© Fakebook</p>
</body>
