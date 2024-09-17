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

print load_fiche_titre($langs->trans("Brouillard de caisse"), '', 'caisse.png@caisse');
require "../app/Model/Caisse.php";
$caisse=new Caisse($db);
$transactions=$caisse->index(false);
?>
<script src="./js/findAjax.js" defer></script>
<link rel="stylesheet" href="./css/bootstrap.min.css">
<script src="./js/bootstrap.bundle.min.js"></script>
<hr>
<!-- Message d'alert -->
<?php include "./message.php"; ?>
<?php include "./includes/modif.php"; ?>

<div class=" d-flex justify-content-end">
    <div id="searchfield">
        <form action="../app/Controller/BrouillionController.php" method="post" class="p-1">    
            <i class="fas fa-filter"></i>
            <input type="text" placeholder="Motif" name="motif">
            <select name="type" id="TypeDearch">
                <option value="3">Type</option>
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

    <div class="div-table-responsive-no-min">
       <table class="noborder centpercent" >
            <tr class="liste_titre">
                <th class="wrapcolumntitle"><a href="" class="reposition">Réf. </a></th>
                <th class="wrapcolumntitle center"><a href="" class="reposition"> Motif</a></th>
                <th class="wrapcolumntitle end pe-4"><a href="" class="reposition"> Valeur</a></th>
                <th class="wrapcolumntitle center"><a href="" class="reposition"> Date</a></th>
                <th class="wrapcolumntitle"><a href="" class="reposition">Type</a></th>
                <th class="wrapcolumntitle center"><a href="" class="reposition">Actions</th>
            </tr>
            <tr>
            <?php 
                if(isset($_SESSION['caisse@search'])) $transactions = $_SESSION['caisse@search'];
                foreach($transactions as $transaction): 
            ?>
                <td><?= $transaction->id ?></td>
                <td class="center"><?= $transaction->motif  ?></td>
                <td class="amount end"><?= number_format($transaction->valeur,2,","," ")." Ar" ?></td>
                <td class="center"><?= $transaction->date ?></td>
                <td><?= $transaction->entrant ? "dépot" : "retrait" ?></td>
                <td class="center">
                    <form action="../app/Controller/BrouillionController.php" method="post" class="d-inline">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                        <input type="hidden" name="entrant" value="<?= $transaction->entrant ?>">
                        <input type="hidden"  name="delete" value="<?= $transaction->id ?>">
                        <button class="btn" title="supprimer" data-bs-toggle="modal" data-bs-target="#modal<?= $transaction->id ?>">
                            <i class="fs-5 far fa-trash-alt text-secondary"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modal<?= $transaction->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">                        
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Attention !</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <p>
                                        Ref :<?= $transaction->id ?></br>
                                        motif :<?= $transaction->motif ?></br>
                                        valeur :<?= $transaction->valeur ?>
                                    </p>
                                    Voulez-vous vraiment supprimer cette transaction ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn text-light" style="background-color: var(--butactionbg);">Supprimer</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Boutton modifier -->
                    <button class="btn" type="button" name="edit" value="<?= $transaction->id ?>" data-bs-toggle="modal" data-bs-target="#modalModif"><i class="fs-5 fas fa-pencil-alt"></i></button>

                    <!-- valider un transaction  -->
                    <form action="../app/Controller/BrouillionController.php" method="post" class="d-inline">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                        <button class="btn" name="valideOne" value="<?= $transaction->id ?>"><i class="fs-5 fas fa-check"></i></button>
                    </form>
                </td>
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
    #id-container {
        height: 92vh;
    }
    i{
        color: #8a9097;
    }
    i.fa-check:hover{
        color: #00e056;
    }
    i.fa-trash-alt:hover{
        color: red!important;
    }
    i.fa-pencil-alt:hover{
        color: yellow;
    }
    #editform{
        display: none;
        margin-left: 2%;
    }
    #motif, #somme{
        color: #4e4e4e;
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

<script>
    const buttons = document.querySelectorAll('button[title=supprimer]');
    // const submits =  document.querySelectorAll('button[name=submit]');
    

    for (const button of buttons) {
        const form = button.parentElement;
        form.addEventListener('submit', function(e){
            if(e.submitter.title=='supprimer')
            e.preventDefault();
        });
    }
</script>