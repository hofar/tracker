<?php
$timeHelp = "?" . time();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code - Scanner</title>

    <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon.ico">

    <style>
        /**
        * html5-qrcode-css
        * Github: https://github.com/Qiming-Liu/html5-qrcode-css
        **/

        #qr-reader,
        #qr-reader-results.show {
            background: white;
            border: 0 !important;
        }

        #qr-reader,
        #qr-reader-results.show,
        .box-button {
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
            color: black;
            width: 100%;
        }

        div#qr-reader-results {
            font-size: 22px;
            display: none;
        }

        div#qr-reader-results strong {
            word-wrap: break-word;
        }

        div#qr-reader-results.show {
            display: block;
        }

        #qr-reader__dashboard_section_fsr span {
            display: none !important;
        }

        #qr-reader__dashboard_section {
            padding: 0 !important;
        }

        #qr-reader__dashboard_section_swaplink {
            text-decoration: none !important;
            border-radius: 2px;
            margin-top: 1rem;
            color: #fff;
            background-color: #1989fa;
            border: 1px solid #1989fa;
            position: relative;
            display: inline-block;
            box-sizing: border-box;
            height: 2.5rem;
            width: 88%;
            line-height: 2.5rem;
            text-align: center;
            cursor: pointer;
            align-items: center;
            -webkit-box-pack: center;
        }

        #qr-reader__dashboard_section_csr button {
            text-decoration: none !important;
            /* border-radius: 2px; */
            border-radius: 0.375rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
            color: #fff;
            background-color: #ee0a24;
            border: 1px solid #ee0a24;
            position: relative;
            display: inline-block;
            box-sizing: border-box;
            /* height: 2.5rem; */
            width: 88% !important;
            line-height: 2.5rem;
            text-align: center;
            cursor: pointer;
            align-items: center;
            -webkit-box-pack: center;
        }

        #qr-reader__dashboard_section_csr span {
            margin-right: 0 !important;
        }

        #qr-reader__dashboard_section_fsr input {
            text-decoration: none !important;
            border-radius: 2px;
            margin-top: 1rem;
            color: #fff;
            background-color: #07c160;
            border: 1px solid #07c160;
            position: relative;
            display: inline-block;
            box-sizing: border-box;
            height: 2.5rem;
            width: 88% !important;
            line-height: 2.5rem;
            text-align: center;
            cursor: pointer;
            align-items: center;
            -webkit-box-pack: center;
        }

        #qr-reader__camera_selection {
            background-color: #fff;
            height: 2.5rem;
            width: 88%;
            line-height: 2.5rem;
            border: 1px solid #1989fa;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
        }

        #qr-reader span#html5-qrcode-anchor-scan-type-change {
            color: black;
        }

        /* X-Small devices (portrait phones, less than 576px) */
        /* No media query for `xs` since this is the default in Bootstrap */

        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) {}

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) {

            #qr-reader,
            #qr-reader-results.show,
            .box-button {
                width: 500px;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {}

        /* X-Large devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) {}

        /* XX-Large devices (larger desktops, 1400px and up) */
        @media (min-width: 1400px) {}
    </style>
</head>

<body>
    <div class="container my-5 position-relative">
        <div id="qr-reader" class="rounded p-4 mb-4"></div>
        <div id="qr-reader-results" class="rounded p-4 mb-4"></div>
        <!--
            <div class="box-button">
                <a href="https://hofarismail.site/pemindai-qrcode/generator.html" target="_qrGenerator" class="btn btn-info">
                    <i class="bi bi-qr-code me-1"></i> QR Code Generator
                </a>
            </div>
        -->
    </div>

    <script src="./assets/dist/scanner.bundle.js<?= $timeHelp ?>"></script>
</body>
</head>

</html>