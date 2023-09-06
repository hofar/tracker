import jQuery from 'jquery';
import { Modal, Tooltip } from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import { Html5QrcodeScanner } from 'html5-qrcode';

// Mendapatkan bagian domain (seperti http://localhost) dari URL
const domain = window.location.origin;

// Mendapatkan bagian path (seperti /tracker/qrcode/generator) dari URL
const path = window.location.pathname;

// Memecah path menjadi array dengan menggunakan '/' sebagai pemisah
const pathParts = path.split('/');

// Mengambil elemen pertama dari array pathParts untuk mendapatkan bagian sebelum 'tracker'
const pathBeforeTracker = pathParts.slice(0, pathParts.indexOf('tracker') + 1).join('/');

// Menggabungkan domain dan pathBeforeTracker untuk mendapatkan URL lengkap tanpa query string
const urlWithoutQueryString = domain + pathBeforeTracker;

console.log(urlWithoutQueryString);

jQuery(document).on('DOMContentLoaded', function () {
    const resultContainer = document.getElementById('qr-reader-results');
    let lastResult, countResults = 0;
    let baseUrl = urlWithoutQueryString;

    function onScanSuccess(decodedText, decodedResult) {
        if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
            // Handle on success condition with the decoded message.
            console.log(`Scan result ${decodedText}`, decodedResult);

            // getCSRFToken(token);

            resultContainer.innerHTML = 'Hasil scan: <strong>' + decodedText + '</strong>';
            resultContainer.classList.add('show');
        }
    }

    const qrCodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 256 });
    qrCodeScanner.render(onScanSuccess);

    // Bagian JavaScript untuk mengambil CSRF token dan eksekusi kode lain
    function getCSRFToken(token) {
        var xhr = new XMLHttpRequest();

        // Atur callback untuk menangani respon dari server
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Jika permintaan berhasil, tangkap CSRF token dari respons JSON
                    var response = JSON.parse(xhr.responseText);
                    var csrfName = response.csrfName;
                    var csrfToken = response.csrfToken;

                    // Eksekusi kode lain dengan menggunakan CSRF token
                    setFlagToOne(csrfName, csrfToken, token);
                } else {
                    // Jika permintaan gagal, tangani kesalahan (jika ada)
                    console.error('Failed to get CSRF token');
                }
            }
        };

        // Tentukan metode dan URL permintaan (ganti "alamat_server_anda" dengan URL server Anda)
        var url = baseUrl + "/ProgramKerja/get_csrf_token";
        xhr.open('GET', url, true);

        // Kirim permintaan untuk mendapatkan CSRF token
        xhr.send();
    }

    // Bagian JavaScript untuk AJAX POST request
    function setFlagToOne(csrfName, csrfToken, token) {
        // Ambil token dari input atau dapatkan dari sumber lain
        // var token = 'uHNGJ7Mq1RiZJdnyzfjJxRPnRvyvmiDpDKOHbWTsjfMI2tL8yh';

        // Data yang akan dikirimkan dalam permintaan AJAX POST
        var data = {
            [csrfName]: csrfToken,
            token: token
        };

        // Buat objek XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Atur callback untuk menangani respon dari server
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Permintaan berhasil, lakukan tindakan sesuai respons dari server (jika ada)
                    console.log('QR Code Match');

                    alert("Successfull!!\n\n\nQR Code Match Success!\n\n\nYou have match QR Code with server\n\n\nprocess other script");
                } else {
                    // Permintaan gagal, tangani kesalahan (jika ada)
                    console.error('Failed to match QR Code');

                    alert("Failed!!\n\n\nQR Code Match Failed!\n\n\nUnknown QR Code");
                }
            }
        };

        // Tentukan metode dan URL permintaan (ganti "alamat_server_anda" dengan URL server Anda)
        var url = baseUrl + "/QRCode/setFlagToOne";
        xhr.open('POST', url, true);

        // Atur header jika diperlukan (misalnya, jika Anda ingin mengirim data dalam format JSON)
        // xhr.setRequestHeader('Content-Type', 'application/json');

        // Atur header untuk mengharapkan respons sebagai JSON
        xhr.setRequestHeader('Accept', 'application/json');

        // Atur responseType untuk mengurai respons JSON secara otomatis
        xhr.responseType = 'json';

        // Kirim permintaan dengan data yang telah disiapkan
        xhr.send(JSON.stringify(data));
    }

    console.log('%cPeringatan: Jangan masukkan data atau skrip apapun.', 'color: blue; font-weight: bold; font-size: 24px; text-shadow: -1px -1px 0 yellow, 1px -1px 0 yellow, -1px 1px 0 yellow, 1px 1px 0 yellow;');
    console.log('%cJika seseorang meminta Anda untuk menyalin/tempel sesuatu di sini, ada kemungkinan 11/10 bahwa Anda sedang ditipu.', 'color: blue; font-weight: bold; font-size: 24px; text-shadow: -1px -1px 0 yellow, 1px -1px 0 yellow, -1px 1px 0 yellow, 1px 1px 0 yellow;');
    console.log('%cMenempelkan apapun di sini bisa memberikan akses kepada penyerang ke akun Anda.', 'color: red; font-weight: bold; font-size: 24px; text-shadow: -1px -1px 0 yellow, 1px -1px 0 yellow, -1px 1px 0 yellow, 1px 1px 0 yellow;');
    console.log('%cKecuali Anda benar-benar memahami apa yang Anda lakukan, tutup jendela ini dan tetap aman.', 'color: blue; font-weight: bold; font-size: 24px; text-shadow: -1px -1px 0 yellow, 1px -1px 0 yellow, -1px 1px 0 yellow, 1px 1px 0 yellow;');
});

/*!
* Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
* Copyright 2011-2023 The Bootstrap Authors
* Licensed under the Creative Commons Attribution 3.0 Unported License.
*/

'use strict'

const getStoredTheme = () => localStorage.getItem('theme')
const setStoredTheme = theme => localStorage.setItem('theme', theme)

const getPreferredTheme = () => {
    const storedTheme = getStoredTheme()
    if (storedTheme) {
        return storedTheme
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
}

const setTheme = theme => {
    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark')
    } else {
        document.documentElement.setAttribute('data-bs-theme', theme)
    }
}

setTheme(getPreferredTheme())

const showActiveTheme = (theme, focus = false) => {
    const themeSwitcher = document.querySelector('#bd-theme')

    if (!themeSwitcher) {
        return
    }

    const themeSwitcherText = document.querySelector('#bd-theme-text')
    const activeThemeIcon = document.querySelector('.theme-icon-active use')
    const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
    const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')

    document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
        element.classList.remove('active')
        element.setAttribute('aria-pressed', 'false')
    })

    btnToActive.classList.add('active')
    btnToActive.setAttribute('aria-pressed', 'true')
    activeThemeIcon.setAttribute('href', svgOfActiveBtn)
    const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
    themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)

    if (focus) {
        themeSwitcher.focus()
    }
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = getStoredTheme()
    if (storedTheme !== 'light' && storedTheme !== 'dark') {
        setTheme(getPreferredTheme())
    }
})

window.addEventListener('DOMContentLoaded', () => {
    showActiveTheme(getPreferredTheme())

    document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const theme = toggle.getAttribute('data-bs-theme-value')
            setStoredTheme(theme)
            setTheme(theme)
            showActiveTheme(theme, true)
        })
    })
})