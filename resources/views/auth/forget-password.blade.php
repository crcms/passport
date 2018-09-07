<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}} - 找回密码</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background: #999999;
        }

        .app-name {
            font-family: Arial;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .container-fluid {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box {
            background: #ffffff;
            height: auto;
            width: 30%;
            padding: 40px;
            border-radius: 10px;
        }

        .box .box-heading {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="box">
        <div class="box-heading text-center">
            <h2><span class="app-name">{{config('app.name')}}</span> - 找回密码</h2>
        </div>
        <div class="alert alert-danger message" role="alert" style="display: none"></div>
        <div class="box-body">
            <form action="{{route('passport.register.post')}}" name="register" class="form">
                <input type="hidden" name="_redirect" value="{{Request::input('_redirect')}}">
                <input type="hidden" name="app_key" value="{{Request::input('app_key')}}">
                <input type="hidden" name="type" value="{{$type}}">
                <div class="form-group">
                    <label>Email Or Mobile</label>
                    <input type="text" class="form-control form-control-lg" id="object" name="object"
                           placeholder="email">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <div class="form-inline" style="justify-content: space-between">
                        <input type="text" style="" class="form-control form-control-lg" name="code" placeholder="code">
                        <button class="btn btn-lg" id="send-code" type="button">发送</button>
                    </div>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">提交</button>
                </div>
                <div class="form-group text-right">
                    已有账号？<a
                            href="{{route('login',['_redirect'=>Request::input('_redirect'),'app_key'=>Request::input('app_key')])}}">点击登录</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
    $(function () {

        $('#send-code').on('click', function () {
            let object = $('#object').val();
            let type = $('input[name="type"]').val();
            let appKey = $('input[name="app_key"]').val();

            if (object === '') {
                alert('请输入手机号或邮箱');
                return false;
            }

            $.ajax({
                type: 'post',
                data: {
                    'app_key': appKey,
                    'object': object,
                    'type': type
                },
                dataType: 'json',
                url: '{{route('passport.code.post')}}',
                success: function (response) {
                    $('.message').addClass('alert-info').removeClass('alert-danger');

                    $('.message').text('已发送请注意查收').show();

                    return false;
                },
                error: function (error) {
                    $('.message').addClass('alert-danger').removeClass('alert-info');
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function (key, value) {
                            $('.message').text(value[0]).show();
                            return true;
                        });
                    } else if (error.responseJSON.message) {
                        $('.message').text(error.responseJSON.message).show();
                    }
                }
            });
        });

        $('form[name="register"]').on('submit', function () {
            $.ajax({
                type: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                url: $(this).attr('action'),
                success: function (response) {
                    $('.message').addClass('alert-info').removeClass('alert-danger');
                    if (response.data.cookie) {
                        let cookieName = response.data.cookie.name || 'token';
                        $.cookie(cookieName, response.data.cookie.token, {expires: parseInt(response.data.cookie.expired) / (24 * 60)});
                    }

                    $('.message').text('注册成功').show();

                    if (response.data.url) {
                        setTimeout(function () {
                            window.location.href = response.data.url
                        }, 800);
                    }

                    return false;
                },
                error: function (error) {
                    $('.message').addClass('alert-danger').removeClass('alert-info');
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function (key, value) {
                            $('.message').text(value[0]).show();
                            return true;
                        });
                    } else if (error.responseJSON.message) {
                        $('.message').text(error.responseJSON.message).show();
                    }
                }
            });
            return false;
        });
    });
</script>
</body>
</html>