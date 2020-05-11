<?php $user = $_SESSION['user'] ?>

<div class="row content pl-5 mt-5">
    <div class="col-lg-2 border">
        <div class="mt-2">
            <div class="d-flex justify-content-center align-items-center">
                <img class="user-avatar"
                     src="<?= $user->getAvatarFullPath() ?>" alt="avatar">
                <div class="dropdown ml-2">
                    <button class="btn btn-secondary dropdown-toggle"
                            type="button" id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/auth/logout">Logout</a>
                    </div>
                </div>
            </div>
            <p class="text-center"><?= $user->getFullName() ?></p>
        </div>
    </div>
</div>
