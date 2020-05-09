<div class="row login-form-container justify-content-center">
    <div class="col-lg-4 border p-4">
        <form class="js-auth-form" action="/auth/registration" method="post" novalidate>
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
                <input type="text" class="js-input form-control" id="last_name" name="last_name" placeholder="Enter email" required>
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
                    <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms">
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
