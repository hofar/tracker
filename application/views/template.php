<?php
$theme = $this->input->get("theme");
$theme = !is_null($theme) ? $theme : "dark";

$queryParams = $this->input->get();
$queryString = http_build_query($queryParams);

$menuItems = array(
    array(
        "title" => "Home",
        "url" => "",
        "icon" => '<i class="bi bi-house"></i>', // Bootstrap Icon,
        "access" => is_user_id()
    ),
    array(
        "title" => "Network",
        "url" => "dashboard/network",
        "icon" => '<i class="bi bi-hdd-network"></i>',
        "access" => is_user_id()
    ),
    array(
        "title" => "Aplikasi",
        "url" => "dashboard/aplikasi",
        "icon" => '<i class="bi bi-window-desktop"></i>',
        "access" => is_user_id()
    ),
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
        "title" => "Logout",
        "url" => "auth/logout",
        "icon" => '<i class="bi bi-box-arrow-right"></i>',
        "access" => is_user_id()
    ),
    // Add more menu items with their respective URLs
);

// Mendapatkan URL saat ini
$currentUrl = $_SERVER['REQUEST_URI'];

// Memisahkan path dari URL saat ini dan menghilangkan tanda "?"
$currentUrl = explode('?', parse_url($currentUrl, PHP_URL_PATH))[0];

$menuHtml = '<ul class="navbar-nav">';
foreach ($menuItems as $menuItem) {
    $title = $menuItem["title"];
    $url = $menuItem["url"];
    $icon = $menuItem["icon"];
    $arrUrl = [];
    $lastValue = "";

    if (!empty($url)) {
        $url = site_url($url);
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
            . '<a class="nav-link ' . $active . '" aria-current="page" href="' . $url . '?' . $queryString . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="' . $title . '">' . $icon . ' ' . $title . '</a>'
            . '</li>';
    }
}
$menuHtml .= '</ul>';
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $theme ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>

    <meta name="description" content="{metadesc}">
    <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        #content {
            margin-top: 100px;
        }

        .navbar {
            background-color: #6610f2;
        }

        li.nav-item {
            position: relative;
        }

        a.nav-link.active:after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            border-bottom: 2px solid white;
            border-radius: .5px;
            top: 5px;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .anychart-credits {
            display: none !important;
        }
    </style>
</head>

<body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark">
        <div class="container">
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

    <section id="content" class="mb-4">
        <div class="container">
            {content}
        </div>
    </section>

    <footer>
        <div class="container mb-4">
            <p class="m-0">
                Page rendered in <strong>{elapsed_time}</strong> seconds.
                <?php echo (ENVIRONMENT === "development") ?  "CodeIgniter Version <strong>" . CI_VERSION . "</strong>" : "" ?>.
                PHP Version <strong><?= phpversion() ?></strong>.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src=""></script>

    <script>
        (() => {
            "use strict"
            // tooltip
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

            // theme
            const getStoredTheme = () => localStorage.getItem("theme")
            const setStoredTheme = theme => localStorage.setItem("theme", theme)

            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme()
                if (storedTheme) {
                    return storedTheme
                }

                return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"
            }

            const setTheme = theme => {
                if (theme === "auto" && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    document.documentElement.setAttribute("data-bs-theme", "dark")
                } else {
                    document.documentElement.setAttribute("data-bs-theme", theme)
                }
            }

            // setTheme(getPreferredTheme())

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitcher = document.querySelector("#bd-theme")

                if (!themeSwitcher) {
                    return
                }

                const themeSwitcherText = document.querySelector("#bd-theme-text")
                const activeThemeIcon = document.querySelector(".theme-icon-active use")
                const btnToActive = document.querySelector('[data-bs-theme-value="${theme}"]')
                const svgOfActiveBtn = btnToActive.querySelector("svg use").getAttribute("href")

                document.querySelectorAll("[data-bs-theme-value]").forEach(element => {
                    element.classList.remove("active")
                    element.setAttribute("aria-pressed", "false")
                })

                btnToActive.classList.add("active")
                btnToActive.setAttribute("aria-pressed", "true")
                activeThemeIcon.setAttribute("href", svgOfActiveBtn)
                const themeSwitcherLabel = '${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})'
                themeSwitcher.setAttribute("aria-label", themeSwitcherLabel)

                if (focus) {
                    themeSwitcher.focus()
                }
            }

            window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
                const storedTheme = getStoredTheme()
                if (storedTheme !== "light" && storedTheme !== "dark") {
                    setTheme(getPreferredTheme())
                }
            })

            window.addEventListener("DOMContentLoaded", () => {
                showActiveTheme(getPreferredTheme())

                document.querySelectorAll("[data-bs-theme-value]").forEach(toggle => {
                    toggle.addEventListener("click", () => {
                        const theme = toggle.getAttribute("data-bs-theme-value")
                        setStoredTheme(theme)
                        setTheme(theme)
                        showActiveTheme(theme, true)
                    })
                })
            })
        })()
    </script>
</body>

</html>