<div class="row auth-form-container justify-content-center">
    <div class="col-lg-4 border p-4">
        <h2 class="mb-4">Sign in</h2>
        <p class="js-error-text-common form-text text-danger"></p>
        <form class="js-auth-form" action="/auth/login" method="post">
            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $csrfToken ?>">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="js-input form-control" id="email" name="email" placeholder="Enter email" required>
                <small class="js-error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="js-input form-control" id="password" name="password" placeholder="Password"
                       required>
                <small class="js-error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="form-group">
                <p>Don't have an account? <a href="/auth/registration">Sign up</a></p>
            </div>
        </form>
    </div>
</div>
