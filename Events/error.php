<?php
function succes($champs)
{
    ?>
    <div class="alert alert-success">
    <i class="icon icon-check-circle icon-lg"></i>
    <strong>Succès, </strong><?php echo $champs;?>
    </div>

    <?php
}
function error($champs)
{
    ?>
    <div class="alert alert-error">
        <i class="icon icon-check-circle icon-lg"></i>
        <strong>Attention, </strong><?php echo $champs;?>
    </div>

    <?php
}

?>