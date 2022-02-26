<?php if(!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?=$_SESSION['flash']['status'];?>" style="margin-bottom: 10px;">
            <?= $_SESSION['flash']['message']; ?>               
        </div>
<?php endif; ?>