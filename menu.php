<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Týždenné menu</title>
    <style>
        h1{
            text-align: center;
        }
        #week-menu{
            text-align: center;
            font-size: 20px;
        }
        thead{
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
<h1>Týždenné menu</h1>
<div class="container-md">
    <table class="table table-striped">
        <thead>
        <tr><th>Deň</th><th>Názov jedla</th> <th>Jedáleň</th> <th>Cena</th></tr>
        </thead>
        <tbody id="week-menu">

        </tbody>
    </table>
</div>
    <script>
        var menu_table = document.getElementById('week-menu');
        getData(menu_table);



        async function getData(target) {
            const res = await axios.get('API.php');
            data = res.data;

            var row = [];
            var day = [];
            var name = [];
            var price = [];
            var restaurant = [];

            for (let i = 0; i < data.length; i++) {
                row[i] = document.createElement('tr');
                day[i] = document.createElement('td');
                day[i].innerHTML = data[i].day;
                name[i] = document.createElement('td');
                name[i].innerHTML = data[i].name;
                price[i] = document.createElement('td');
                if (data[i].price==null){
                    price[i].innerHTML = "";
                }else {
                    price[i].innerHTML = data[i].price + "€";
                }
                restaurant[i] = document.createElement('td');
                restaurant[i].innerHTML = data[i].restaurant_name;
                row[i].appendChild(day[i]);
                row[i].appendChild(name[i]);
                row[i].appendChild(restaurant[i]);
                row[i].appendChild(price[i]);
                target.appendChild(row[i]);
            }

        }
    </script>
</body>
</html>