<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <p>Hi <strong>{{ $user->name }}</strong></p>
    <p>UserName: <strong>{{ $user->email }}</strong></p>
    <p>Password: <strong>1234</strong></p>

    {!! clean($template) !!}
</body>
</html>
