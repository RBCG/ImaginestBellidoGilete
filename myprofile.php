<?php
session_start();

require_once("./db/controlProfile.php");

if (!isset($_SESSION['usuari'])) {
    header("Location: ./index.php?redirected");
    exit;
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
	<!-- Main CSS Files -->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,200,500,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" href="css/material-cards.css">
<!--===============================================================================================-->
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>
    
    <nav class="main-menu" style="z-index: 30">

        <span>
            <img src="./img/image2.jpg" href="./home.php" id="fotoSidebar" class="m-t-15"></img>
            <hr style="color:white 5px solid;">
        </span>

        <li class="has-subnav nombre">
            <a href="myprofile.php">
                <i class="fa fa-user fa-2x"></i>
                <span class="nav-text">
                <?php
                    require_once './db/controlUsuari.php';
                    $usuari = $_SESSION['usuari'];
                    $userName=getNameUserWithNameOrMail($usuari);
                    echo "@$userName";
                ?>
                </span>
            </a>
        </li>

        <ul>
            <li class="inicio">
                <a href="home.php">
                    <i class="fa fa-home fa-2x"></i>
                    <span class="nav-text">
                        Inicio
                    </span>
                </a>
            </li>
            <li class="has-subnav subirFotografia">
                <a href="#" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-upload fa-2x subirFotografia"></i>
                    <span class="nav-text">
                        Subir fotografia
                    </span>
                </a>
            </li>
            <li class="has-subnav videoPublicitario">
                <a href="#" data-toggle="modal" data-target="#video">
                    <i class="fa fa-play fa-2x"></i>
                    <span class="nav-text">
                        Ver video publicatario
                    </span>
                </a>
            </li>
            <li class="cambiarContraseña">
                <?php
                    if (strpos($usuari, '@') !== false) {
                        echo "<a href='resetPasswordDentro.php?mail=$usuari'>
                        <i class='fa fa-unlock-alt fa-2x'></i>
                        <span class='nav-text'>
                            Cambiar contraseña
                        </span>
                        </a>";
                    }
                    ?>
            </li>
            <li class="comoUsar">
                <a href="atajosDeTecladoPC.php">
                    <i class="fa fa-align-justify fa-2x"></i>
                    <span class="nav-text">
                        Atajos de teclado en PC
                    </span>
                </a>
            </li>
            <li class="politicas">
                <a href="politicas.php">
                    <i class="fa fa-info fa-2x"></i>
                    <span class="nav-text">
                        Politica de privacidad
                    </span>
                </a>
            </li>
            <li class="faq">
                <a href="faq.php">
                    <i class="fa fa-question-circle fa-2x"></i>
                    <span class="nav-text">
                        Preguntas frecuentes
                    </span>
                </a>
            </li>
            <li Class="logout">
                <a href="logout.php">
                    <i class="fa fa-power-off fa-2x"></i>
                    <span class="nav-text">
                        Cerrar sesión
                    </span>
                </a>
            </li>
        </ul>
    </nav> 

    <div id="main-menu-mobile">
        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <a href="myprofile.php"><?php echo "@$userName"; ?></a>
            <a href="home.php">Inicio</a>
            <a href="politicas.php">Política de privacidad</a>
            <a onclick="closeNav()" href="#" data-toggle="modal" data-target="#exampleModal">Subir fotografia</a>
            <?php
                if (strpos($usuari, '@') !== false) {
                    echo "<a href='resetPasswordDentro.php?mail=$usuari'>
                    <span class='nav-text'>
                        Cambiar contraseña
                    </span>
                    </a>";
                }
            ?>
            <a href="politicas.php">Política de privacidad</a>
            <a href="faq.php">Preguntas frecuentes</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>

        <div id="main">
            <button class="openbtn" onclick="openNav()">IMAGINEST - Perfil de @<?php echo $userName ?>▾</button>
        </div>
    </div>

    <h1 class="tituloapp" style="text-align: center;font-family: 'Titillium Web', sans-serif; padding: 0;font-weight: bold;">IMAGINEST - Perfil de @<?php echo $userName ?></h1>

    <?php 
        $userName=getNameUserWithNameOrMail($usuari);   //Este user ya se pilla en modal de arriba, una vez descomentado borrarlo
        $fotografiasUsuario = nombreFotosPublicadasByUser($userName); 

        $qttFotografias = count($fotografiasUsuario);
        $contador=1;$esFinal=false;

        foreach($fotografiasUsuario as $fotoUsu){
            if($contador==$qttFotografias)$esFinal=true;
            muestraFotografia($userName,$fotoUsu[0],$contador,$esFinal);
            $contador++;
        }
        
    ?>

    <!-- MODAL SUBIR FOTO -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content fondoModalFoto">
                <div class="modal-header">
                    <div class="centerText">
                        <h4 class="modal-title ModalLabelTamaño" id="exampleModalLabel">Crear publicación</h4>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body infoPublicPhoto">
                    <div class="PublicPhotoDiv1">
                        <img src="./img/image2.jpg" id="fotoAddPhoto" class="m-t-2"></img>
                    </div>
                    <div class="PublicPhotoDiv2 ">
                        <span>
                        <?php
                            echo "@$userName";
                        ?>
                        </span>
                        <br>
                        <span id="hashtag" style="color: purple">#</span>
                        <span id="letraL" style="color: red">L</span>
                        <span id="letraE" style="color: yellow">e</span>
                        <span id="letraG" style="color: green">G</span>
                        <span id="letraO" style="color: blue">o</span>
                        <span id="letraN" style="color: purple">n</span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="./db/controlArchivos.php" method="POST" enctype="multipart/form-data"> 
                        <div class="form-group">
                            <textarea maxlength="200" class="form-control" name="descripcionFoto" id="exampleFormControlTextarea1" placeholder="¿En que está pensando, <?php echo $userName?>?" rows="3" style="background-color: #242526; color: #C4C6CA; border: gray 1px solid"></textarea>
                        </div>
                        <div class="file-upload">

                            <div class="image-upload-wrap">
                                <input class="file-upload-input" type='file' name="fotoSubida" onchange="readURL(this);" accept="image/*" required />
                                <div class="drag-text">
                                    <h3>Arrastra y suelta una imagen o haz clic</h3>
                                </div>
                            </div>
                            <div class="file-upload-content">
                                <img class="file-upload-image" src="#"/>
                                <div class="image-title-wrap">
                                    <button type="button" onclick="removeUpload()" class="remove-image">Eliminar <span class="image-title">Uploaded Image</span></button>
                                </div>
                            </div>
                        </div>

                        <input class="btn-enviar" name="resetpassword" type="submit" value="Enviar"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL VIDEO -->
    <div class="modal fade" id="video" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="row">
                <div class="col">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/85MppyLJHz0?autohide=0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="/imaginest/js/home.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- NO BORRAR -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script> 

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
        $(function() {
            $('.material-card > .mc-btn-action').click(function () {
                var card = $(this).parent('.material-card');
                var icon = $(this).children('i');
                icon.addClass('fa-spin-fast');

                if (card.hasClass('mc-active')) {
                    card.removeClass('mc-active');

                    window.setTimeout(function() {
                        icon
                            .removeClass('fa-arrow-left')
                            .removeClass('fa-spin-fast')
                            .addClass('fa-bars');

                    }, 800);
                } else {
                    card.addClass('mc-active');

                    window.setTimeout(function() {
                        icon
                            .removeClass('fa-bars')
                            .removeClass('fa-spin-fast')
                            .addClass('fa-arrow-left');

                    }, 800);
                }
            });
        });

        function openNav() {
            document.getElementById("mySidebar").style.width = "100%";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }
    </script>

</body>
</html>