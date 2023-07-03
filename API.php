<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once('config.php');
try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo $e->getMessage();
}

function getRestaurants(PDO $db){
    try{
        $query = "SELECT r.id, r.name from html r_html join restaurant r on r.id = r_html.restaurant_id";
        $stmt = $db->query($query);
        $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($restaurants);
        http_response_code(200);

    }catch(PDOException $e){
        echo $e->getMessage();
        http_response_code(400);
    }
}

function getMenuByDay(PDO $db, $day)
{
    $query = "SELECT m.name,m.price,r.name as 'restaurant_name' FROM meals m join restaurant r on r.id = m.restaurant_id WHERE m.day ='" . $day . "' order by m.price";
    $stmt = $db->query($query);
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($menu)) {
        echo json_encode($menu);
        http_response_code(200);
    }else{
        http_response_code(400);
    }
}

function getAllMeals(PDO $db)
{
    $query = "SELECT m.id, m.name, m.price, m.day,r.name as 'restaurant_name' FROM meals m join restaurant r on r.id = m.restaurant_id order by m.price";
    $stmt = $db->query($query);
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($meals)) {
        echo json_encode($meals);
        http_response_code(200);
    }else{
        http_response_code(404);
    }
}

function addMeal(PDO $db, $restaurant_id, $name, $price)
{
    $days = ['Pondelok','Utorok','Streda','Štvrtok','Piatok'];
    $status = true; 
    try{
        foreach ($days as $day) {
            $sql = "INSERT INTO meals (restaurant_id, name, price, day) VALUES (?,?,?,?)";
            $stmt = $db->prepare($sql);
            $success = $stmt->execute([$restaurant_id, $name, $price, $day]);
            $status = $status && $success;
        }
        http_response_code(201);

    }catch(PDOException $e){
        echo $e->getMessage();
        http_response_code(404);
    }

}

function update_meal(PDO $db, $id, $price)
{
    $query = "SELECT * FROM meals where id=" . $id;
    $stmt = $db->query($query);
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($meals)){
        http_response_code(400);
    }else {
        $stmt = $db->prepare('UPDATE meals SET price = :price WHERE id = :id;');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':price', $price);
        $success = $stmt->execute();
        if ($success) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }
}

function deleteRestaurantMenu(PDO $db, $id)
{
    $query = "SELECT * FROM html";
    $stmt = $db->query($query);
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $contains = false;
    foreach ($restaurants as $restaurant){
        if ($restaurant['restaurant_id']==$id){
            $contains = true;
        }
    }
    if ($contains) {
        $sql = "DELETE FROM meals WHERE restaurant_id =" . $id;
        $stmt = $db->prepare($sql);
        $success1 = $stmt->execute();
        $sql = "DELETE FROM html WHERE restaurant_id =" . $id;
        $stmt = $db->prepare($sql);
        $success2 = $stmt->execute();

        if ($success1 && $success2) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }else{
        http_response_code(400);
    }

}


switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['day'])){
            getMenuByDay($db,$_GET['day']);
        }elseif (isset($_GET['restaurants'])){
            getRestaurants($db);
        }
        else{
            getAllMeals($db);
        }
        break;
    case 'POST':
        addMeal($db,$_POST['restaurant_id'],$_POST['name'],$_POST['price']);
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);
        if(!empty($_PUT['id']) && !empty($_PUT['price'])) {
            update_meal($db,$_PUT['id'],$_PUT['price']);
        }

        break;
    case 'DELETE':
        deleteRestaurantMenu($db,$_GET['id']);
        break;
}


