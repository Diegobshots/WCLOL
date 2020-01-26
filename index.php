<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WCLOL</title>
    <link rel="stylesheet" href="style.css">
    <!-- Iconos -->
    <link rel="stylesheet" href="icons/css/solid.css">
    <link rel="stylesheet" href="icons/css/fontawesome.css">
    <link rel="stylesheet" href="icons/css/brands.css">
    <link rel="stylesheet" href="icons/css/regular.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


</head>
<body>
    

<?php
if(!isset($_REQUEST['nombreInvocador']) || $_REQUEST['nombreInvocador'] == "" ){
    include("formulario.php");
}else{

    //Transformamos el nombre de invocador a formato para URL espacios -> %20%

    $nombre = str_replace(" ","%20",$_REQUEST['nombreInvocador']);
    $server = $_REQUEST['server'];

  
    //Obtenemos el json que nos devuelve la api pasandole el nombre de invocador y servidor
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-name/$nombre?api_key=RGAPI-f4d1f9d1-75d9-406c-9797-fdaf30deca55");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);

    //Transformamos el archivo json a objeto PHP para manejar los datos
    $res = json_decode($res);

    //Validamos errores de conexion o falta de info por parte del servidor
    if(isset($res->status)){
        if($res->status->status_code == "404"){
            echo "<h1>No existe ese man<h1>";
        }
    }else{
        
        //hacemos una segunda consulta a la api, extrayendo de la primera el ID de invocador
        $summonerID = $res->id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://euw1.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/".$summonerID."?api_key=RGAPI-f4d1f9d1-75d9-406c-9797-fdaf30deca55");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res);

        if(isset($res->status)){
            if($res->status->status_code == "404"){
                echo "<h1>aún no maquinote<h1>";
            }elseif($res->status->status_code == "500") {
                echo "<h1>ayy por la raaaja que se ma matao mi serveer<h1>";
            }
        }else{
            

            $partida = $res->gameStartTime/1000;
            $fecha = date('m/d/Y H:i:s', $partida);
        
            echo "<h1>$fecha</h1>";
            //Hay que pillar la hora del sistema y restarle la hora a la que empezó la partida para pillar los segundos que lleva iniciada
        
        }



    }
    }
   

  


   
    
?>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>

</body>
</html>