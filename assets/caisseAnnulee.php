<?php

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--; $j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) {
	$res = @include "../main.inc.php";
}
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

// Load translation files required by the page
$langs->loadLangs(array("caisse@caisse"));

$action = GETPOST('action', 'aZ09');


// Security check
// if (! $user->rights->caisse->myobject->read) {
// 	accessforbidden();
// }
$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0) {
	$action = '';
	$socid = $user->socid;
}

$max = 5;
$now = dol_now();


/*
 * Actions
 */

// None


/*
 * View
 */

llxHeader("", $langs->trans("CaisseArea"));

print load_fiche_titre($langs->trans("Caisse Annulée(s)"), '', 'caisse.png@caisse');

require "../app/Model/Caisse.php";
$caisse=new Caisse($db);
$transactions=$caisse->indexAnnule();
$_action = "../app/Controller/CaisseAnnuleeController.php";
?>
<script src="./js/findAjax.js" defer></script>
<link rel="stylesheet" href="./css/bootstrap.min.css">
<?php include "./message.php"; ?>
<hr>
<div class=" d-flex justify-content-end">
    <div id="searchfield">
        <form action="<?=$_action?>" method="post" class="p-1">    
            <i class="fas fa-filter"></i>
            <input type="text" placeholder="Motif" name="motif">
            <select name="type" id="TypeDearch">
                <option value="2">Type</option>
                <option value="1">Dépôt</option>
                <option value="0">Retrait</option>
            </select>

            <label for="date1">du :</label>
            <input type="date" id="date1" name="date1">
    
            <label for="date2">au :</label>
            <input type="date" id="date2" name="date2" >
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                
            <button type="submit" class="btn text-white" name="filter" style="background: var(--butactionbg);">FILTRER</button>
        </form>
    </div>
</div>

    <form action="<?=$_action?>" method="post" id="editform">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
        <label for="motif">Motif : </label>
        <input type="text" id="motif" class="inputcaisse" required name="motif">
        <label for="somme">Somme : </label>
        <input type="number" id="somme" required name="valeur">
        <button type="submit" class="button" name="update">ENREGISTRER</button>
    </form>

    <div class="div-table-responsive-no-min">
       <table class="noborder centpercent">
            <tr class="liste_titre">
                <th class="wrapcolumntitle"><a href="" class="reposition">Réf. </a></th>
                <th class="wrapcolumntitle center"><a href="#" class="reposition"> Motif</a></th>
                <th class="wrapcolumntitle end pe-4"><a href="#" class="reposition"> Valeur</a></th>
                <th class="wrapcolumntitle center"><a href="#" class="reposition"> Date</a></th>
                <th class="wrapcolumntitle"><a href="#" class="reposition">Type</a></th>
                <th class="wrapcolumntitle center"><a href="#" class="reposition" align="center">Etat</a></th>
            </tr>
            <tr>
            <?php
                if(isset($_SESSION['caisse@search'])) 
                $transactions = $_SESSION['caisse@search'];
                foreach($transactions as $transaction): 
            ?>
                <td><?= $transaction->id ?></td>
                <td class="center"><?= $transaction->motif  ?></td>
                <td class="amount end"><?= number_format($transaction->valeur,2,","," ")." Ar" ?></td>
                <td class="center"><?= $transaction->date ?></td>
                <td><?= $transaction->entrant ? "dépot" : "retrait" ?></td>
                <td class="center py-3"><?= $transaction->valide ? "validé" : "brouillion" ?></td>
            </tr>
            <?php endforeach  ?>
       </table> 
    </div>
<?php
$NBMAX = $conf->global->MAIN_SIZE_SHORTLIST_LIMIT;
$max = $conf->global->MAIN_SIZE_SHORTLIST_LIMIT;


// End of pageHello
llxFooter();
$db->close();
unset($_SESSION['caisse@search']);
?>
<style>
    #editform{
        display: none;
        margin-left: 2%;
    }
    #motif, #somme{
        color: #4e4e4e;
    }

    #id-container {
        height: 92vh;
    }

    #searchfield{
        display: flex;
        background: #e5e5e5 ;
        padding: 4px 10px;
        border-radius: 5px;
        justify-content: space-around;
        margin-bottom: 5px;
    }
</style>