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
<form action="{{route('passport.login.post')}}" method="post">
    <input type="hidden" name="_redirect" value="{{Request::input('_redirect')}}">
    <input type="hidden" name="application_key" value="{{Request::input('application_key')}}">
    <input type="text" name="name">
    <br>
    <input type="password" name="password">
    <br>
    <button type="submit">login</button>
</form>
</body>
</html>