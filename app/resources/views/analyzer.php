<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Page speed optimization</h1>
                    <form class="form-inline" action="/analyze" method="post">
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="inputPassword2" class="sr-only">Password</label>
                            <input type="text" class="form-control" name="url" placeholder="Enter webpage url">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">TEST</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            Summary
                        </div>
                        <div class="card-body">
                            <p>Server:</p>
                            <ul>
                                <li>Server response time: <?= $page['response_time'] ?> ms</li>
                                <li>Count redirects: <?= $page['count_redirects'] ?></li>
                            </ul>

                            <p>Page:</p>
                            <ul>
                                <li>Count bad requests: <?= $page['count_bad_requests'] ?></li>
                                <?php foreach ($page['url_bad_requests'] as $item): ?>
                                    <div class="alert alert-dark" role="alert">
                                        <?= $item ?>
                                    </div>
                                <?php endforeach ?>
                            </ul>

                            <p>Resources:</p>
                            <ul>
                                <li>Count CSS: <?= $css['total_count'] ?></li>
                                <li>Count JS: <?= $scripts['total_count'] ?></li>
                                <li>Count Images: <?= $images['total_count'] ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            CSS
                        </div>
                        <div class="card-body">
                            <h2>CSS size: <?= $css['total_size'] ?></h2>
                            <p>External: <?= $css['ext']['size'] ?> / Internal: <?= $css['int']['size'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            JavaScript
                        </div>
                        <div class="card-body">
                            <h2>JavaScript size: <?= $scripts['total_size'] ?></h2>
                            <p>External: <?= $scripts['ext']['size'] ?> / Internal: <?= $scripts['int']['size'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            Images
                        </div>
                        <div class="card-body">
                            <h2>Images size: <?= $scripts['total_size'] ?></h2>
                            <?php foreach ($images['img'] as $img): ?>

                                <p class="card-text">
                                    <img width="100" src="<?= $img['url'] ?>"> | Size: <?= $img['size'] ?>
                                </p>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>