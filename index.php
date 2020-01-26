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
    $API_KEY = "RGAPI-f4d1f9d1-75d9-406c-9797-fdaf30deca55";
    $nombre = str_replace(" ","%20",$_REQUEST['nombreInvocador']);
    $server = $_REQUEST['server'];

  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-name/$nombre?api_key=$API_KEY");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($res);

    if(isset($res->status)){
        if($res->status->status_code == "404"){
            echo "<h1>No existe ese man<h1>";
        }
    }else{
        
        $summonerID = $res->id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://euw1.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/".$summonerID."?api_key=$API_KEY");
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
        }



    }
    }
   

  


   
    
?>

</body>
</html>