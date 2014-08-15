<html>

<head>

	<title>Cambiar contraseña</title>
	
</head>

<body>

	<form action="/recovery-change" method="post">
	
		<input type="hidden" name="token" value="{{ $token }}" />
		<input type="hidden" name="email" value="{{ $email }}" />
	
		<label for="password"> </label> Password<input type="text" name="password" > <br/>

		<br/>
		
		<label for="password_confirm"> </label> Confirmar<input type="text" name="password_confirm" > <br/>

		<br/>		
		
		<input type="submit" value="enviar" />
	
	</form>


</body>

</html>