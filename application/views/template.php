<?php
$timeHelp = "?" . time();

$theme = $this->input->get("theme");
$theme = !is_null($theme) ? $theme : "light";

$queryParams = $this->input->get();
$queryString = http_build_query($queryParams);

// Mendapatkan URL saat ini
$currentUrl = $_SERVER['REQUEST_URI'];

// Memisahkan path dari URL saat ini dan menghilangkan tanda "?"
$currentUrl = explode('?', parse_url($currentUrl, PHP_URL_PATH))[0];

$menuItems = array(
    // array(
    //     "id" => "home",
    //     "title" => "Home",
    //     "url" => "",
    //     "icon" => '<i class="bi bi-house"></i>', // Bootstrap Icon,
    //     "access" => is_public()
    // ),
    array(
        "id" => "network",
        "title" => "Network",
        "url" => "dashboard/network",
        "icon" => '<i class="bi bi-hdd-network"></i>',
        "access" => is_public()
    ),
    array(
        "id" => "aplikasi",
        "title" => "Aplikasi",
        "url" => "dashboard/aplikasi",
        "icon" => '<i class="bi bi-window-desktop"></i>',
        "access" => is_public()
    ),
    array(
        "id" => "qrgen",
        "title" => "QR Code",
        "url" => "QRCode/generator",
        "icon" => '<i class="bi bi-qr-code"></i>',
        "access" => is_public()
    ),
    // array(
    //     "id" => "logout",
    //     "title" => "Login",
    //     "url" => "auth/index",
    //     "icon" => '<i class="bi bi-box-arrow-in-right"></i>',
    //     "access" => is_not_user()
    // ),
    array(
        "id" => "logout",
        "title" => "Logout",
        "url" => "auth/logout",
        "icon" => '<i class="bi bi-box-arrow-right"></i>',
        "access" => is_user_id()
    ),
    // Add more menu items with their respective URLs
);

$menuHtml = '<ul class="navbar-nav">';
foreach ($menuItems as $menuItem) {
    $id = $menuItem["id"];
    $title = $menuItem["title"];
    $url = $menuItem["url"];
    $icon = $menuItem["icon"];
    $arrUrl = [];
    $lastValue = "";

    if (!empty($url)) {
        $url = base_url($url);
        $arrUrl = explode('/', $url);
    } else {
        $url = base_url();
    }

    // Memisahkan path dari URL saat ini
    $url = parse_url($url, PHP_URL_PATH);

    if (count($arrUrl) > 0) {
        $lastValue = end($arrUrl);
    }

    // Jika URL saat ini cocok dengan path di $menuItems
    $active = (trim($url, '/') === trim($currentUrl, '/')) ? "active" : "";
    if ($menuItem['access']) {
        $menuHtml .= '<li class="nav-item">'
            . '<a id="' . $id . '" class="nav-link ' . $active . '" aria-current="page" href="' . $url . '?' . $queryString . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="' . $title . '">' . $icon . ' ' . $title . '</a>'
            . '</li>';
    }
}
$menuHtml .= '</ul>';

$sideBarItemsB = array(
    array(
        "title" => "Master User",
        "url" => "user/manajemen",
        "icon" => '<i class="bi bi-people"></i>',
        "access" => is_super()
    ),
    array(
        "title" => "Role",
        "url" => "role/manajemen",
        "icon" => '<i class="bi bi-view-list"></i>',
        "access" => is_super()
    ),
    array(
        "title" => "Master Data",
        "url" => "ProgramKerja/data",
        "icon" => '<i class="bi bi-card-list"></i>',
        "access" => is_super()
    ),
    array(
        "title" => "History Keterangan",
        "url" => "keterangan/manajemen",
        "icon" => '<i class="bi bi-card-list"></i>',
        "access" => is_super()
    ),
);

$sideBar = '';
foreach ($sideBarItemsB as $sideBarItem) {
    $title = $sideBarItem["title"];
    $url = $sideBarItem["url"];
    $icon = $sideBarItem["icon"];
    $arrUrl = [];
    $lastValue = "";

    if (!empty($url)) {
        $url = base_url($url);
        $arrUrl = explode('/', $url);
    } else {
        $url = base_url();
    }

    // Memisahkan path dari URL saat ini
    $url = parse_url($url, PHP_URL_PATH);

    if (count($arrUrl) > 0) {
        $lastValue = end($arrUrl);
    }

    // Jika URL saat ini cocok dengan path di $sideBarItems
    $active = (trim($url, '/') === trim($currentUrl, '/')) ? "active" : "";
    if ($sideBarItem['access']) {
        $sideBar .=  '<a  class="list-group-item list-group-item-action ' . $active . '" aria-current="true" href="' . $url . '?' . $queryString . '" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="' . $title . '">' . $icon . ' ' . $title . '</a>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>

    <meta name="description" content="{metadesc}">
    <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon.ico">

    <link rel="stylesheet" href="<?= base_url('assets/css/template.css') . $timeHelp ?>">
    <script src="<?= base_url('assets/dist/template.bundle.js') . $timeHelp ?>"></script>
</head>

<body class="body">
    <div class="d-none">
        <input type="hidden" name="base_url" id="base_url" value="<?= base_url() ?>" />
        <input type="hidden" name="csrfName" id="csrfName" value="<?= $this->security->get_csrf_token_name(); ?>" />
        <input type="hidden" name="csrfToken" id="csrfToken" value="<?= $this->security->get_csrf_hash(); ?>">
    </div>

    <nav class="navbar fixed-top navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Tracker Graph</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <?php
                print $menuHtml;
                ?>
            </div>
        </div>
    </nav>

    <section id="content" class="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-xl-2 mb-4">
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Sidebar Item #1
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                <div class="accordion-body p-0">
                                    <div class="list-group rounded-0">
                                        <?= $sideBar ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                    Sidebar Item #2
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group rounded-0">
                                        <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                            The current link item
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                                        <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                        <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                        <a class="list-group-item list-group-item-action disabled" aria-disabled="true">A disabled link item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                    Sidebar Item #3
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                                <div class="accordion-body p-0">
                                    <div class="list-group rounded-0">
                                        <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                            The current link item
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                                        <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                        <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                        <a class="list-group-item list-group-item-action disabled" aria-disabled="true">A disabled link item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xl-10">
                    {content}
                </div>
            </div>
        </div>
    </section>

    <footer class="fixed-bottom">
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <span class="navbar-text">
                    Page rendered in <strong>{elapsed_time}</strong> seconds.
                    <?php echo (ENVIRONMENT === "development") ?  "CodeIgniter Version <strong>" . CI_VERSION . "</strong>" : "" ?>.
                    PHP Version <strong><?= phpversion() ?></strong>.
                </span>
            </div>
        </nav>
    </footer>
</body>

</html>