<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <title>The SUPERGlobal Variables</title>
</head>

<body>

    <div class="container p-5">
        <div class="row justify-content-center">
            <div class="col col-md-4">
                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="input" id="name" placeholder="Type name...">
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <?php if(isset($_GET['submit'])): ?>
                    <div class="text-content">
                        <p>GET VALUE: <?= $_GET['input']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($_POST['submit'])): ?>
                    <div class="text-content">
                        <p>POST VALUE: <?= $_POST['input']; ?></p>
                    </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="col col-md-4">
                <div class="content">
                    <p>FROM GET</p>
                    <pre>
                        <?php
                            // Check if the submit button is clicked
                            if(isset($_GET['submit'])) {
                                var_dump($_GET);
                            }
                        ?>
                    </pre>
                </div>
                <div class="content">
                    <p>FROM POST</p>
                    <pre>
                        <?php
                            // Check if the submit button is clicked
                            if(isset($_POST['submit'])) {
                                var_dump($_POST);
                            }
                        ?>
                    </pre>
                </div>
                <div class="content">
                    <p>FROM REQUEST</p>
                    <pre>
                        <?php
                            var_dump($_REQUEST);
                        ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>