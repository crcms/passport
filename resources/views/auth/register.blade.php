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
<h1 class="message"></h1>
<form action="{{route('passport.register.post')}}" method="post" name="register">
    <input type="hidden" name="_redirect" value="{{Request::input('_redirect')}}">
    <input type="hidden" name="app_key" value="{{Request::input('app_key')}}">
    <input type="text" name="name" placeholder="name">
    <br>
    <input type="text" name="email" placeholder="email">
    <br>
    <input type="password" name="password" placeholder="password">
    <br>
    <button type="submit">Register</button>
</form>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(function () {
        $('form[name="register"]').on('submit', function () {
            $.ajax({
                type: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                url: $(this).attr('action'),
                success: function (response) {

                    console.log(response);
                },
                error: function (error) {
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function (key, value) {
                            $('.message').text(value[0]);
                            return true;
                        });
                    } else if (error.responseJSON.message) {
                        $('.message').text(error.responseJSON.message);
                    }
                }
            });
            // $.post($(this).attr('action'),$(this).serialize(),function(response,error){
            //    console.log(response,error);
            // });
            return false;
        });
    });
</script>
</body>
</html>