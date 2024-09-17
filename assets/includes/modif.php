<!-- Modal -->
<div class="modal fade" id="modalModif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">                        
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Attention !</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-start">
            <h2>Modification de la transaction ref:</h2>

            <form action="../app/Controller/TransactionController.php" method="POST">
                <table>
                    <tr class="w-100">
                        <input type="hidden" name="id" id="id">
                        <td><label for="motif">Motif : </label></td>
                        <td><input  type="text" id="motif" class="inputcaisse" required name="motif"></td>
                    </tr>
                    <tr>
                        <td><label for="somme">Somme : </label></td>
                        <td><input type="number" id="somme" required name="valeur"></td>
                    </tr>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn text-white" name="update" style="background-color: var(--butactionbg);">ENREGISTRER</button>
            </form>
        </div>
        </div>
    </div>
</div>