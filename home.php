<?php
session_start();
require_once('./db/controlLikeDislike.php');
require_once('./db/controlUsuari.php');

if (!isset($_SESSION['usuari'])) {
    header("Location: ./index.php?redirected");
    exit;
}

$fotoRandom=false;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // nombre foto anterior
    $namePhotoOculto = filter_input(INPUT_POST, 'namePhotoOculto');

    //nueva foto
    $post = getImageSQLRandom($namePhotoOculto);
    $nameUserPost = getUserNamePost($post[0])[0];

    $nameUser = getNameUserWithNameOrMail($_SESSION['usuari']);

    if(isset($_POST["like"])){
        darLike($namePhotoOculto,$nameUser);
    }else if(isset($_POST["dislike"])){
        darDislike($namePhotoOculto,$nameUser);
    }

    $fotoRandom=true;
}

if(!$fotoRandom){
    $post = getImageSQL();
    $nameUserPost = getUserNamePost($post[0])[0];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>IMAGINEST - TU RED SOCIAL</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="msapplication-tap-highlight" content="no">
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
            <li class="cambiarContrase??a">
                <?php
                    if (strpos($usuari, '@') !== false) {
                        echo "<a href='resetPasswordDentro.php?mail=$usuari'>
                        <i class='fa fa-unlock-alt fa-2x'></i>
                        <span class='nav-text'>
                            Cambiar contrase??a
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
                        Cerrar sesi??n
                    </span>
                </a>
            </li>
        </ul>
    </nav> 

    <div id="main-menu-mobile">
        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">??</a>
            <a href="myprofile.php"><?php echo "@$userName"; ?></a>
            <a href="home.php">Inicio</a>
            <a href="politicas.php">Pol??tica de privacidad</a>
            <a onclick="closeNav()" href="#" data-toggle="modal" data-target="#exampleModal">Subir fotografia</a>
            <?php
                if (strpos($usuari, '@') !== false) {
                    echo "<a href='resetPasswordDentro.php?mail=$usuari'>
                    <span class='nav-text'>
                        Cambiar contrase??a
                    </span>
                    </a>";
                }
            ?>
            <a href="politicas.php">Pol??tica de privacidad</a>
            <a href="faq.php">Preguntas frecuentes</a>
            <a href="logout.php">Cerrar sesi??n</a>
        </div>

        <div id="main">
            <button class="openbtn" onclick="openNav()">IMAGINEST???</button>
        </div>
    </div>
    
    <section class="container">
        <h1 class="tituloapp" style="text-align: center;font-family: 'Titillium Web', sans-serif; padding: 0;font-weight: bold;">IMAGINEST</h1>

        <div class="row active-with-click" style="padding: 0px !important; justify-content: center">
            <!-- <div class="col-lg-1">
                <a class="fa fa-fw fa-chevron-left" id="pasarFotoLeft" ></a>
            </div> -->
            <div class="col-lg-7 col-md-11 col-sm-12 col-xs-12">
                <article class="material-card Green">
                    <h2 style="display: inline-flex;">
                        <span>
                            <?php 
                                
                                echo "Publicado por @$nameUserPost" 
                            ?>
                        </span>
                        <!-- <img src="./img/normal.png" style="width: 40px;  margin-left: 10px"> -->
                    </h2>
                    <div class="mc-content">
                        <div class="img-container" style="background-color: white; border-top-left-radius:30px; border-top-right-radius:30px">                            
                            <?php
                                require_once './db/controlUsuari.php';
                                $nombreFoto = $post[0]; 
                                $descripcionFoto = $post[1];
                                ?>
                                <img class="img-responsive" src="./fotosPublicadas/<?php echo $nombreFoto?>.png" style="border-top-left-radius: 10px; border-top-right-radius: 10px; height: 100%; width: 100%; object-fit: contain">
                            <!-- *********** -->
                        </div>
                        <div class="mc-description">
                            <p><?php echo $descripcionFoto ?></p>
                        </div>
                    </div>
                    <a class="mc-btn-action">
                        <i class="fa fa-bars" style="padding-top: 10px"></i>
                    </a>
                    <div class="mc-footer">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <button class="fa fa-fw fa-thumbs-up" name="like" id="likes" type="submit">&nbsp<?php echo $likes = contarLikes($nombreFoto)[0];?></button>
                            <input type="hidden" name="namePhotoOculto" value="<?php echo $nombreFoto?>">
                        </form>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <button class="fa fa-fw fa-thumbs-down" name="dislike" id="dislikes">&nbsp<?php echo $dislikes = contarDislikes($nombreFoto)[0];?></button>   
                            <input type="hidden" name="namePhotoOculto" value="<?php echo $nombreFoto?>">
                        </form>
                    </div>  
                </article>
            </div>   
            <!-- <div class="col-lg-1">
                <a class="fa fa-fw fa-chevron-right" id="pasarFotoRight"></a>
            </div> -->
        </div>
    </section>

    <!-- MODAL SUBIR FOTO -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content fondoModalFoto">
                <div class="modal-header">
                    <div class="centerText">
                        <h4 class="modal-title ModalLabelTama??o" id="exampleModalLabel">Crear publicaci??n</h4>
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
                            <textarea maxlength="200" class="form-control" name="descripcionFoto" id="exampleFormControlTextarea1" placeholder="??En que est?? pensando, <?php echo $userName?>?" rows="3" style="background-color: #242526; color: #C4C6CA; border: gray 1px solid"></textarea>
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

    <!-- <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> -->
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