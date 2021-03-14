<?php
    function nombreFotosPublicadasByUser($usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT hashedName FROM photographies where postedBy=? ORDER BY publicationDate DESC";
            $prepareHashedNameFotoUsu = $db->prepare($sql);
            $prepareHashedNameFotoUsu->execute(array($usuarioName)); 
            $resultado = $prepareHashedNameFotoUsu->fetchAll();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultado;
    }

    function obtenerDescripcionFoto($nombreFoto)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT description FROM photographies where hashedName=?";
            $prepareDescripcionFoto = $db->prepare($sql);
            $prepareDescripcionFoto->execute(array($nombreFoto)); 
            $resultado = $prepareDescripcionFoto->fetchAll();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultado=$resultado[0][0];
    }

    function muestraFotografia($userName,$fotoUsu,$contador,$final)
    {
        require_once("controlLikeDislike.php");
        $descripcion = obtenerDescripcionFoto($fotoUsu);
        ?>

            <?php if($contador==1) echo "<section class='container'><div class='row active-with-click'>"; ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <article class="material-card Green" style=" border-radius: 30px">
                    <h2>
                        <span><?php ?></span>
                    </h2>
                    <div class="mc-content" style="border-top: 5px solid #4caf50;border-left: 5px solid #4caf50;border-right: 5px solid #4caf50;">
                        <div class="img-container" style="background-color:white;">
                            <img class="img-responsive" src="../../imaginest/fotosPublicadas/<?php echo $fotoUsu; ?>.png" style="height: 100%; width: 100%; object-fit: contain">
                        </div>
                        <div class="mc-description">
                            <?php echo $descripcion;?>
                        </div>
                    </div>
                    <a class="mc-btn-action">
                        <i class="fa fa-bars" style="padding-top: 10px"></i>
                    </a>
                    <div class="mc-footer">
                        <button class="fa fa-fw fa-thumbs-up">&nbsp<?php echo $likes = contarLikes($fotoUsu)[0]?></button>
                        <button class="fa fa-fw fa-thumbs-down">&nbsp<?php echo $dislikes = contarDislikes($fotoUsu)[0];?></button>
                    </div>
                </article>
            </div>
            <!-- <?php if($final) echo "</div></div>"; ?> -->
        <?php       
    }