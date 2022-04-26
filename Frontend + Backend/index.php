<?php
    session_start();
    include "./connessione.php";
    $b = 20;
    $a = $_GET["page"] * $b;
    

    $page=@$_GET["page"] ?? 0;
    $size=@$_GET["size"] ?? 20;
    $id = @$_GET["id"] ?? 0;
    $last = ceil($conta/$size) -1;

    //array del json
    $arrayJSON = array ();

    $arrayJSON['_embedded'] = array(
        "employees" => array(
            
        )
    );


    $arrayJSON['page']=array(
        "size"=> $size,
        "totalElements"=> $conta,
        "totalPages"=> $last,
        "number"=> $page

    );

    
    if(!isset($_SESSION["person"])){
        $_SESSION["person"] = '{"firstName":"Johnny","lastName":"Smitty","gender":"M"}';
    }

    $person = json_decode($_SESSION["person"], true);
    $method = $_SERVER["REQUEST_METHOD"];
    $fileJSON = file_get_contents("php://input");
    $data = json_decode($fileJSON, TRUE);

    switch($method){
        case 'GET':
            //curl localhost:8080
            if(isset($_GET['id'])){
                $query = "SELECT * FROM employees WHERE id = $_GET[id];";
                $result = mysqli_query($connessione, $query) or die("Query fallita " . mysqli_error($connessione) . " " . mysqli_errno($connessione));
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                } 
                $arrayJSON["_embedded"]["employees"] = $rows;
                echo json_encode($arrayJSON);
            }else{
                $query = "SELECT * FROM employees LIMIT $a, $b";
                $result = mysqli_query($connessione, $query) or die("Query fallita " . mysqli_error($connessione) . " " . mysqli_errno($connessione));
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                }
                $arrayJSON["_embedded"]["employees"] = $rows;
                echo json_encode($arrayJSON);
            }
            

            break;

        case 'POST':
            //curl -X POST -H "Content-Type: application/json" -d "{\"firstName\":\"John\",\"lastName\":\"Smith\",\"gender\":\"M\"}" localhost:8080
            $data = json_decode(file_get_contents('php://input'), true);
            $query = "INSERT INTO employees (first_name, last_name, gender) VALUES ('$data[firstName]', '$data[lastName]', '$data[gender]');";
            $result = mysqli_query ($connessione, $query) or die ("Query fallita " . mysqli_error($connessione) . " " . mysqli_errno($connessione));
            echo json_encode($data);
            break;
<<<<<<< HEAD
            echo "Aggiunto con successo";
=======
>>>>>>> 08be2c1161759b9320b6ee966b59e9e04961256f

        case 'PUT':
            //PUT: curl -X PUT -H "Content-Type: application/json" -d "{\"id\":\"10003\",\"firstName\":\"John\",\"lastName\":\"Smith\",\"gender\":\"M\"}" localhost:8080
            $data = json_decode(file_get_contents('php://input'), true);
            $query =    "UPDATE employees 
                        SET first_name = '$data[firstName]', 
                            last_name = '$data[lastName]', 
                            gender = '$data[gender]'
                        WHERE id = '$data[id]'";
            $result = mysqli_query ($connessione, $query) or die ("Query fallita " . mysqli_error($connessione) . " " . mysqli_errno($connessione));
            echo json_encode($data);
            break;
    
        case 'DELETE':
            //curl -X DELETE -H "Content-Type: application/json" -d "{\"id\":\"10003\"}" localhost:8080
            $data = json_decode(file_get_contents('php://input'), true);
            $query = "DELETE FROM employees WHERE id = '$data[id]'";
            $result = mysqli_query ($connessione, $query) or die ("Query fallita " . mysqli_error($connessione) . " " . mysqli_errno($connessione));
            if(($key = array_search('id: '. $id, $arrayJSON)) !== false){
                unset($arrayJSON[$key]);
            }

            echo json_encode($arrayJSON);
            break;
            break;

        default:
            header("HTTP/1.1 400 BAD REQUEST");
            break;
    }
