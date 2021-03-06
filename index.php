<?php
session_start();
require 'functions.php';

// logic for logout
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_unset();
    session_destroy();

    setcookie('key1', '', time() - 3600);
    setcookie('key2', '', time() - 3600);
    setcookie('key3', '', time() - 3600);
    setcookie('admin', '', time() - 3600);

    header('location: login.php');
    exit;
}

// logic for session login
if (!isset($_SESSION['login'])) {
    header("location: login.php?page=$nowPage");
    exit;
}

// logic for pagination.
$totalPagesList = 10;
$totalList = count(query("SELECT * FROM userlist"));
$totalPages = ceil($totalList / $totalPagesList);
$nowPage = isset($_GET['page']) ? $_GET['page'] : 1;
$indexStartList = ($totalPagesList * $nowPage) - $totalPagesList;

$userListValue = query("SELECT * FROM userlist ORDER BY userlist.name ASC LIMIT $indexStartList, $totalPagesList");

// logic for delete
if (isset($_GET['delete'])) {

    delete();
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en" id="header-slider">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akbarazy | Our List</title>

    <link rel="stylesheet" href="static/css/font-awesome.min.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="apple-touch-icon" href="images/favicon-apple.png" sizes="180x180" />
    <link rel="icon" href="images/favicon.png" sizes="32x32" />
</head>

<body>
    <!-- section navbar -->

    <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
        <a class="navbar-brand">Our List</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?logout=exit">Logout</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Slider</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="#header-slider">Header</a>
                        <a class="dropdown-item" href="#content-slider">Content</a>
                        <a class="dropdown-item" href="#pagination-slider">Pagination</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-md-0">

                <?php if (isset($_COOKIE['admin']) && isset($_SESSION['admin'])) : ?>
                    <a href="create-list.php" class="btn btn-outline-light mr-3 my-sm-0" name="create-list">Create List</a>
                <?php endif; ?>

                <input class="form-control live-search" type="text" placeholder="Search your name">
            </form>
        </div>
    </nav>

    <!-- end navbar -->


    <!-- section header -->

    <div class="jumbotron mt-5" id="header">
        <h1 class="display-4">Our List</h1>
        <p class="lead">Thank you for those of you who have registered and login into our website. Please add yourself to the list if you are interested and enjoy all the features we have provided.</p>
        <hr class="my-4">
        <p id="content-slider">Never interfere with lists that are not about yourself. Because all of this data list has an interest in the purpose of this website.</p>
    </div>

    <!-- end header -->


    <!-- section content -->

    <div class="content" id="content">
        <div class="mx-5 card-content">
            <div class="row justify-content-center">

                <?php foreach ($userListValue as $userListValueRow) : ?>
                    <div class="col-430px col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                        <div class="card mb-4 box-shadow" style="height: calc(100% - 1.5rem);">
                            <img class="card-img-top card-image" src="images/<?php echo $userListValueRow['image']; ?>" alt="Card image cap">
                            <div class="card-body">
                                <p class="card-text text-center"><?php echo $userListValueRow['name']; ?></p>

                                <div class="d-flex
                                    <?php if (isset($_COOKIE['admin']) && isset($_SESSION['admin'])) echo 'flex-column'; ?>
                                    justify-content-between
                                    align-items-center">

                                    <?php if (isset($_COOKIE['admin']) && isset($_SESSION['admin'])) : ?>
                                        <div class="btn-group">
                                            <a href="edit-list.php?edit=<?php echo $userListValueRow['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <a href="index.php?delete=<?php echo $userListValueRow['id']; ?>#content-slider" class="btn btn-sm btn-outline-secondary">Delete</a>
                                        </div>
                                    <?php endif; ?>

                                    <small class="text-muted mx-auto
                                        <?php if (isset($_COOKIE['admin']) && isset($_SESSION['admin'])) echo 'mt-date'; ?>" id="pagination-slider">
                                        <?php echo $userListValueRow['date']; ?>
                                    </small>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <nav aria-label="Page navigation example" id="pagination">
                <ul class="pagination d-flex justify-content-center mb-5">
                    <li class="page-item">

                        <?php if ($nowPage > 1) : ?>
                            <a class="page-link" href="index.php?page=<?php echo $nowPage - 1; ?>#content-slider" aria-label="Previous">
                                <span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                                <span class="sr-only">Prev</span>
                            </a>
                        <?php else : ?>
                            <a class="page-link off cursor-default" aria-label="Previous">
                                <span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                                <span class="sr-only">Prev</span>
                            </a>
                        <?php endif; ?>

                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <?php if ($i == $nowPage) : ?>
                            <li class="page-item"><a class="page-link font-weight-bold pressed cursor-default"><?php echo $i; ?></a></li>
                        <?php else : ?>
                            <li class="page-item"><a class="page-link font-weight-bold" href="index.php?page=<?php echo $i; ?>#content-slider"><?php echo $i; ?></a></li>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <li class="page-item">

                        <?php if ($nowPage < $totalPages) : ?>
                            <a class="page-link" href="index.php?page=<?php echo $nowPage + 1; ?>#content-slider" aria-label="Next">
                                <span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                <span class="sr-only">Next</span>
                            </a>
                        <?php else : ?>
                            <a class="page-link off cursor-default" aria-label="Next">
                                <span aria-hidden="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                <span class="sr-only">Next</span>
                            </a>
                        <?php endif; ?>

                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <!-- end content -->


    <!-- section footer -->

    <footer class="page-footer bg-dark">
        <div class="bg-dark">
            <div class="container">

                <div class="row py-4 d-flex align-items-center">
                    <div class="col-md-12 text-center footer-icons">

                        <a href="https://www.youtube.com/"><i class="fa fa-youtube-play fa-lg mr-4" aria-hidden="true"></i></a>
                        <a href="https://www.google.com"><i class="fa fa-google fa-lg mr-4" aria-hidden="true"></i></a>
                        <a href="https://web.whatsapp.com"><i class="fa fa-whatsapp fa-lg mr-4" aria-hidden="true"></i></a>
                        <a href="https://www.facebook.com"><i class="fa fa-facebook-official fa-lg mr-4" aria-hidden="true"></i></a>

                    </div>
                </div>

            </div>
        </div>

        <div class="bg-darkblack">
            <div class="container pt-5">
                <div class="row">

                    <div class="col-sm-4 col-md-4 mb-5 footer1">
                        <h6 class="text-uppercase font-weight-bold text-white">Developer</h6>
                        <hr class="bg-primary mb-4 mt-0 d-inline-block mx-auto" style="width:87px; height: 2px;">
                        <p class="mt-2 text-white">Don't forget to visit our social media. if there is an error on our website, you can visit us <a class="font-weight-bold" href="https://akbarazyportfolio.000webhostapp.com">Here</a></p>
                    </div>

                    <div class="col-sm-4 col-md-4 mb-5 footer2">
                        <h6 class="text-uppercase font-weight-bold text-white">My Skills</h6>
                        <hr class="bg-primary mb-4 mt-0 d-inline-block mx-auto" style="width:87px; height: 2px;">
                        <ul class="list-unstyled">
                            <li class="my-2 text-white">HTML</li>
                            <li class="my-2 text-white">CSS</li>
                            <li class="my-2 text-white">Javascript</li>
                            <li class="my-2 text-white">PHP</li>
                        </ul>
                    </div>

                    <div class="col-sm-4 col-md-4 mb-5 footer3">
                        <h6 class="text-uppercase font-weight-bold text-white">About Me</h6>
                        <hr class="bg-primary mb-4 mt-0 d-inline-block mx-auto" style="width:87px; height: 2px;">
                        <p class="mt-2 text-white">I am a developer of this website. I come from Indonesia. I am 15 years old and still a high school student</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-dark">
            <div class="container pt-3">
                <div class="row d-flex justify-content-center">

                    <div class="col-md-4">
                        <p class="text-grey text-center mb-0">&copy; Copyright | Akbarazy 2022</p>
                        <p class="text-grey text-center">Design by Akbarazy</p>
                    </div>

                </div>
            </div>
        </div>
    </footer>

    <!-- end footer -->

    <!-- <script src="static/js/jquery-3.2.1.slim.min.js"></script> -->
    <script src="static/js/jquery-3.6.0.min.js"></script>
    <script src="static/js/bootstrap.bundle.min.js"></script>
    <script src="static/ajax/live-search.js"></script>
    <script>
        const dropdownItem = document.querySelectorAll('.dropdown-item');
        dropdownItem.forEach(function(element) {
            element.addEventListener('click', function(event) {

                const navbarCollapse = document.querySelector('.navbar-collapse');
                navbarCollapse.classList.remove('show');
            })
        });
    </script>
</body>

</html>