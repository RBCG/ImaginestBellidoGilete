<?php
    require_once('./db/controlUsuari.php');
    require_once('./resetPasswordSend.php');
    
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['user']) && isset($_POST['pass'])){
            //Protección ante XSS
            $userPOST = filter_input(INPUT_POST, 'user');
            $passPOST = filter_input(INPUT_POST, 'pass');
            $passHash = getHashPass($userPOST);

            //$usuari = verificaUsuari($userPOST);
            if(verificaUsuari($userPOST) && password_verify($passPOST,$passHash[0])){
                session_start();
                if (!empty($userPOST)) $_SESSION['usuari'] = $userPOST;
                header("Location: home.php");
                exit();
            }else{
                $err = TRUE;
                $user = $userPOST;
            }
            $err = TRUE;
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>IMAGINEST - TU RED SOCIAL</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="img/logoImaginest.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/mainNew.css">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt p-t-23" data-tilt>
					<img src="img/logoImaginest.png" alt="IMG">
				</div>

				<!-- FORM -->
				<form method="POST" class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<span class="login100-form-title">
						IMAGINEST - <br> INICIAR SESIÓN
					</span>

					<?php 
                        if(isset($err) && $err == TRUE){
                            echo '<div class="alert alert-danger alert-dismissible fade show m-t-5" role="alert">
                            Revisa el correo electrónico y/o la contraseña. Tiene su cuenta activada?
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                        if(isset($err2) && $err2 == TRUE){
                            echo '<div class="alert alert-danger alert-dismissible fade show m-t-42" role="alert">
                            Correo no registrado!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                        if(isset($err3) && $err3 == TRUE){
                            echo '<div class="alert alert-danger alert-dismissible fade show m-t-5" role="alert">
                            Las contraseñas no coinciden
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                        if(isset($msg) && $msg == TRUE){
                            echo '<div class="alert alert-success alert-dismissible fade show m-t-5" role="alert">
                            Enlace para reestablecer contraseña enviado a correo electrónico
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                        if(isset($msg2) && $msg2 == TRUE){
                            echo '<div class="alert alert-success alert-dismissible fade show m-t-5" role="alert">
                            Usuario creado correctamente! Active su cuenta
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                        if(isset($msg3) && $msg3 == TRUE){
                            echo '<div class="alert alert-success alert-dismissible fade show m-t-5" role="alert">
                            Cuenta activada correctamente! Ya puede iniciar sesión
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                    ?>

					<!-- EMAIL -->
					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="user" placeholder="Usuario / Email" required value="<?php if(isset($user)) echo $user;?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<!-- CONTRASEÑA -->
					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="pass" placeholder="Contraseña" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<!-- BOTON LOGIN -->
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							INICIAR SESIÓN
						</button>
					</div>

					<!-- RECUPERAR CONTRASEÑA -->
                    <div class="text-center p-t-12 p-b-4">
						<a href="#" data-toggle="modal" data-target="#exampleModal">
							Has olvidado la contraseña?
						</a>
					</div>

					<div class="text-center p-t-35 p-b-10">
						<a class="txt2" href="register.php" >
							No tienes cuenta? Registrate
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>

				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel">Has olvidado la contraseña?</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<h5>Introduce tu correo electrónico</h5>
							</div>
							<div class="modal-body">
								<form action="resetPasswordSend.php" method="POST">
									<input id="usuario" type="text" class="user p-b-10" name="email" placeholder="Ej: johndoe@gmail.com" style="width: 100%" required/>
									<br><hr>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
									<input class="btn btn-success" name="resetpassword" type="submit" value="Enviar"/>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<p class="hechoPor">Hecho por <b>Raúl Bellido</b> - <b>Carlos Gilete</b></p>
		</div>

		
	</div>

	
	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

	<script>
        function openForm() {
        	document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
        	document.getElementById("myForm").style.display = "none";
        }
	</script>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</body>
</html>