<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <title>Formulár</title>
    <style>
        h1{
            text-align: center;
        }
        .container-md{
            width: 500px;
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
<h1>Formulár na testovanie</h1>
<div class="container-md">
    <div class="content">
        <div class="pageElement">
            <h3>Pridať jedlo</h3>
            <form class="formEdit" method="post" onsubmit="addMeal()">
                <label>
                    <select class="form-select" id="restaurant_id" name="restaurant_id">

                    </select>
                </label>
                <div class="mb-3">
                    <label for="InputName" class="form-label">Názov</label>
                    <input type="text" name="name" class="form-control" id="InputName">
                </div>
                <div class="mb-3">
                    <label for="InputPrice" class="form-label">Cena</label>
                    <input type="text" name="price" class="form-control" id="InputPrice">
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Vložiť</button>
            </form>
        </div>

        <div class="pageElement">
            <h3>Odstrániť jedáleň</h3>
            <form class="formEdit" method="get" onsubmit="deleteRestaurant()">
                <label>
                    <select class="form-select" id="restaurant_id_delete" name="restaurant_id">

                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary btn-lg">Odstrániť</button>
                </label>
            </form>
        </div>
        <div class="pageElement">
            <h3>Upraviť jedlo</h3>
            <form class="formEdit" method="post" onsubmit="updateMeal()">
                <label>
                    <select class="form-select" id="meal_id" name="meal_id">
                    </select>
                </label>
                <div class="mb-3">
                    <label for="InputPriceUpdate" class="form-label">Cena</label>
                    <input type="text" name="price" class="form-control" id="InputPriceUpdate">
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Upraviť</button>
            </form>
        </div>
    </div>
</div>

<script>

    makeMealsSelect(document.getElementById('meal_id'));
    makeRestaurantsSelect(document.getElementById('restaurant_id'));
    makeRestaurantsSelect(document.getElementById('restaurant_id_delete'));

    async function makeRestaurantsSelect(target){
        const res = await axios.get('API.php',{params: {restaurants: 'any'}});
        data = res.data;

        var option = [];

        for (let i = 0; i < data.length; i++) {
            option[i] = document.createElement('option');
            option[i].setAttribute('value', data[i].id);
            option[i].innerHTML = data[i].name;
            target.appendChild(option[i]);
        }
    }

    async function makeMealsSelect(target) {
        const res = await axios.get('API.php');
        data = res.data;

        var option = [];

        for (let i = 0; i < data.length; i++) {
            option[i] = document.createElement('option');
            option[i].setAttribute('value',data[i].id);
            if (data[i].price!=null) {
                option[i].innerHTML = data[i].name + " | " + data[i].restaurant_name + " | " + data[i].day + " | " + data[i].price + "€";
            }else {
                option[i].innerHTML = data[i].name + " | " + data[i].restaurant_name + " | " +  data[i].day + " | ";
            }
            target.appendChild(option[i]);
        }
    }


    function addMeal(){
        restaurant = document.getElementById('restaurant_id')
        name_input = document.getElementById('InputName');
        price = document.getElementById('InputPrice');
        postMeal(restaurant.value,name_input.value, price.value);
        return true;
    }

    async function postMeal(restaurant_id,name_input,price){
        await axios.post('API.php',{restaurant_id: restaurant_id,name: name_input, price: price}, {headers: {'Content-Type': 'multipart/form-data'}})
    }

    function deleteRestaurant(){
        restaurant = document.getElementById('restaurant_id_delete');
        deleteApiRestaurant(restaurant.value);
        return true;
    }

    async function deleteApiRestaurant(rest_id){
        await axios.delete('API.php',{params: {id: rest_id}});
    }

    function updateMeal(){
        restaurant = document.getElementById('meal_id');
        price = document.getElementById('InputPriceUpdate');
        putMeal(restaurant.value, parseFloat(price.value));
        return true;
    }

    async function putMeal(meal_id,meal_price){
        await axios.put('API.php', {id: meal_id, price: meal_price},{headers: {'Content-Type' : 'application/x-www-form-urlencoded'}});
    }
</script>
</body>
</html>