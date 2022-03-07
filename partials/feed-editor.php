<?php
    $firstName = current(explode(' ', $userInfo->name));
?>
<div class="box feed-new">
       
     <div class="box-body">
         <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?= $base;?>/media/avatars/<?=$userInfo->avatar ?? 'default.jpg';?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$firstName?></div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-send" style="pointer-events:none; opacity:0.5;">
                 <img src="<?= $base;?>/assets/images/send.png"/>
            </div>
            <form class="feed-new-form" method="POST" action="<?=$base;?>/feed_editor_action.php">
                <input type="hidden" name="body">
            </form>
         </div>
    </div>
</div>
    <?php 
        unset($_SESSION['flash']);         
    ?>
<script>
    let feedInput = document.querySelector('.feed-new-input');
    let feedSubmit = document.querySelector('.feed-new-send');
    let feedForm = document.querySelector('.feed-new-form');
   
    feedInput.addEventListener("keyup", function() {
        let value = feedInput.innerText.trim();

        if(value === '') {
            feedSubmit.style.cssText = 'pointer-events:visible;' +
                                       'opacity:0.5;'
        } else {
            feedSubmit.style.cssText = 'pointer-events:visible;' +
                                       'opacity:1;'
        }
    });

    feedSubmit.addEventListener('click', function(){  
        let value = feedInput.innerText.trim();

        feedForm.querySelector('input[name=body]').value = value;
        feedForm.submit();
    });
</script>