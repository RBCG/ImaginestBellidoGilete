<?php
    use PHPMailer\PHPMailer\PHPMailer;
    function enviarMailPass($email)
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
        $mail->Subject = 'Contrasena actualizada correctamente';
        $mail->MsgHTML('
        <div style="position: relative; background: #0bbcdb; height: 240px; padding: 1px; outline: none; box-sizing: border-box; display:block;">
            <p style="text-align: center; font-size: 30px; margin: 50px; color: #fff">Su contraseña ha sido actualizada correctamente.</p>
        </div>
        ');

        $mail->AddAddress($email, 'Activar cuenta');

        $result = $mail->Send();
    }