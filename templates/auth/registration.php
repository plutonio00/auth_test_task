<div class="row auth-form-container justify-content-center">
    <div class="col-lg-4 border p-4">
        <h2 class="mb-4">Sign up</h2>
        <p class="js-error-text-common form-text text-danger"></p>
        <form class="js-auth-form" action="/auth/registration" enctype="multipart/form-data" method="post">
            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $csrfToken ?>">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="js-input form-control" id="email" name="email" placeholder="Enter email" required>
                <small class="js-error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="first_name">First name</label>
                <input type="text" class="js-input form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                <small class="js-error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="last_name">Last name</label>
                <input type="text" class="js-input form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                <small class="js-error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="js-input form-control" id="password" name="password" placeholder="Password"
                       required>
                <small class="js-error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="avatar">Load avatar (file no larger than 30MB, file in formats: jpg, jpeg, png, bmp)</label>
                <input type="file" class="js-input form-control-file" id="avatar" name="avatar" accept="image/jpeg, image/png, image/jpg, image/bmp">
                <small class="js-error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="js-input form-check-input" id="agree_terms" name="agree_terms" required>
                    <label class="form-check-label" for="agree_terms">I accept the terms of use of the site</label>
                    <small class="js-error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="form-group">
                <p>Already have an account? <a href="/auth/login">Sign in</a></p>
            </div>
        </form>
    </div>
</div>
