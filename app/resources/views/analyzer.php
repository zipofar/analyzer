<?php require 'templates/header.php' ?>
<?php require 'templates/form_test.php' ?>
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
                            <h2>Images size: <?= $images['total_size'] ?></h2>
                            <?php foreach ($images['img'] as $img): ?>

                                <p class="card-text">
                                    <img width="100" src="<?= $img['url'] ?>"> | Size: <?= $img['size'] ?>
                                </p>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
<?php require 'templates/footer.php' ?>