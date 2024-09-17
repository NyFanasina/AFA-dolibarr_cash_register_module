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

$form = new Form($db);
$formfile = new FormFile($db);

llxHeader("", $langs->trans("CaisseArea"));



print load_fiche_titre($langs->trans("Dépôt"), '', 'caisse.png@caisse');


   require "../app/Model/Caisse.php";
   $caisse=new Caisse($db);
   $depots=$caisse->lastInsertedDepot();

?>
<link rel="stylesheet" href="./css/bootstrap.min.css">
    <!-- Message d'alert -->
    <?php include "./message.php"; ?>
    <hr>
    <h2>Enregistrement de dépôt</h2>
    <hr>
    <div class="fichecenter">
        <form action="../app/Controller/TransactionController.php" method="POST">
            <table>
                <tr>
                    <td><label for="motif">Motif : </label></td>
                    <td><input type="text" id="motif" class="inputcaisse" required name="motif" minlength="4"></td>
                </tr>
                <tr>
                    <td><label for="somme">Somme (Ar): </label></td>
                    <td><input type="number" id="somme" required name="valeur" min="100"></td>
                </tr>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
            </table>
            <span><hr></span>
            <button type="reset" class="button">EFFACER</button> 
            <button type="submit" class="button" name="depot">ENREGISTRER</button>
        </form>
    </div>
     
    <br>

    <div class="div-table-responsive-no-min">
    <h4>Récemment enregistré: </h4>
        <table class="ntagtable liste" >
                <tr class="liste_titre">
                    <th class="wrapcolumntitle" align="left">    <a href="" class="reposition">Réf. </a></th>
                    <th class="wrapcolumntitle" align="center">  <a href="" class="reposition"> Motif</a></th>
                    <th class="wrapcolumntitle" align="center">  <a href="" class="reposition"> Somme</a></th>
                    <th class="wrapcolumntitle" align="center">  <a href="" class="reposition"> Date</a></th>
                    <th class="wrapcolumntitle" align="left">    <a href="" class="reposition"> </a></th>
                </tr>
                <tr>
                <?php foreach($depots as $depot): ?>
                    <td><?= $depot->id ?></td>
                    <td><?= $depot->motif  ?></td>
                    <td class="amount">
                        <?= number_format($depot->valeur, 2,","," ")." Ar"  ?>
                    </td>
                    <td><?=  $depot->date ?></td>
                    <td>
                        <a class="editfielda fs-5" href=""><i class="fas fa-pencil-alt" style=" color: #444;" title="Modifier"></i></a>
                    </td>
                </tr>  
                <?php endforeach ?>
        </table> 
    </div>


<?php
$NBMAX = $conf->global->MAIN_SIZE_SHORTLIST_LIMIT;
$max = $conf->global->MAIN_SIZE_SHORTLIST_LIMIT;


// End of pageHello
llxFooter();
$_SESSION['message'] = null;
$db->close();
?>
<style>
    #id-container {
        height: 92vh;
    }
    label{
        padding-right: 75px;
    }
    input[type=text]{
        width: 420px;
    }
    button { 
    background: #9b75a7!important;
    border-radius: 3px!important; 
    color: #fcfcfc!important;
    }
    table{
        padding: 10px 0px;
    }
</style>

<script>
    const alert = document.getElementById('alert');
    
    setTimeout(() => alert.style.display= 'none',10000);
</script>