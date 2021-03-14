<?php
    $db = mysqli_connect("localhost", "root", "", "imaginest"); 

    //TABLE USUARIOS
    $usersDB = "CREATE TABLE IF NOT EXISTS users(
        iduser int(11) auto_increment not null,
        mail varchar(40),
        username varchar(16),
        passHash varchar(60),
        userFirstName varchar(60),
        userLastName varchar(120),
        creationDate datetime,
        lastSignIn datetime,
        removeDate datetime,
        active tinyint(1),
        activationDate datetime,
        activationCode char(64),
        resetPass tinyint(1),
        resetPassExpiry time,
        resetPassCode char(64),
        PRIMARY KEY(iduser)
    );";
    
    $create_usuarios_table = mysqli_query($db, $usersDB);

    //TABLE FOTOGRAFIAS
    $photographiesDB = "CREATE TABLE IF NOT EXISTS photographies(
        id int(11) auto_increment not null,
        hashedName varchar(64) not null,
        description varchar(200),
        publicationDate datetime,
        likes int(11),
        dislikes int(11),
        hashtagName varchar(200),
        postedBy varchar(16) not null,
        PRIMARY KEY(id)
    );";

    $create_photographies_table = mysqli_query($db, $photographiesDB);
    
    //TABLE HASHTAGS
    $hashtagsDB = "CREATE TABLE IF NOT EXISTS hashtags(
        name varchar(40),
        PRIMARY KEY(name)
    );";

    $create_hashtags_table = mysqli_query($db, $hashtagsDB);

    //TABLE Likes/Dislikes por Usuario

    $valorationFotoDB = "CREATE TABLE IF NOT EXISTS valorationphotographies(
        id int(11) auto_increment not null,
        nameUser varchar(16) not null,
        namePhoto varchar(64) not null,
        likeP tinyint(4) not null,
        dislikeP tinyint(4) not null,
        PRIMARY KEY(id)
    );";

    $create_valoration_table = mysqli_query($db, $valorationFotoDB);

