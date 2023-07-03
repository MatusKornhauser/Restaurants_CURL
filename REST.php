<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <title>API</title>
    <style>
        h1{
            text-align: center;
        }
        .container-md{
            width: 700px;
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
<h1>Rest API</h1>
<div class="container-md">
    <p><b>GET:</b> <br>/restaurant/API.php <br><b> Vráti zoznam všetkých jedál v databáze </b><br> Výstup: JSON v tvare {‘id’: id, ‘name’: name, ‘price’: price, ‘day’: day, ‘restaurant_name’: restautant_name}
        <br>Návratová hodnota: 200, 404
    </p>
    <p>
    <b>GET:</b><br>/restaurant/API.php{‘day’: day} <br><b> Vráti zoznam jedál v daný deň </b><br> Vstup: day – String reprezentujúci deň v týždni {Pondelok, Utorok,…}
        <br> Výstup: JSON v tvare {‘name’: name, ‘price’: price, ‘restaurant_name’: restautant_name} <br>
        Návratová hodnota:	200, 400
    </p>
    <p>
    <b>GET:</b> <br>/restaurant/API.php{‘restaurant’: restaurant} 
    <br><b>Vráti reštaurácie</b>
        <br>Vstup: restaurant – String reprezentujúci názov reštaurácie
        <br>Výstup: JSON v tvare {‘id’: id, ‘name’: name}
        <br>Návratová hodnota: 200, 400
    </p>
    <p>
    <b>POST:</b> <br>/restaurant/API.php
	<br><b>Pridá jedlo danej reštaurácie na celý týždeň</b>
	<br>Vstup – dáta vo formáte data-form (restaurant_id, name, price)
	<br>Návratová hodnota: 201, 404
    </p>
    <p>
    <b>PUT:</b> <br>/restaurant/API.php
	<br><b>Upraví cenu konkrétneho jedla</b>
	<br>Vstup – dáta vo formáte x-www-form-urlencoded (id, price)
	<br>Návratová hodnota: 200, 400
    </p>
    <p>
    <b>DELETE:</b> <br>/restaurant/API.php{‘id’: id}
	<br><b>Zmaže reštauráciu aj so všetkými jej jedlami</b>
	<br>Vstup – id – id reštaurácie
	<br>Návratová hodnota: 200, 400
    </p>
</div>

</body>
</html>