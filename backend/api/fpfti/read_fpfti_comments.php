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
    $fpfti_id = isset($_GET['fpfti_id']) ? $_GET['fpfti_id'] : die();
    $result = $fpfti->read_fpfti_comments($fpfti_id);
    //get row count
    $num = $result->rowCount();

    //check if any fpfti
    if($num > 0) {
        //Post array
        $comments_arr = array();
        $comments_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $comments_item = array(
                'id' => $id,
                'text' => $text,
                'user_id' => $user_id,
                'fpfti_id' => $fpfti_id,
                'created' => $created
            );

            //Push to "data"
            array_push($comments_arr['data'], $comments_item);
        }

        //Turn to JSON & output
        echo json_encode($comments_arr);
    } else {
        echo json_encode(
            array('message' => 'No comments found')
        );
    }