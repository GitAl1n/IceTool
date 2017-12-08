<?php
// appel ficchier netatmo 
require_once("Netatmo-API-PHP-master/src/Netatmo/autoload.php");

use Netatmo\Clients\NAWSApiClient;
use Netatmo\Exceptions\NAClientException;

// configuration avec clé api et code client
$config = array();
$config['client_id'] = '5a2551d713475db8b88bcb3a';
$config['client_secret'] = 'comRVQgpmwMPFH1TDf9h0PEfOPsAMH';
//application will have access to station and theromstat
$config['scope'] = 'read_station';

$client = new Netatmo\Clients\NAWSApiClient($config);


// pour avoir token avec un user "credential grant"
$username = 'emilie.robert05@gmail.com';
$pwd = ',netatmo,gavary33';
// $client_id=['5a2551d713475db8b88bcb3a'];
// $client_secret=['comRVQgpmwMPFH1TDf9h0PEfOPsAMH'];
$client->setVariable('username', $username);
$client->setVariable('password', $pwd);



//pour avoir un token avec authorization code grant
//test if "code" is provided in get parameters 
// (which would mean that user has already accepted 
// the app and has been redirected here)
if(isset($_GET['code']))
{
    try
    {
       $tokens = $client->getAccessToken();
       $refresh_token = $tokens['refresh_token'];
       $access_token = $tokens['access_token'];
    }
    catch(Netatmo\Exceptions\NAClientException $ex)
    {
       echo " An error occured while trying to retrieve your tokens \n";
    }
}
else if(isset($_GET['error']))
{
    if($_GET['error'] === 'access_denied')
        echo "You refused that this application access your Netatmo Data";
    else echo "An error occured \n";
}
else
{
    //redirect to Netatmo Authorize URL
    // ici on a réussi à atterrir sur cet page mais à force de 
    // trifouiller dans le code je n'arrive plus à y retourner
    // je pense que c'est juste parce que on rempli une condition autre 
    $redirect_url = $client->getAuthorizeUrl();
    header("HTTP/1.1 ". OAUTH2_HTTP_FOUND);
    header("Location: ". $redirect_url);
    die();
}



// pour avoir données station netatmo (j'ai essayé de remplacer getData 
// par getPublicData mais c'est une method)
$data = $client->getData(NULL, TRUE);
foreach($data['devices'] as $device)
{
    echo $device['station_name'] . "\n";
    print_r($device['dashboard_data']);
    foreach($device['modules'] as $module)
    {
        echo $module['module_name'];
        print_r($module['dashboard_data']);
    }
}
// requête google maps geocoding output format peut être remplacé par json ou xml
// https://maps.googleapis.com/maps/api/geocode/outputFormat?parameters
// https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=YOUR_API_KEY
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

<body>
    <div class="container">
        <div class="requete token">

<!-- // Getpublicdata 
GET /api/getpublicdata?access_token=[YOUR_TOKEN]
&lat_ne=[YOUR_LAT_NE]&lon_ne=[YOUR_LON_NE]
&lat_sw=[YOUR_LAT_SW]&lon_sw=[YOUR_LON_SW]
&filter=[TRUE_FALSE] HTTP/1.1
    Host:api.netatmo.com  -->

        </div>
        <div class="formulaire">
            <h3>Formulaire de saisie affiché sur la page :</h3>
                <form method = "GET" action="index.php">
                    <label name="adresse"> entrez une adresse ou un lieu pour 
                    afficher la moyenne des températures actuelles de la zone
                    </label>
                    <!-- <input type="text" name="adresse" id="adresse"> -->
                    <input ng-model="inputText" 
                    ng-change="mapcontrol.autocomplete()" 
                    class="search-text ng-pristine ng-untouched ng-valid" 
                    placeholder="Rechercher" id="geocode">
                    <button type="button"> envoyer </button>
                </form>
        </div> <!--fin div formulaire-->

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
