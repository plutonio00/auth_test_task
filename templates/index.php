<?php $user = $_SESSION['user'] ?>

<div class="row content pl-5 mt-5">
    <div class="col-lg-2 border">
        <div class="mt-2">
            <div class="d-flex justify-content-center">
                <img class="user-avatar"
                     src="<?= $user->getAvatarFullPath() ?>" alt="avatar">
            </div>
            <p class="text-center"><?= $user->getFullName() ?></p>
        </div>
    </div>
</div>
