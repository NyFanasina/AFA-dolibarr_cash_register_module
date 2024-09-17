<?php
    require "../Model/Caisse.php";
    require_once '../../../../main.inc.php';
    $caisse = new Caisse($db);
    
    if(isset($_POST['filter'])){
        $motif = htmlspecialchars($_POST['motif']);
        $date1 = htmlspecialchars($_POST['date1']);
        $date2 = htmlspecialchars($_POST['date2']);
        $type = htmlspecialchars($_POST['type']);
        $_SESSION['caisse@search'] = $caisse->searchForCaisseAnnulee($type,$motif,$date1,$date2);
    }
    header('location:'.$_SERVER['HTTP_REFERER']);