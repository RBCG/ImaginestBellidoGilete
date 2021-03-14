<?php
    require_once('./db/controlUsuari.php');
    require_once('./mailSuccessPassword.php');

    session_start();

    if (!isset($_SESSION['usuari'])) {
        header("Location: ./index.php?redirected");
        exit;
    }
    else {
        if (!empty($_GET['mail'])) {
            $mail = filter_input(INPUT_GET, 'mail');
        }
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['pass']) && isset($_POST['pass2'])) {
                $pass = filter_input(INPUT_POST, 'pass');
                $pass2 = filter_input(INPUT_POST, 'pass2');
                $mailOculto = filter_input(INPUT_POST, 'mailOculto');
    
                if ($pass == $pass2) {
                    $passPOSTHash = password_hash($pass, PASSWORD_DEFAULT);
                    actualizarContraseña($passPOSTHash, $mailOculto);
                    enviarMailPass($mailOculto);
                    header("Location: home.php");
                } else{
                    $error = TRUE;
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <title>IMAGINEST - TU RED SOCIAL</title>
    <link rel="stylesheet" type="text/css" href="./css/main.css" />
    <link rel="icon" href="./img/logoImaginest.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/mainNew.css">
    <!--===============================================================================================-->
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt p-b-16" data-tilt>
                    <img src="img/logoImaginest.png" alt="IMG">
                </div>

                <!-- FORM -->
                <form method="POST" class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <span class="login100-form-title">
                        IMAGINEST - <br> REESTABLECER CONTRASEÑA
                    </span>

                    <?php
                        if(isset($error) && $error == TRUE){
                            echo '<div class="alert alert-danger alert-dismissible fade show m-t-5" role="alert">
                            Las contraseñas no coinciden
                            </div>';
                        }
                    ?>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="mail" placeholder="Email" value="<?php if(isset($mail)) echo $mail; else echo $mailOculto?>" disabled>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
					</div>

                    <!-- CONTRASEÑA -->
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="password" name="pass" placeholder="Nueva contraseña" value="<?php if (isset($pass)) {echo $pass;}?>" autocomplete="off" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <!-- VERIFICAR CONTRASEÑA -->
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="password" name="pass2" placeholder="Repita la contraseña" value="<?php if (isset($pass2)) {echo $pass2;}?>" autocomplete="off" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <input type="hidden" name="mailOculto" value="<?php if (isset($mail)) echo $mail; else echo $mailOculto?>">

                    <!-- BOTON LOGIN -->
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            ACTUALIZAR CONTRASEÑA
                        </button>
                    </div>

                    
                </form>
            </div>
        </div>
    </div>

    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
        <script src="vendor/tilt/tilt.jquery.min.js"></script>
        <script >
            $('.js-tilt').tilt({
                scale: 1.1
            })
        </script>
    <!--===============================================================================================-->

</body>
</html>