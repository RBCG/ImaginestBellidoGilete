<?php
    use PHPMailer\PHPMailer\PHPMailer;
    function enviarMailPass($email, $activationCode)
    {
        require 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
    
        //Configuració del servidor de Correu
        //Modificar a 0 per eliminar msg error
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        
        //Credencials del compte GMAIL
        $mail->Username = 'imaginestsoporte';
        $mail->Password = 'Educem00.';
    
        //Dades del correu electrònic
        $mail->SetFrom('imaginestsupport@gmail.es','Imaginest');
        $mail->Subject = 'Correo para reestablecer la contrasena';
        $mail->MsgHTML('
        <div style="position: relative; background: #0bbcdb; height: 240px; padding: 1px; outline: none; box-sizing: border-box; display:block;">
            <p style="text-align: center; font-size: 30px; margin: 50px; color: #fff">Restablecer la contraseña</p>
            <p style="text-align: center; font-size: 20px; margin-top: 50px; color: #fff">Pulsa en "Reestablecer contraseña"</p>
            <a style="position: relative; display:block; text-align: center; font-size: 30px;" href="http://localhost/imaginest/resetPassword.php?code=' . $activationCode . '&mail=' . $email . '">Reestablecer contraseña!</a>
        </div>
        ');

        $mail->AddAddress($email, 'Activar cuenta');

        $result = $mail->Send();
    }