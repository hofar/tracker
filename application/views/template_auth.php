<?php
$theme = $this->input->get("theme");
$theme = !is_null($theme) ? $theme : "light";
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
            margin-bottom: 100px;
        }
    </style>
</head>

<body>
    <section id="content" class="">
        <div class="container">
            {content}
        </div>
    </section>

    <footer class="fixed-bottom">
        <nav class="navbar bg-body-tertiary">
            <div class="container">
                <span class="navbar-text">
                    Page rendered in <strong>{elapsed_time}</strong> seconds.
                    <?php echo (ENVIRONMENT === "development") ?  "CodeIgniter Version <strong>" . CI_VERSION . "</strong>" : "" ?>.
                    PHP Version <strong><?= phpversion() ?></strong>.
                </span>
            </div>
        </nav>
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