<?php 
    function darLike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');
        
        $existe = existeValoracionFoto($nombreFoto,$usuarioName);

        if($existe){
            $hayDislike = comprobarSiHayDislike($nombreFoto,$usuarioName);
            if($hayDislike) actualizarVotoLike($nombreFoto,$usuarioName);
        }else{
            añadirLike($nombreFoto,$usuarioName);
        }
    }

    function comprobarSiHayDislike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(dislikeP) FROM valorationphotographies WHERE namePhoto=? AND nameUser=?";
            $preparadaComprobarDislike = $db->prepare($sql);
            $preparadaComprobarDislike->execute(array($nombreFoto,$usuarioName));
            $resultado = $preparadaComprobarDislike->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return ($resultado[0]==1 ? true : false);
    }

    function comprobarSiHayLike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(likeP) FROM valorationphotographies WHERE namePhoto=? AND nameUser=?";
            $preparadaComprobarDislike = $db->prepare($sql);
            $preparadaComprobarDislike->execute(array($nombreFoto,$usuarioName));
            $resultado = $preparadaComprobarDislike->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return ($resultado[0]==1 ? true : false);
    }

    function actualizarVotoLike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "UPDATE valorationphotographies SET likeP=1, dislikeP=0 WHERE namePhoto=? AND nameUser=?";
            $preparadaAñadirLike = $db->prepare($sql);
            $preparadaAñadirLike->execute(array($nombreFoto,$usuarioName));
            $añadirLike = $db->query($sql);
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }
    
    function actualizarVotoDislike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "UPDATE valorationphotographies SET likeP=0, dislikeP=1 WHERE namePhoto=? AND nameUser=?";
            $preparadaAñadirLike = $db->prepare($sql);
            $preparadaAñadirLike->execute(array($nombreFoto,$usuarioName));
            $añadirLike = $db->query($sql);
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }   

    function darDislike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');
        
        $existe = existeValoracionFoto($nombreFoto,$usuarioName);

        if($existe){
            $hayLike = comprobarSiHayLike($nombreFoto,$usuarioName);
            if($hayLike) actualizarVotoDislike($nombreFoto,$usuarioName);
        }else{
            añadirDislike($nombreFoto,$usuarioName);
        }
    }

    function añadirLike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "INSERT INTO valorationphotographies(nameUser,namePhoto,likeP,dislikeP) values(?,?,1,0)";
            $preparadaAñadirLike = $db->prepare($sql);
            $preparadaAñadirLike->execute(array($usuarioName,$nombreFoto));
            $añadirLike = $db->query($sql);
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function añadirDislike($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "INSERT INTO valorationphotographies(nameUser,namePhoto,likeP,dislikeP) values(?,?,0,1)";
            $preparadaAñadiDislike = $db->prepare($sql);
            $preparadaAñadiDislike->execute(array($usuarioName,$nombreFoto));
            $añadirDislike = $db->query($sql);
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function existeValoracionFoto($nombreFoto,$usuarioName)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(namePhoto) FROM valorationphotographies WHERE namePhoto=? AND nameUser=?";
            $preparadaExisteValoracionFoto = $db->prepare($sql);
            $preparadaExisteValoracionFoto->execute(array($nombreFoto,$usuarioName));
            $resultado = $preparadaExisteValoracionFoto->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false);
    }    

    function contarLikes($nombreFoto)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(namePhoto) FROM valorationphotographies WHERE namePhoto=? and likeP=1";
            $preparadaContarLikes = $db->prepare($sql);
            $preparadaContarLikes->execute(array($nombreFoto));
            $resultado = $preparadaContarLikes->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado);
    }

    function contarDislikes($nombreFoto)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(namePhoto) FROM valorationphotographies WHERE namePhoto=? and dislikeP=1";
            $preparadaContarDislikes = $db->prepare($sql);
            $preparadaContarDislikes->execute(array($nombreFoto));
            $resultado = $preparadaContarDislikes->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado);
    }
