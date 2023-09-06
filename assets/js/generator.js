import { toCanvas } from 'qrcode';

const canvas = document.getElementById('qrcode');
let currentSize = 384; // default canvas size
// Define the initial scale (number of pixels per QR module)
let currentScale = 8;
let baseUrl = document.getElementById("base_url").value;

var csrfName = document.getElementById("csrfName").value;
var csrfToken = document.getElementById("csrfToken").value;

// Function to generate QR code
function generateQRCode() {
    const inputValue = document.getElementById('inputValue').value;
    if (inputValue.trim() !== "") {
        // Generate the QR code
        toCanvas(canvas, inputValue, { width: currentSize, height: currentSize, scale: currentScale }, function (error) {
            if (error) console.error(error);
            console.log('QR code generated successfully!');
        });
        canvas.classList.add('show');
    } else {
        alert("Masukkan input sebelum membuat QR Code.");
    }
}

// Function to generate random token-like string
function generateToken() {
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let tokenLength = 50;
    let token = '';

    for (let i = 0; i < tokenLength; i++) {
        let randomIndex = Math.floor(Math.random() * characters.length);
        token += characters.charAt(randomIndex);
    }

    document.getElementById('inputValue').value = token;

    generateQRCode();
    getCSRFToken(token);
}

// Function to increase QR code size (tambah pixel)
function increaseQRSize() {
    if (canvas) {
        currentSize = parseInt(canvas.style.width);
        if (!isNaN(currentSize)) {
            currentSize += 10; // Tambah 10 pixel
            canvas.style.width = currentSize + "px";
            canvas.style.height = currentSize + "px";
        }
    }
}

// Function to decrease QR code size (kurangi pixel)
function decreaseQRSize() {
    if (canvas) {
        currentSize = parseInt(canvas.style.width);
        if (!isNaN(currentSize) && currentSize > 10) {
            currentSize -= 10; // Kurangi 10 pixel
            canvas.style.width = currentSize + "px";
            canvas.style.height = currentSize + "px";
        }
    }
}

document.getElementById('generateQRCode').addEventListener('click', generateToken);
document.getElementById('increaseQRSize').addEventListener('click', increaseQRSize);
document.getElementById('decreaseQRSize').addEventListener('click', decreaseQRSize);

// Bagian JavaScript untuk mengambil CSRF token dan eksekusi kode lain
function getCSRFToken(token) {
    var xhr = new XMLHttpRequest();

    // Atur callback untuk menangani respon dari server
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Jika permintaan berhasil, tangkap CSRF token dari respons JSON
                var response = JSON.parse(xhr.responseText);
                csrfName = response.csrfName;
                csrfToken = response.csrfToken;

                // Eksekusi kode lain dengan menggunakan CSRF token
                insertToken(csrfName, csrfToken, token);
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
function insertToken(csrfName, csrfToken, token) {
    // Generate data token menggunakan Math.random() (sebagai contoh)
    // var token = Math.random().toString(36).substr(2, 50);

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
                console.log('Token sent successfully');
            } else {
                // Permintaan gagal, tangani kesalahan (jika ada)
                console.error('Failed to send token');
            }
        }
    };

    // Tentukan metode dan URL permintaan (ganti "alamat_server_anda" dengan URL server Anda)
    var url = baseUrl + "/QRCode/insertToken";
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