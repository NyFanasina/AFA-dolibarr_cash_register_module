<?php 
    if(isset($_SESSION['message'])){
        $message = $_SESSION['message'];
?>

<div class="alert alert-<?= $message[0]?>" id="alert">
    <strong><?= $message[1] ?> </strong> <?= $message[2] ?>
</div>'

<?php 
    }
unset($_SESSION['message']);
?>


<script>
    const alert = document.getElementById('alert');
    
    setTimeout(() => alert.style.display= 'none',12000);
</script>

<style>
    #alert{
        position: fixed;
        top: 60px;
        right: 15px;
    }
</style>