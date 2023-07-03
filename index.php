<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <title>Denné menu</title>
    <style>
        h1{
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
<h1>DENNÉ MENU VENZA, DELIKANTI, EAT AND MEET</h1>
<div class="container-md">
        <div class="accordion-item">
            <div style="text-align: center;">
                <h2 class="accordion-header" id="headingOne">Pondelok</h2>
            </div>
            <div id="monday-body" class="accordion-body">

            </div> 
        </div>
        <div class="accordion-item">
            <div style="text-align: center;">
                <h2 class="accordion-header" id="headingTwo">Utorok</h2>
            </div>
           
                <div id="tuesday-body" class="accordion-body">

                </div>
        </div>
        <div class="accordion-item">
            <div style="text-align: center;">
                <h2 class="accordion-header" id="headingThree"> Streda</h2>
            </div>
            
                <div id="wednesday-body" class="accordion-body">

                </div>
        </div>
        <div class="accordion-item">
            <div style="text-align: center;">
                <h2 class="accordion-header" id="headingFour"> Štvrtok </h2>
            </div>
            
                <div id="thursday-body" class="accordion-body">

                </div>
        </div>
        <div class="accordion-item">
            <div style="text-align: center;">
                <h2 class="accordion-header" id="headingFive"> Piatok</h2>
            </div>
            <div id="friday-body" class="accordion-body">

            </div>
        </div>
</div>
</body>
<script>

    var monday_block = document.getElementById('monday-body');
    var tuesday_block = document.getElementById('tuesday-body');
    var wednesday_block = document.getElementById('wednesday-body');
    var thursday_block = document.getElementById('thursday-body');
    var friday_block = document.getElementById('friday-body');
    var monday = getData("Pondelok",monday_block);
    var tuesday = getData("Utorok",tuesday_block);
    var wednesday = getData("Streda",wednesday_block);
    var thursday = getData("Štvrtok",thursday_block);
    var friday = getData("Piatok",friday_block);

    async function getData(day,target) {
        const res = await axios.get('API.php',{params: {day: day}});
        data = res.data;

        table = document.createElement('table');
        table.setAttribute('class','table table-striped');
        thead = document.createElement('thead');
        nazov = document.createElement('th');
        nazov.innerHTML = 'Názov';
        cena = document.createElement('th');
        cena.innerHTML = 'Cena';
        rest = document.createElement('th');
        rest.innerHTML = 'Reštaurácia';
        r1 = document.createElement('tr');
        r1.appendChild(nazov);
        r1.appendChild(rest);
        r1.appendChild(cena);
        thead.appendChild(r1);
        table.appendChild(thead);
        tbody = document.createElement('tbody');

        var row = [];
        var name = [];
        var price = [];
        var restaurant = [];

        for (let i = 0; i < data.length; i++) {
            row[i] = document.createElement('tr');
            name[i] = document.createElement('td');
            restaurant[i] = document.createElement('td');
            price[i] = document.createElement('td');
            name[i].innerHTML = data[i].name;
            if (data[i].price==null){
                price[i].innerHTML = "";
            }else {
                price[i].innerHTML = data[i].price + "€";
            }
            restaurant[i].innerHTML = data[i].restaurant_name;

            row[i].appendChild(name[i]);
            row[i].appendChild(restaurant[i]);
            row[i].appendChild(price[i]);
            tbody.appendChild(row[i]);
        }
        table.appendChild(tbody);
        target.appendChild(table);
    }

</script>
</html>
