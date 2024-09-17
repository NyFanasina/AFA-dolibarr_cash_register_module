<?php
    require "../Model/Caisse.php";
    require_once '../../../../main.inc.php';
    $caisse = new Caisse($db);

    if(isset($_POST['delete'])){
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
            $caisse->delete($_POST['delete']);
            $_SESSION['message'] = ['success','Suppression réussie : ' ,'retrait supprimé !'];
        }
        header('location:'.$_SERVER['HTTP_REFERER']);
    }
    elseif(isset($_POST['filter'])){
        $motif = htmlspecialchars($_POST['motif']);
        $date1 = htmlspecialchars($_POST['date1']);
        $date2 = htmlspecialchars($_POST['date2']);
        $type = htmlspecialchars($_POST['type']);
        $_SESSION['caisse@search'] = $caisse->searchForHistorique($type,$motif,$date1,$date2);
    }

    header('location:'.$_SERVER['HTTP_REFERER']);