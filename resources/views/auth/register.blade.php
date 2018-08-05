<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{route('passport.register.post')}}" method="post">
    <input type="hidden" name="_redirect">
    <input type="text" name="name">
    <br>
    <input type="text" name="email">
    <br>
    <input type="password" name="password">
    <br>
    <button type="submit">Register</button>
</form>
</body>
</html>