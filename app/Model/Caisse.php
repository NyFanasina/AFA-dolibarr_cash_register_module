<?php
    class Caisse {
        private $bd;

        public function __construct($bd)
        {
            $this->bd = $bd;
        }

        public function index(bool $validated){
            $transactions = [];
            $state = $validated ? 1 : 0;
            $sql = "SELECT * FROM `llx_caisse` WHERE valide='$state' AND existe=1 order by date DESC";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        public function indexAnnule(){
            $transactions = [];
            $sql = "SELECT * FROM `llx_caisse` WHERE existe=0 order by date DESC";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        public function store(array $data){
            $motif = $data['motif'];
            $valeur = $data['valeur'];
            $entrant = $data['entrant'];
            $this->bd->begin();
            $this->bd->query("INSERT INTO `llx_caisse`(`motif`,`valeur`,`entrant`,`date`) VALUES('$motif' , '$valeur', '$entrant',NOW())");
            $this->bd->commit();
        }
        // soft delete
        public function delete(string $id){
            $this->bd->begin();
            $this->bd->query("UPDATE `llx_caisse` SET existe=0 WHERE id = '$id'");
            $this->bd->commit();
        }

        public function restore(string $id){
            $this->bd->begin();
            $this->bd->query("UPDATE `llx_caisse` SET existe=1 WHERE id = '$id'");
            $this->bd->commit();
        }

        public function update(array $data){
            $motif = $data['motif'];
            $valeur = $data['valeur'];
            $id = $data['id'];
            $this->bd->begin();
            $this->bd->query("UPDATE `llx_caisse` SET motif='$motif', valeur='$valeur' WHERE id='$id'");
            $this->bd->commit();
        }

        public function lastInsertedRetrait(){
            $retraits = [];
            $resql = $this->bd->query("SELECT * FROM `llx_caisse` WHERE entrant=0 ORDER BY date DESC limit 10");
            while($retrait = $this->bd->fetch_object($resql)){
                $retraits[] = $retrait;
            }
            return $retraits;
        }

        public function lastInsertedDepot(){
            $retraits = [];
            $resql = $this->bd->query("SELECT * FROM `llx_caisse` WHERE entrant=1 ORDER BY date DESC limit 10");
            while($retrait = $this->bd->fetch_object($resql)){
                $retraits[] = $retrait;
            }
            return $retraits;
        }
        

        public function find($id){
            $resql = $this->bd->query("SELECT * FROM `llx_caisse` WHERE id='$id'");
            $transaction = $this->bd->fetch_object($resql);
            return $transaction;
        }

        public function findWithmotif($motif){
            
            die($_SESSION['test']);
            $transactions = [];
            $resql = $this->bd->query("SELECT * FROM `llx_caisse` WHERE motif LIKE '%$motif%'");
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        public function validate($id){
            $this->bd->begin();
            $this->bd->query("UPDATE `llx_caisse` SET valide=1 WHERE id = '$id'");
            $this->bd->commit();
        }
        
        public function searchForBrouillion($type,$motif,$date1, $date2){
            $transactions = [];
            $sql = $this->search($type,$motif,$date1, $date2);
            $sql .= " AND `valide`=0 ORDER BY id DESC";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        public function searchForCaisseAnnulee($type,$motif,$date1, $date2){
            $transactions = [];
            $sql = $this->search($type,$motif,$date1, $date2);
            $sql .= " AND `existe`=0 ORDER BY id DESC";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        public function searchForHistorique($type,$motif,$date1, $date2){
            $transactions = [];
            $sql = $this->search($type,$motif,$date1, $date2);
            $sql .= " AND `valide`=1 ORDER BY id DESC";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        private function search($type=0,$motif,$date1, $date2){
            $sql = "SELECT * FROM `llx_caisse` WHERE 1";
            if(!empty($motif)){
                $sql = "SELECT * FROM `llx_caisse` WHERE `motif` LIKE '%$motif%'";
            }
            if(!empty($date1)){
                $sql = "SELECT * FROM `llx_caisse` WHERE `motif` LIKE '%$motif%' AND DATE(date)='$date1'";
            }
            if(!empty($date1) AND !empty($date2)){
                $sql = "SELECT * FROM `llx_caisse` WHERE `motif` LIKE '%$motif%' AND date BETWEEN '$date1' AND '$date2'";
            }
            if($type <=1 && $type >= 0)
            $sql .= " AND `entrant` = '$type'";
            return $sql;
        }

        //virtuelle
        public function getEarningVr(){
            $depots = $retraits =0;
            $sql = "SELECT IFNULL(SUM(IF(entrant=1,valeur,-valeur)), 0) as total FROM `llx_caisse` WHERE  existe=1";
            $resql = $this->bd->query($sql);
            return $this->bd->fetch_object($resql)->total;
        }


        public function getEarning(){
            $depots = $retraits =0;
            $sql = "SELECT IFNULL(SUM(IF(entrant=1,valeur,-valeur)), 0) as total FROM `llx_caisse` WHERE valide=1 AND existe=1";
            $resql = $this->bd->query($sql);
            return $this->bd->fetch_object($resql)->total;
        }

        public function countTransaction(){
            $resql = $this->bd->query("SELECT entrant, COUNT(id) as nb FROM `llx_caisse` WHERE valide=1 AND existe=1 GROUP BY entrant");
            while($number = $this->bd->fetch_object($resql)){
                if($number->entrant == 1){
                    $numbers['depot'] = $number->nb;
                }
                elseif($number->entrant == 0){
                    $numbers['retrait'] = $number->nb;
                }
            }
            return $numbers ?? 0;
        }

        public function countTransactions(){
            $resql = $this->bd->query("SELECT COUNT(id) as nb FROM `llx_caisse` WHERE valide=1 AND existe=1");
            $res = $this->bd->fetch_object($resql);
            return $res->nb;
        }

        public function countTransactionsBrouillard(){
            $resql = $this->bd->query("SELECT COUNT(id) as nb FROM `llx_caisse` WHERE valide=0 AND existe=1");
            $res = $this->bd->fetch_object($resql);
            return $res->nb;
        }

        public function countTransactionsCancelled(){
            $resql = $this->bd->query("SELECT COUNT(id) as nb FROM `llx_caisse` WHERE valide=1 AND existe=0");
            $res = $this->bd->fetch_object($resql);
            return $res->nb;
        }

        public function pdf(){
            $transactions = [];
            $sql = "SELECT id,motif,valeur,IF(entrant, 'dépôt','retrait') entrant,IF(existe,'actif','supprimé') AS existe,DATE_FORMAT(date,'%d-%m-%Y') date FROM `llx_caisse` WHERE valide=1";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }

        public function chart(){
            $transactions = [];
            $sql = "SELECT SUM(IF(entrant=1,valeur,0)) depot, SUM(IF(entrant=0,valeur,0)) retrait, MONTH(date) month FROM `llx_caisse` WHERE valide=1 AND existe=1 AND MONTH(date) <= MONTH(NOW()) GROUP BY month";
            $resql = $this->bd->query($sql);
            while($transaction = $this->bd->fetch_object($resql)){
                $transactions[] = $transaction;
            }
            return $transactions;
        }
        

    }