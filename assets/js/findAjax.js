const ajaxbuttons = document.querySelectorAll('button[name=edit]');
const url = "../../app/ajax/find.php";
const motif = document.getElementById('motif');
const somme = document.getElementById('somme');
const id = document.getElementById('id');

    for (const iterator of ajaxbuttons) {
        iterator.addEventListener('click',function() {
            fetch(`../app/ajax/find.php?id=${this.value}`)
                .then(response => {
                    if(response.ok)
                    return response.json();   
                })
                .then(json => {
                    motif.value = json.motif;
                    somme.value = json.valeur;  
                    id.value = json.id;  
                });
        })
        
    }

