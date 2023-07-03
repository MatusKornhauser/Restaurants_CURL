<?php
require_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo $e->getMessage();
}

function insert_meal($db,$restaurant_id,$name,$price,$day){
    $sql = "INSERT INTO meals (restaurant_id, name, price, day) VALUES (?,?,?,?)";
    $stmt = $db->prepare($sql);
    $success = $stmt->execute([$restaurant_id,$name,$price,$day]);
}

function download($db){

    $query = "select * from restaurant where (id=1) or (id=3) or (id=5)";
    $stmt = $db->query($query);
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($restaurants as $restaurant) {
        $curl = curl_init($restaurant['url']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $page = curl_exec($curl);
        $sql = "INSERT INTO html (restaurant_id, created_date, html) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([$restaurant['id'],date("Y-m-d"),$page]);
    }
}

$dom = new DomDocument();

function parse($db,$dom){

    $days = ['Pondelok','Utorok','Streda','Štvrtok','Piatok'];

    $query = "select html from html where (restaurant_id=1)";
    $stmt = $db->query($query);
    $page = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dom->loadHTML($page[0]['html']);

    for ($i = 0; $i < 5; $i++) {
        $parsed[$i] = $dom->getElementById('day_' . ($i + 1));
        $h5s = $parsed[$i]->getElementsByTagName('h5');
    
        $price_text = explode(" ", $h5s[2]->textContent);
        $price_text = explode(",", $price_text[0]);

        if (floatval($price_text[1])>10.0) {
            $price =  floatval($price_text[0]) + floatval($price_text[1]) / 100;
        }else{
            $price = floatval($price_text[0]) + floatval($price_text[1]) / 100;
        }
        

        insert_meal($db, 1, $h5s[1]->textContent, $price, $days[$i]);

        $price_text = explode(" ", $h5s[4]->textContent);
        $price_text = explode(",", $price_text[0]);
        if (floatval($price_text[1])>10.0) {
            $price = floatval($price_text[0]) + floatval($price_text[1]) / 100;
        }else{
            $price =floatval($price_text[0]) + floatval($price_text[1]) / 100;
        }

        insert_meal($db, 1, $h5s[3]->textContent, $price, $days[$i]);

        for ($j = 6; $j < count($h5s); $j = $j + 3) {
            $price_text = explode(" ", $h5s[$j + 1]->textContent);
            $price_text = explode(",", $price_text[0]);
            if (floatval($price_text[1])>10.0) {
                $price = floatval($price_text[0]) +floatval($price_text[1]) / 100;
            }else{
                $price = floatval($price_text[0]) +floatval($price_text[1]) / 100;
            }

            insert_meal($db, 1, $h5s[$j]->textContent, $price, $days[$i]);
        }

    }
    $query = "select html from html where (restaurant_id=5)";
    $stmt = $db->query($query);
    $page = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dom->loadHTML($page[0]['html']);
 
    $parsed = $dom->getElementsByTagName("td");
    for ($i = 1; $i < count($parsed) - 2; $i = $i + 3) {
        if (!strpos($parsed[$i]->textContent, "Polievka podľa dennej ponuky")) {
            $day = $days[intdiv($i, 24)];
            insert_meal($db, 5, trim($parsed[$i]->textContent), null, $day);
        }
    }

    $query = "select html from html where (restaurant_id=3)";
    $stmt = $db->query($query);
    $page = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dom->loadHTML($page[0]['html']);

    for ($i = 0; $i < 5; $i++) {
        $parsed = $dom->getElementById('day-' . ($i + 1));
        $paragraphs = $parsed->getElementsByTagName('p');
        $spans = $parsed->getElementsByTagName('span');

        for ($k = 0; $k < count($paragraphs); $k++) {
            $names[$k] = $paragraphs[$k]->textContent;
        }

        for ($j = 0; $j < count($spans); $j = $j + 3) {

            $price_text = explode(" ", $spans[$j]->textContent);
            $price_text = explode(",", $price_text[0]);
            if (floatval($price_text[1])>10.0) {
                $price = floatval($price_text[0]) + floatval($price_text[1]) / 100;
            }else{
                $price = floatval($price_text[0]) + floatval($price_text[1]) / 100;
            }
            $prices[intdiv($j, 3)] = $price;
        }

        for ($l = 0; $l < count($paragraphs); $l++) {
            insert_meal($db, 3, $names[$l], $prices[$l], $days[$i]);
        }

    }
    echo "<script>window.location.href='buttons.php';</script>";
}

function delete($db){

    $sql = "DELETE FROM meals";
    $stmt = $db->prepare($sql);
    $success = $stmt->execute();

    $sql = "DELETE FROM html";
    $stmt = $db->prepare($sql);
    $success = $stmt->execute();
}

function is_parsed($db): bool
{
    $query = "SELECT * FROM meals";
    $stmt = $db->query($query);
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($meals)){
        return false;
    }
    return true;
}

function is_downloaded($db): bool
{
    $query = "SELECT * FROM html";
    $stmt = $db->query($query);
    $htmls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($htmls)){
        return false;
    }
    return true;
}

$message = "";

if(array_key_exists('download', $_POST)) {
    if (!is_downloaded($db)) {
        download($db);
        $message = "Úspešne stiahnuté";
        echo "<script>window.location.href='buttons.php';</script>";
    }else{
        $message = "Obsah už bol stiahnutý";
    }
} else if(array_key_exists('parse', $_POST)) {
    if (!is_parsed($db)) {
        parse($db, $dom);
        $message = "Úspešne rozparsované";
        echo "<script>window.location.href='buttons.php';</script>";
    }else{
        $message = "Obsah už bol rozparsovaný";
    }
}else if(array_key_exists('delete', $_POST)) {
    delete($db);
    $message = "Úspešne vymazané";
    echo "<script>window.location.href='buttons.php';</script>";
}

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <title>Načítanie</title>
    <style>
        .container-md{
            text-align: center;
        }
        
        body {
            background: #a8d3c9;
            background: -webkit-linear-gradient(to right, #95cde7, #aab6ee);
            background: linear-gradient(to right, #a8d3c9, #aab6ee);
        } 
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark" aria-label="navbar">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Domov</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">Týždenné menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="buttons.php">Načítanie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apis_menu.php">Edit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="REST.php">API</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>
<div class="container-md">
        <?php echo $message ?>
        <div style="margin-top: 20px;"> 
            <form method="post">
                <input type="submit"
                        name="download"
                        class="btn btn-primary btn-lg"
                        value="Stiahni" />

                <input type="submit"
                        name="parse"
                        class="btn btn-primary btn-lg"
                        value="Rozparsuj" />

                <input type="submit"
                        name="delete"
                        class="btn btn-primary btn-lg"
                        value="Vymaž" />
            </form>
        </div>
</div>
</body>
</html>
