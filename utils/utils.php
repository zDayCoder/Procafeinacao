<?php
function error($attb1, $attb2, $attb3){
    $atributos = array(
            'attb1' => $attb1,
            'attb2' => $attb2,
            'attb3' => $attb3,
        );
        $jsonData = json_encode($atributos);
        $expiringTime = time() + 10;
        setcookie('hidden_message', $jsonData, $expiringTime);
        header("Location: error.php");
        exit;
}