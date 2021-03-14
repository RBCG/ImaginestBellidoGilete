<?php
    require_once('./db/controlUsuari.php');
    require_once('./mail.php');

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['verifyPass'])){

            //Protección ante XSS
            $userPOST = filter_input(INPUT_POST, 'user');
            $emailPOST = filter_input(INPUT_POST, 'email');
            $firstNamePOST = filter_input(INPUT_POST, 'firstName');
            $lastNamePOST = filter_input(INPUT_POST, 'lastName');
            $passPOST = filter_input(INPUT_POST, 'pass');
            $verifyPassPOST = filter_input(INPUT_POST, 'verifyPass');
            $passPOSTHash = password_hash($passPOST, PASSWORD_DEFAULT); 

            if (existeixUsername($userPOST)) $error = "Este usuario ya existe.";
            else if (existeixEmail($emailPOST)) $error = "Este email ya existe.";
            else if ($passPOST!=$verifyPassPOST) $error = "Las contraseñas no son iguales.";
            else {
                registrarUsuari($userPOST, $emailPOST, $firstNamePOST, $lastNamePOST, $passPOSTHash);
                $activationCode=getActivationCode($userPOST);
                enviarMail($emailPOST, $activationCode[0]);
                header("Location: index.php");
                exit();
            }
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
				<div class="login100-pic js-tilt p-t-88" data-tilt>
					<img src="img/logoImaginest.png" alt="IMG">
				</div>

				<!-- FORM -->
				<form method="POST" class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<span class="login100-form-title">
					IMAGINEST - <br> REGISTRARSE
					</span>

					<?php 
                        if(isset($error)){
                            echo '<div class="alert alert-danger alert-dismissible fade show m-t-5" role="alert">
                            '. $error .'
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                        }
                    ?>

					<!-- USUARIO -->
					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="user" placeholder="Usuario" required value="<?php if(isset($user)) echo $user;?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

                    <!-- MAIL -->
					<div class="wrap-input100 validate-input">
						<input class="input100" type="email" name="email" placeholder="Correo electrónico" required value="<?php if(isset($email)) echo $email;?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                    </div>
                    
                    <!-- FIRST NAME -->
					<div class="wrap-input100">
						<input class="input100" type="text" name="firstName" placeholder="Nombre" value="<?php if(isset($firstName)) echo $firstName;?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
                    </div>

                    <!-- LAST NAME -->
					<div class="wrap-input100">
						<input class="input100" type="text" name="lastName" placeholder="Apellido/s" value="<?php if(isset($lastName)) echo $lastName;?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
                    </div>
                    
					<!-- CONTRASEÑA -->
					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="pass" required placeholder="Contraseña">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                    </div>
                    
                    <!-- VERIFICAR CONTRASEÑA -->
					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" name="verifyPass" required placeholder="Repita la contraseña">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="custom-control custom-switch p-l-40 wrap-input100 validate-input">
						<input type="checkbox" class="custom-control-input" id="switch1" name="example" required>
						<label class="custom-control-label fs-12" for="switch1">He leido y acepto la <a class="fs-13" href="#" data-toggle="modal" data-target="#exampleModalLong">Política de privacidad.</a></label>
					</div>

					<!-- Modal -->
					<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Políticas de privacidad</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p style=" text-align: justify;text-justify: inter-word;">
							En esta web se utilizan cookies de terceros y propias para conseguir que tengas una mejor experiencia de navegación, puedas compartir contenido en redes sociales y para que podamos obtener estadísticas de los usuarios.
							Puedes evitar la descarga de cookies a través de la configuración de tu navegador, evitando que las cookies se almacenen en su dispositivo.
							Este tipo de Cookie recuerda sus preferencias para las herramientas que se encuentran en los servicios, por lo que no tiene que volver a configurar el servicio cada vez que usted visita.
							<br><br><b>Cookies de geo-localización: </b><br>
							Estas cookies son utilizadas para averiguar en qué país se encuentra cuando se solicita un servicio. Esta cookie es totalmente anónima, y sólo se utiliza para ayudar a orientar el contenido a su ubicación.
							<br><br><b>Cookies de registro: </b><br>
							Las cookies de registro se generan una vez que el usuario se ha registrado o posteriormente ha abierto su sesión, y se utilizan para identificarle en los servicios.
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
						</div>
					</div>
					</div>

					<div class="wrap-input100">
						<input class="input100" type="text" name="lastName" placeholder="Apellido/s" value="<?php if(isset($lastName)) echo $lastName;?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
                    </div>

					<!-- BOTON LOGIN -->
					<div class="container-login100-form-btn m-b-2">
						<button class="login100-form-btn" type="submit">
                            <span>CREAR CUENTA</span>
						</button>
					</div>

					<div class="text-center p-t-35 p-b-10">
						<a class="txt2" href="index.php" >
							<i class="fa fa-long-arrow-left m-r-5" aria-hidden="true"></i>
							Tienes cuenta? Inicia sesión.
						</a>
					</div>
				</form>
			</div>
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
</body>
</html>