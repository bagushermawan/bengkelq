<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="assets/css/rootresi.css">
    <link rel="stylesheet" href="assets/css/cekresi.css">
    <link rel="stylesheet" href="/assets/css/iziToast.min.css">
    <style>
        #search-error-toast {
            position: relative;
            top: 0;
            right: 0;
            z-index: 9999;
            /* Atur z-index lebih tinggi dari kotak pencarian */
        }
    </style>

    <title>Cek Status</title>
</head>

<body>
    <nav class="navbarr flex">
        <div class="logo"><a href="{{ route('/') }}"><img src="assets/logo.png" alt="logo" /></a></div>
        <div class="mobile-menu"><span></span></div>
        <ul class="list-menu flex">
            @if ($currentUserRole === 'Admin')
                <li class="btn btn-primary"><a href="{{ route('home') }}">Admin Page</a></li>
            @endif
            <li><a href="{{ route('/') }}#blog" target="_blank">Blog</a></li>
            <li><a href="{{ route('/') }}#tentang" target="_blank">Tentang kami</a></li>
        </ul>
    </nav>
    <section id="main" class="flex">
        <h2>
            Cek Status Motormu disini
        </h2>
        <p>
            Kamu bisa mengecek status motormu disini, mulai dari Nama, Merk, Nopol, Status, dan detail lainnya.
        </p>
        <div id="container-resi" class="resi">
            <div class="form-container">
                <form action="" method="get">
                    <ul>
                        <li>
                            <label class="teks">Kode Transaksi :</label>
                            <div id="search-error-toast"></div>
                            <input class="teks" type='text' id='search' name='search'
                                placeholder='Masukkan kode/nama transaksi ..' required autofocus>
                            <a type="button" class="btn btn-more track" value='Search' id='but_search'>
                                <i
                                    class="fa-regular fa-magnifying-glass"style="font-family: Font Awesome 5 Free; font-weight: 900"></i>
                                Cek Status
                            </a>
                            @if ($currentUserRole === 'Admin')
                                <input class="btn btn-more track" type='button' value='Fetch all records'
                                    id='but_fetchall'>
                            @endif
                        </li>
                    </ul>
                </form>
            </div>
            <div class="detail-container">
                <h3 class="title-section"><span>Cek</span> Status</h3>
                <div class="body-card flex">
                    <div class="body-card">
                        <div class="loading-overlay">
                            <div class="loading-animation"></div>
                        </div>
                        <img class="ptes" src="assets/home-motor.png" alt="gambar" />
                        <p class="ptes">Silahkan isi form untuk lacak pengiriman</p>
                        <table id='empTable' style='width:100%;padding:5px;'>
                            <thead>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="container-fill" class="display-true">
            <h3 class="title-section"><span>Lihat</span> Detail Servis</h3>
            <div class="progress-history" style="color:rgb(173, 169, 169);">Maybe, coming soon ..</div>
        </div>
    </section>
    <div id="user-name"
        data-nama="{{ Auth::check() ? 'Hi, ' . Auth::user()->nama . ' → ' . Auth::user()->role : 'Hwaloo guest!' }}">
    </div>
    {{-- Script Console --}}
    @if ($currentUserRole !== 'guest')
        <script>
            var currentUserRole = @json($currentUserRole);
            console.log("Role yang login: ", currentUserRole);
        </script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengambil elemen dengan id "user-name" dan mendapatkan nilai atribut data-nama
            var userNameElement = document.getElementById("user-name");
            var namauser = userNameElement.dataset.nama;

            // Mengecek apakah namauser adalah "Hwaloo guest!"
            if (namauser === 'Hwaloo guest!') {
                // Menampilkan namauser dengan warna merah
                console.log("Tidak ada user yang login, " + "%c" + namauser,
                    "font-weight: bold;color: red;font-size:30px;");
            } else {
                // Menampilkan namauser dengan warna gold
                console.log("User yang login: " + "%c" + namauser, "font-weight: bold;color: gold;font-size:30px;");
            }
        });
    </script>
    {{-- Ambil Inputan --}}
    <script>
        // Cek apakah ada parameter 'search' pada URL
        const urlParams = new URLSearchParams(window.location.search);
        const searchValue = urlParams.get('search');

        // Jika parameter 'search' ada, isi nilai ke dalam input 'search'
        if (searchValue) {
            document.getElementById('search').value = searchValue;
            document.getElementById('but_search').click(); // Klik tombol "Cek Status" secara otomatis
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('assets/js/iziToast.js') }}"></script>
    {{-- Menu bar rspnsv --}}
    <script>
        const mobileMenu = document.querySelector(".mobile-menu"),
            listMenu = document.querySelector(".list-menu");
        mobileMenu.addEventListener("click", () => {
            mobileMenu.classList.toggle("active");
            listMenu.classList.toggle("active");
        });
    </script>
    {{-- Ajax Search --}}
    <script type='text/javascript'>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            var isLoading = false; // Menyimpan status loading
            // Tampilkan efek loading-overlay saat permintaan AJAX dimulai
            $(document).ajaxStart(function() {
                if (isLoading) {
                    showElem(); // Tampilkan efek loading-overlay
                }
            });

            // Sembunyikan efek loading-overlay saat permintaan AJAX selesai
            $(document).ajaxStop(function() {
                hideElem();
            });

            // Fetch all records
            $('#but_fetchall').click(function() {
                $('.body-card .ptes').hide();
                isLoading = true; // Set status loading menjadi true

                // Tampilkan efek loading-overlay
                showElem();

                // Tambahkan jeda detik sebelum permintaan AJAX dimulai
                setTimeout(function() {
                    // AJAX GET request
                    $.ajax({
                        url: 'getTransaction',
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            createRows(response,
                                true
                            ); // Set isFetchAll menjadi true saat fetch all record
                        }
                    });
                }, 500); // Timeout ms
            });

            // Search by kode/nama
            $('#but_search').on('click', function(event) {
                event.preventDefault(); // Mencegah aksi default tombol submit
                var transactionKode = $('#search').val().trim();
                if (transactionKode === '') {
                    iziToast.error({
                        title: 'Kode/nama',
                        message: ' tidak boleh kosong ygy ..',
                        position: 'topRight',
                        timeout: 2000,
                        target: '#search-error-toast',
                        closeOnClick: true,
                        displayMode: 2,
                    });
                    return; // Jika inputan kosong, hentikan eksekusi fungsi
                }

                if (transactionKode.length < 2) {
                    iziToast.error({
                        title: 'Minimal',
                        message: 'masukkan 2-3 karakter ..',
                        position: 'topRight',
                        timeout: 2000,
                        target: '#search-error-toast',
                        closeOnClick: true,
                        displayMode: 2,
                    });
                    return; // Jika inputan kurang dari 2 karakter, hentikan eksekusi fungsi
                }

                isLoading = true; // Set status loading menjadi true

                if (transactionKode !== '') {
                    $('.body-card .ptes').hide();

                    // Tampilkan efek loading-overlay
                    showElem();

                    // Tambahkan jeda 2 detik sebelum permintaan AJAX dimulai
                    setTimeout(function() {
                        // AJAX POST request
                        $.ajax({
                            url: 'getTransactionbyid',
                            type: 'post',
                            data: {
                                _token: CSRF_TOKEN,
                                transactionKode: transactionKode
                            },
                            dataType: 'json',
                            success: function(response) {
                                createRows(response,
                                    false
                                ); // Set isFetchAll menjadi false saat melakukan search
                            }
                        });
                    }, 500); // Timeout 2 detik (2000 ms)
                }
            });
        });

        function showElem() {
            var loadingOverlay = document.getElementsByClassName("loading-overlay")[0];
            if (loadingOverlay) {
                loadingOverlay.style.visibility = "visible";
                $('.body-card .loading-overlay').show();
            }
        }

        function hideElem() {
            var loadingOverlay = document.getElementsByClassName("loading-overlay")[0];
            if (loadingOverlay) {
                loadingOverlay.style.visibility = "hidden";
                $('.body-card .loading-overlay').hide();
            }
        }

        // Create table rows
        function createRows(response, isFetchAll) {
            var len = 0;
            $('#empTable tbody').empty(); // Empty <tbody>
            if (response['data'] != null) {
                len = response['data'].length;
            }

            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    var tr_str = "";

                    if (isFetchAll) {
                        // Tambahkan <th>No</th> jika melakukan fetch all record
                        tr_str += "<tr><th>No</th><td></td><td>" + (i + 1) + "</td></tr>";
                    }

                    var id = response['data'][i].id;
                    var kode = response['data'][i].kode;
                    var nama = response['data'][i].nama;
                    var merek = response['data'][i].merek;
                    var platnomer = response['data'][i].platnomer;
                    var status = response['data'][i].status;
                    var capitalizedStatus = status.charAt(0).toUpperCase() + status.slice(
                        1); // Merubah status menjadi huruf kapitalized

                    var statusClass = getStatusClass(status); //Add kelas CSS berdasarkan kondisi status

                    // Tambahkan data lain ke dalam baris tabel
                    tr_str += "<tr><th>Kode</th><th>:</th><td style='color:#301A4B;font-weight:bold;'>" + kode +
                        "</td></tr>" +
                        "<tr><th>Nama</th><th>:</th><td style='color:#6DB1BF;font-weight:bold;'>" + nama + "</td></tr>" +
                        "<tr><th>Merek</th><th>:</th><td>" + merek + "</td></tr>" +
                        "<tr><th>Plat Nomor</th><th>:</th><td>" + platnomer + "</td></tr>" +
                        "<tr><th>Status</th><th>:</th><td><a class='baten " + statusClass + "'>" + capitalizedStatus +
                        "</i></a></td></tr>";

                    if (isFetchAll) {
                        //Nambah tag <br> setiap data hanya saat fetch all
                        tr_str += "<tr><td colspan='3'><br><hr class='haer'></td></tr>";
                    }

                    $("#empTable tbody").append(tr_str);
                }
            } else {
                var tr_str = "<tr>" +
                    "<td align='center' colspan='4' class='tede'><b>No record found.</b></td>" +
                    "</tr>";

                $("#empTable tbody").append(tr_str);
            }
        }
        // Mendapatkan kelas CSS berdasarkan kondisi status
        function getStatusClass(status) {
            var statusClass = '';

            //Buat nentuin kelas CSS berdasarkan kondisi status
            if (status === 'Proses') {
                statusClass = 'baten-proses';
            } else if (status === 'Selesai') {
                statusClass = 'baten-selesai';
            }
            return statusClass;
        }
    </script>
</body>

</html>
