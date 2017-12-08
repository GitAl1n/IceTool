<?php
// appel fichiers netatmo 
require_once("autoload.php");
require_once("Config.php");
require_once("Utils.php");


 // contient les informations de connexion, vous pouvez aussi 
//  les insérer directement ci-dessous en dur à la place des variables

$config = array("client_id" => $client_id,
              "client_secret" => $client_secret,
              "username" => $test_username,
              "password" => $test_password,
              "scope" => $scope);
              // à rajouter dans config et utiliser le bon scope

$client = new Netatmo\Clients\NAWSApiClient($config);
// Récupération du token
$tokens = $client->getAccessToken();
var_dump($tokens);

$access_token=$tokens["access_token"];
$refresh_token=$tokens["refresh_token"];
$expire_in=$tokens["expire_in"];

echo"<br>";
var_dump($access_token);
echo"<br>";
var_dump($refresh_token);
echo"<br>";
var_dump($expire_in);



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>exo_preparation_stage</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="css/style_perso.css" rel="stylesheet" type="text/css" />
     
    <script src="js/popper.js" type="text/javascript"></script> 
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>
    
    
</head>

<body class="fond">
    <div class="container">
        <div class="requete token">

        </div>
        <div class="formulaire">
            <h3>Formulaire de saisie affiché sur la page :</h3>
                <form method = "POST" action="donnees.php">
                    <label name="adresse"> entrez une adresse ou un lieu pour 
                    afficher la moyenne des températures actuelles de la zone
                    </label>
                    <!-- <input type="text" name="adresse" id="adresse"> -->
                    <input ng-model="inputText" 
                    ng-change="mapcontrol.autocomplete()"                 
                    placeholder="Rechercher" id="geocode">
                    <?php 
                    $lat_ne="";
                    $lat_sw="";
                    $lon_ne="";
                    $lon_sw="";
                    // lien requete get public data avec coordonnées hasard rentré dans une variable
                    $json="https://api.netatmo.com/api/getpublicdata?access_token={$access_token}&
                                        lat_ne=15&lon_ne=20&
                                        lat_sw=-15&lon_sw=-20&
                     
                                        filter=TRUE";
                    // mise sur lien avec adresse getdata à côté du boutton 
                    echo "
                    
                    <a href='{$json}'>page json</a>   
                    <button id='button' type='submit' > 
                    envoyer </button>" ;
                // mise encomment file_get (réponse false) pas encore debug (erreur 403 en local)
                //    $homepage = file_get_contents('$json');
                //     var_dump ($homepage);
                    ?>
                    
                </form>
        </div> <!--fin div formulaire-->


<!-- requête geocoding qui fonctionne pour gap
 https://maps.googleapis.com/maps/api/geocode/json?address=Gap&components=country:FR&key=AIzaSyAnfN-eXylokqfOyyMqcaAzNSK_ieV1rx4 -->
        <div class="resultats">
        <!-- dans les température et autre une fois qu'on aura réussi à récupérer les données
        sous forme json on les traitera et fera s'afficher ici grâce à des variables -->
        <p>Coordonnées GPS de la zone rentrée:</p>


        <p>Température moyenne : degré</p>
        <p>Altitude moyenne des stations : </p>
        <p>Nombre de stations dans la zone : </p>

        </div>

    </div> <!-- fin div container-->



</body>
</html>