<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WCLOL</title>
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
    curl_setopt($ch, CURLOPT_URL, "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-name/$nombre?api_key=RGAPI-3d96a6a4-36cf-4bfc-b552-615b8a83a6be");
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
        curl_setopt($ch, CURLOPT_URL, "https://euw1.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/".$summonerID."?api_key=RGAPI-3d96a6a4-36cf-4bfc-b552-615b8a83a6be");
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

</body>
</html>