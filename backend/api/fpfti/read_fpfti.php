<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Fpfti.php';

    //Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate fpfti object
    $fpfti = new Fpfti($db);

    //query
    $result = $fpfti->read_fpfti($_GET['fpfti_id']);
    //get row count
    $num = $result->rowCount();

    //check if any fpfti
    if($num > 0) {
        //Post array
        $fpfti_arr = array();
        $fpfti_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $fpfti_item = array(
                'id' => $id,
                'title' => $title,
                'user_id' => $user_id,
                'link' => $link,
                'accepted' => $accepted,
                'likes' => $likes,
                'created' => $created
            );

            //Push to "data"
            array_push($fpfti_arr['data'], $fpfti_item);
        }

        //Turn to JSON & output
        echo json_encode($fpfti_arr);
    } else {
        echo json_encode(
            array('message' => 'No fpfti found')
        );
    }