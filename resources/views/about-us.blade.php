@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">About Us</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">About Us</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- About Us Section -->
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center mb-4">Sejarah Perpustakaan Sultan Abdul Samad</h2>
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/library-photos/library.png') }}" alt="Library Image" class="img-fluid rounded">
                        </div>
                        <h5><strong>Sejarah Awal Perpustakaan Sultan Abdul Samad: 1921-1968</strong></h5>
                        <p>
                            Perjalanan sejarah Perpustakaan Sultan Abdul Samad bermula seawal penubuhan Sekolah Pertanian
                            Malaya pada 21 Mei 1931, dengan matlamat menyokong aktiviti pembelajaran dan penyelidikan.
                            Ketika itu, perpustakaan hanyalah sebuah bilik kecil dengan koleksi buku yang terhad, tanpa
                            kemudahan asas seperti tempat duduk yang sesuai. Keadaan ini menyebabkan pelajar terpaksa
                            membawa pulang buku ke asrama masing-masing untuk menggunakannya.
                        </p>
                        <p>
                            Transformasi besar bermula pada tahun 1956 apabila Allahyarham Tan Sri Senu Abdul Rahman, Ahli
                            Majlis Penasihat Kolej, menyeru kepada pembaikan perpustakaan. Dengan peruntukan awal sebanyak
                            RM2,500 pada tahun 1959, pembelian buku, perabot, dan peralatan perpustakaan mula dilaksanakan.
                            Sumbangan koleksi turut diterima daripada pelbagai pihak seperti Jabatan Pertanian dan Yayasan
                            Asia, menjadikan koleksi perpustakaan meningkat kepada lebih 3,000 buku pada tahun 1964.
                        </p>
                        <p>
                            Pada tahun 1965, Encik Lin Meng Fou dilantik untuk memacu penambahbaikan dalam sistem pengurusan
                            perpustakaan. Antara usaha beliau termasuk memperkenalkan sistem pengkelasan dan penyusunan buku
                            yang lebih teratur dan sistematik. Hasilnya, koleksi perpustakaan terus berkembang pesat,
                            mencapai sebanyak 7,000 naskhah buku menjelang akhir tahun 1967.
                        </p>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
