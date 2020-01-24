<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/bocata%20de%20puños?api_key=RGAPI-3d96a6a4-36cf-4bfc-b552-615b8a83a6be");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
curl_close($ch);

$res = json_decode($res);
$summonerID = $res->id;



    
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://euw1.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/".$summonerID."?api_key=RGAPI-3d96a6a4-36cf-4bfc-b552-615b8a83a6be");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
curl_close($ch);
$res2 = json_decode($res);

echo "gameStartTime: ".$res2->gameStartTime;
    

