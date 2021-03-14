<?php
    require_once('./db/controlUsuari.php');
    require_once('./index.php');

    if (!empty($_GET["code"]) && !empty($_GET["mail"]))
    {
        $code = filter_input(INPUT_GET, 'code');
        $mail = filter_input(INPUT_GET, 'mail');
        
        if (verificarCodeMail($code, $mail))
        {
            activarCompteUsuari($mail);
            header("Location: index.php");
        }
    }
