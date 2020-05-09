<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $pageName ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<script src="/js/form_handler.js"></script>
</body>
</html>
