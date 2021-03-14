<?php
    require_once('./db/controlUsuari.php');
    require_once('./mailPassword.php');
    require_once('./index.php');

    if (isset($_POST['email']))
        {
            $emailPOST = filter_input(INPUT_POST, 'email');
            if (existeixEmail($emailPOST))
            {
                $msg = TRUE;
                solicitarCambiarContrasena($emailPOST);
                $activationPassCode = getPassCode($emailPOST);
                enviarMailPass($emailPOST,$activationPassCode[0]);
            }
            else{
                $err2 = TRUE;
            }
        }

    


    