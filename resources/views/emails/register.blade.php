<h1>欢迎注册</h1>
<strong>{{$user->name}}</strong>，欢迎注册，请
<a href="{{$url}}" target="_blank">点击此处</a>
，进行邮件验证,{{config('user.register_mail_time_interval')}}分钟有效