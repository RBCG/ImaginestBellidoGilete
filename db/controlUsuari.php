<?php
    function verificaUsuari($user)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(username) FROM users WHERE (username = ? OR mail= ?) AND active = ?";
            $preparadaVerificaUsuario = $db->prepare($sql);
            $preparadaVerificaUsuario->execute(array($user,$user,1));
            $resultado = $preparadaVerificaUsuario->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false);
    }

    function getHashPass($user)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "SELECT passHash FROM users WHERE username = ? OR mail = ?";
            $preparadaGetHash = $db->prepare($sql);
            $preparadaGetHash->execute(array($user,$user));
            $resultado = $preparadaGetHash->fetch();
            actualizarLastSignIn($user);
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado);   
    }

    function actualizarLastSignIn($user)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "UPDATE users SET lastSignIn = CURRENT_TIMESTAMP WHERE username = ? or mail = ?";
            $preparadaActualizarLast = $db->prepare($sql);
            $preparadaActualizarLast->execute(array($user,$user));
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function registrarUsuari($user, $email, $firstName, $lastName, $passHash)
    {
        require('connecta_db_persistent.php');
        try{
            $activationCode = hash('sha256',rand());
            $sql = "INSERT INTO users(mail, username, userFirstName, userLastName, passHash, creationDate, active, activationCode) values (?,?,?,?,?,CURRENT_TIMESTAMP,?,?)";
            $preparadaRegistrar = $db->prepare($sql);
            $preparadaRegistrar->execute(array($email,$user,$firstName,$lastName,$passHash,0,$activationCode));
            $existeix = $db->query($sql);
        } catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function existeixUsername($user)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "SELECT count(username) FROM users WHERE username=?";
            $preparadaExisteixUser = $db->prepare($sql);
            $preparadaExisteixUser->execute(array($user));
            $resultado = $preparadaExisteixUser->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false);
    }

    function existeixEmail($email)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "SELECT count(username) FROM users WHERE mail=?";
            $preparadaExisteixMail = $db->prepare($sql);
            $preparadaExisteixMail->execute(array($email));
            $resultado = $preparadaExisteixMail->fetch();

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false);
    }
    
    function getActivationCode($user)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "SELECT activationCode FROM users WHERE username=? OR mail=?";
            $preparadaGetActCode = $db->prepare($sql);
            $preparadaGetActCode->execute(array($user,$user));
            $resultado = $preparadaGetActCode->fetch();

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado); 
    }

    function verificarCodeMail($activationCode, $mail)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(activationCode) FROM users WHERE mail=? AND activationCode=?";
            $preparadaVerificarCode = $db->prepare($sql);
            $preparadaVerificarCode->execute(array($mail,$activationCode));
            $resultado = $preparadaVerificarCode->fetch();
            
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false); 
    }

    function activarCompteUsuari($mail)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "UPDATE users SET active = 1, activationDate = CURRENT_TIMESTAMP, activationCode = null WHERE mail=? AND active = ?";
            $preparadaActivar = $db->prepare($sql);
            $preparadaActivar->execute(array($mail,0));

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function solicitarCambiarContrasena($email)
    {
        require('connecta_db_persistent.php');
        try{
            $resetCode = hash('sha256',rand());
            $sql = "UPDATE users SET resetPass = ?, resetPassCode = ?, resetPassExpiry = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 30 MINUTE) WHERE mail=?";
            $preparadaSolicitar = $db->prepare($sql);
            $preparadaSolicitar->execute(array(1,$resetCode,$email));

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function getPassCode($mail)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "SELECT resetPassCode FROM users WHERE mail=?";
            $preparadaGetPassCode = $db->prepare($sql);
            $preparadaGetPassCode->execute(array($mail));
            $resultado = $preparadaGetPassCode->fetch();

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado); 
    }

    function verificarCodePassMail($passCode, $mail)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(resetPassCode) FROM users WHERE mail=? AND resetPassCode=?";
            $preparadaVerificarCode = $db->prepare($sql);
            $preparadaVerificarCode->execute(array($mail,$passCode));
            $resultado = $preparadaVerificarCode->fetch();
            
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false); 
    }

    function actualizarContraseña($pass,$mail)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "UPDATE users SET passHash = ?, resetPassCode = ?, resetPass = ?, resetPassExpiry = ? WHERE mail=?";
            $preparadaActPass = $db->prepare($sql);
            $preparadaActPass->execute(array($pass,null,0,null,$mail));

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function abortarResetPass($mail)
    {
        require('connecta_db_persistent.php');
        try{
            $sql = "UPDATE users SET resetPass = ?, resetPassCode = ?, resetPassExpiry = ? WHERE mail=?";
            $preparadaActPass = $db->prepare($sql);
            $preparadaActPass->execute(array(null,null,null,$mail));

        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function getPassExpiry($mail)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT count(resetPassExpiry) FROM users WHERE mail=? AND resetPassExpiry > CURRENT_TIMESTAMP";
            $preparadaVerificarCode = $db->prepare($sql);
            $preparadaVerificarCode->execute(array($mail));
            $resultado = $preparadaVerificarCode->fetch();
            
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado[0]==1 ? true : false); 
    }

    function getUsername($mail)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT username FROM users WHERE mail=?";
            $preparadaVerificarCode = $db->prepare($sql);
            $preparadaVerificarCode->execute(array($mail));
            $resultado = $preparadaVerificarCode->fetch();
            
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado); 
    }

    function getMail($user)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT mail FROM users WHERE username=?";
            $preparadaVerificarCode = $db->prepare($sql);
            $preparadaVerificarCode->execute(array($user));
            $resultado = $preparadaVerificarCode->fetch();
            
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return ($resultado); 
    }

    function getNameUserWithNameOrMail($nameOrMail){
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT username from users WHERE username=? or mail=?";
            $preparadaGetUserName = $db->prepare($sql);
            $preparadaGetUserName-> bindParam(1,$nameOrMail,PDO::PARAM_STR);
            $preparadaGetUserName-> bindParam(2,$nameOrMail,PDO::PARAM_STR);
            $preparadaGetUserName->execute();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        
        return $preparadaGetUserName->fetchColumn(); 
    }

    function getImageSQL()
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT hashedName,description FROM photographies WHERE publicationDate = (select MAX(publicationDate) from photographies)";
            $preparadaNameImageSQL = $db->prepare($sql);
            $preparadaNameImageSQL-> execute();
            $infoFoto = $preparadaNameImageSQL->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $infoFoto;
    }

    function getImageSQLRandom($fotoAnterior)
    {
        require('connecta_db_persistent.php');
        
        try{
            do{
                $sql = "SELECT hashedName,description FROM photographies ORDER BY rand() LIMIT 1";
                $preparadaNameImageSQLRandom = $db->prepare($sql);
                $preparadaNameImageSQLRandom-> execute();
                $infoFotoRandom = $preparadaNameImageSQLRandom->fetch();
            }while($infoFotoRandom[0]==$fotoAnterior);
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $infoFotoRandom;
    }

    function getUserNamePost($nameFoto)
    {
        require('connecta_db_persistent.php');

        try{
            $sql = "SELECT postedBy FROM photographies WHERE hashedName = ?";
            $preparadaNameUsuarioImageSQL = $db->prepare($sql);
            $preparadaNameUsuarioImageSQL-> execute(array($nameFoto));
            $nameUsuarioFoto = $preparadaNameUsuarioImageSQL->fetch();
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $nameUsuarioFoto;
    }