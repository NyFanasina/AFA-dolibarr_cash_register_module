<?php
    require "../Model/Caisse.php";
    require_once '../../../../main.inc.php';
    $caisse = new Caisse($db);

    if(isset($_POST['valideOne'])){
        $id = $_POST['valideOne'];
        $transaction = $caisse->find($id);

        if($transaction->entrant == 1) {     //depot   
            $caisse->validate($id);
            $_SESSION['message'] = ['success','Operatoin réussie : ' ,'Depot &#8470; '.$id.' validé !'];
        }
        elseif(($caisse->getEarningVr() - $transaction->valeur) >= 0){
            $caisse->validate($id);
            $_SESSION['message'] = ['success','Operatoin réussie : ' ,'Retrait &#8470;'.$id.' validé !'];
        }
        else{
            $_SESSION['message'] = ['danger','Echec de l\'opération : ' ,' solde inssufisante'];
        }
    }
    elseif(isset($_POST['delete'])){
        $id = $_POST['delete'];
        $entrant = $_POST['entrant'];
        
        // suppression de depot 
        if($entrant){
            if(($caisse->getEarningVr() - $caisse->find($id)->valeur) >= 0){
                $caisse->delete($id);
                $_SESSION['message'] = ['success','Suppression réussie : ' ,'dépôt supprimé !'];
            }
            else
                $_SESSION['message'] = ['danger','Echec de la suppression : ' ,'Solde Inssufisant !'];
        }
        else{
            $caisse->delete($id);
            $_SESSION['message'] = ['success','Suppression réussie : ' ,'retrait supprimé !'];
        }
    }
    elseif(isset($_POST['update'])){
        $caisse->update(array(
            'motif' => $_POST['motif'],
            'valeur' => $_POST['valeur'], 
            'id' => $_POST['update']
        ));
    }
    elseif(isset($_POST['filter'])){
        $motif = htmlspecialchars($_POST['motif']);
        $date1 = htmlspecialchars($_POST['date1']);
        $date2 = htmlspecialchars($_POST['date2']);
        $type = htmlspecialchars($_POST['type']);
        $_SESSION['caisse@search'] = $caisse->searchForBrouillion($type,$motif,$date1,$date2);
    }
    
    header('location:'.$_SERVER['HTTP_REFERER']);