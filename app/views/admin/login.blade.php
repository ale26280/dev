<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>:: Sistema de Gestion Web ::</title>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/bootstrap-responsive.css') }}
		{{ HTML::style('css/uniform.css') }}
		{{ HTML::style('css/login.css') }}
	
        {{ HTML::script('js/lib/jquery.min-1.7.2.js') }}
        {{ HTML::script('js/app.admin.js') }}
		
    </head>
    <body>
        <div id="logo">
            <img src="{{URL::to('img/logoAdmin.png')}}">
        </div>
        <div id="loginbox">
		

            {{ Form::open( array('method' => 'POST', 'url' => 'adm/login', 'id' => 'loginform', 'class' => 'form-vertical') ) }}            
				<p>Ingrese su usuario y contraseña.</p>
                <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-user"></i></span>{{Form::text('username')}}
                        </div>
                    </div>
                </div>
                <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                    <div class="controls ">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-lock"></i></span>{{Form::password('password')}}
                        </div>
                    </div>
                </div>

                <div id="errorVal">
                    {{ $errors->first('username') }}
                    {{ $errors->first('password') }}                    
                </div>

                <div class="form-actions">
                   
                    <span class="pull-right"><input class="btn btn-inverse" value="Login" type="submit"></span>
                </div>
            {{Form::close()}}

        </div>
        


</body>

</html>

