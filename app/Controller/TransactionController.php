<?php
    require "../Model/Caisse.php";
    require_once '../../../../main.inc.php';
    $caisse = new Caisse($db);

    // DEPOT ET RETRAIT
    $entrant = isset($_POST['depot']) ? 1 : 0;
    $data = array(
        "motif" => htmlspecialchars($_POST['motif']),
        "valeur" => htmlspecialchars($_POST['valeur']),
        "entrant" => htmlspecialchars($entrant)
    );

    //update
    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $transaction = $caisse->find($id);
        $sign = $transaction->entrant ? 1 : -1;
        // echo $caisse->getEarningVr() - $transaction->valeur*$sign + $_POST['valeur']*$sign;
        // die();
        if(($caisse->getEarningVr() - $transaction->valeur*$sign + $_POST['valeur']*$sign) >= 0){
            $caisse->update(array(
                'motif' => $_POST['motif'],
                'valeur' => $_POST['valeur'], 
                'id' => $_POST['id']
            ));
            
            $_SESSION['message'] = ['success','Opération réussie : ' ,'Transaction mis à jour !'];
        }
        else{
            $_SESSION['message'] = ['danger','Echec de l\'operaction : ' ,'Solde Inssufisant !'];
        }
    }


    // //retrait
    elseif(!$entrant){
        if(($caisse->getEarningVr() - $_POST['valeur'] ) >= 0){
            $caisse->store($data);
            $_SESSION['message'] = ['success','Transcation réussie:','retrait enregistré !'];
        }
        else
        $_SESSION['message'] = ['danger','Echec de la transcation :','Solde Inssufisant !'];
    }

    // //Depot
    else{
        $caisse->store($data);
        $_SESSION['message'] = ['success','Transaction réussie :','Depot enregistré !'];
    }
    
    header('location:'.$_SERVER['HTTP_REFERER']);
