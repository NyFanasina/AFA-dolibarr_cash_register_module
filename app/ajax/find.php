<?php
    require "../Model/Caisse.php";
    require_once '../../../../main.inc.php';
    $caisse = new Caisse($db);

    $transaction = $caisse->find($_GET['id']);
    print(json_encode($transaction));