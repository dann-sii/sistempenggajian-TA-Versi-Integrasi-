-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2026 at 09:48 AM
-- Server version: 9.3.0
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penggajianst`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id_akun` varchar(2) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `role` enum('Admin','Pemilik/Pimpinan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penggajian`
--

CREATE TABLE `detail_penggajian` (
  `id_detail` varchar(3) NOT NULL,
  `id_penggajian` varchar(3) DEFAULT NULL,
  `tipe_komponen` enum('gaji_pokok','tunjangan','lembur','bonus','potongan') DEFAULT NULL,
  `keterangan` text,
  `jumlah` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_penggajian`
--

INSERT INTO `detail_penggajian` (`id_detail`, `id_penggajian`, `tipe_komponen`, `keterangan`, `jumlah`) VALUES
('D05', 'G02', 'gaji_pokok', '22 hari x 8 jam x 16.000', '2816000.00'),
('D06', 'G02', 'tunjangan', 'Tunjangan tetap', '200000.00'),
('D07', 'G02', 'lembur', 'Lembur 5 jam', '110000.00'),
('D08', 'G02', 'bonus', 'Bonus kinerja', '50000.00'),
('D09', 'G02', 'potongan', 'Potongan', '40000.00'),
('D10', 'G03', 'gaji_pokok', '21 hari x 8 jam x 16.000', '2688000.00'),
('D11', 'G03', 'bonus', 'Bonus', '75000.00'),
('D12', 'G03', 'potongan', 'Potongan', '25000.00'),
('D13', 'G04', 'gaji_pokok', '24 hari x 8 jam x 16.000', '3072000.00'),
('D14', 'G04', 'lembur', 'Lembur 12 jam', '264000.00'),
('D15', 'G04', 'bonus', 'Bonus', '100000.00'),
('D16', 'G04', 'potongan', 'Potongan', '90000.00'),
('D17', 'G05', 'gaji_pokok', '20 hari x 8 jam x 16.000', '2560000.00'),
('D18', 'G05', 'tunjangan', 'Tunjangan', '240000.00'),
('D19', 'G05', 'lembur', 'Lembur 8 jam', '176000.00'),
('D20', 'G05', 'bonus', 'Bonus', '50000.00'),
('D21', 'G05', 'potongan', 'Potongan', '30000.00'),
('D22', 'G06', 'gaji_pokok', '23 hari x 8 jam x 16.000', '2944000.00'),
('D23', 'G06', 'lembur', 'Lembur 15 jam', '330000.00'),
('D24', 'G06', 'bonus', 'Bonus', '125000.00'),
('D25', 'G06', 'potongan', 'Potongan', '60000.00'),
('D26', 'G07', 'gaji_pokok', '22 hari x 8 jam x 16.000', '2816000.00'),
('D27', 'G07', 'lembur', 'Lembur 2 jam', '44000.00'),
('D28', 'G07', 'potongan', 'Potongan', '20000.00'),
('D29', 'G08', 'gaji_pokok', '19 hari x 8 jam x 16.000', '2432000.00'),
('D30', 'G08', 'tunjangan', 'Tunjangan', '250000.00'),
('D31', 'G08', 'bonus', 'Bonus', '60000.00'),
('D32', 'G08', 'potongan', 'Potongan', '15000.00'),
('D33', 'G09', 'gaji_pokok', '25 hari x 8 jam x 16.000', '3200000.00'),
('D34', 'G09', 'lembur', 'Lembur 18 jam', '396000.00'),
('D35', 'G09', 'bonus', 'Bonus', '150000.00'),
('D36', 'G09', 'potongan', 'Potongan', '25000.00'),
('D37', 'G10', 'gaji_pokok', '22 hari x 8 jam x 16.000', '2816000.00'),
('D38', 'G10', 'lembur', 'Lembur 6 jam', '132000.00'),
('D39', 'G11', 'gaji_pokok', '21 hari x 8 jam x 16.000', '2688000.00'),
('D40', 'G11', 'tunjangan', 'Tunjangan', '250000.00'),
('D41', 'G11', 'lembur', 'Lembur 4 jam', '88000.00'),
('D42', 'G11', 'bonus', 'Bonus', '90000.00'),
('D43', 'G11', 'potongan', 'Potongan', '35000.00');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `npwp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text,
  `no_telp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `bagian` enum('Produksi','Peternakan') NOT NULL DEFAULT 'Produksi',
  `status_karyawan` enum('aktif','non-aktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `no_rekening` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nik`, `nama`, `npwp`, `alamat`, `no_telp`, `nama_bank`, `bagian`, `status_karyawan`, `no_rekening`) VALUES
('K01', '3401010101010001', 'Ahmad Fauzan', '12.345.678.9-012.000', 'Sleman, DI Yogyakarta', '081234567801', 'BRI', 'Produksi', 'aktif', '1234567890'),
('K02', '3401010101010002', 'Siti Nur Aini', '23.456.789.0-123.000', 'Sleman, DI Yogyakarta', '081234567802', 'BCA', 'Produksi', 'aktif', '2345678901'),
('K03', '3401010101010003', 'Rizky Pratama', '34.567.890.1-234.000', 'Kota Yogyakarta', '081234567803', 'BNI', 'Produksi', 'aktif', '3456789012'),
('K04', '3401010101010004', 'Dewi Lestari', '45.678.901.2-345.000', 'Bantul, DI Yogyakarta', '081234567804', 'Mandiri', 'Produksi', 'aktif', '4567890123'),
('K05', '3401010101010005', 'Andi Saputra', '56.789.012.3-456.000', 'Kulon Progo, DI Yogyakarta', '081234567805', 'BRI', 'Produksi', 'non-aktif', '5678901234'),
('K06', '3401010101010006', 'Tri Wulandari', '67.890.123.4-567.000', 'Sleman, DI Yogyakarta', '081234567806', 'BCA', 'Produksi', 'aktif', '6789012345'),
('K07', '3401010101010007', 'Budi Santoso', '78.901.234.5-678.000', 'Gunungkidul, DI Yogyakarta', '081234567807', 'BNI', 'Produksi', 'aktif', '7890123456'),
('K08', '3401010101010008', 'Nabila Rahma', '89.012.345.6-789.000', 'Kota Yogyakarta', '081234567808', 'Mandiri', 'Produksi', 'aktif', '8901234567'),
('K09', '3401010101010009', 'Fajar Maulana', '90.123.456.7-890.000', 'Bantul, DI Yogyakarta', '081234567809', 'BRI', 'Produksi', 'aktif', '9012345678'),
('K10', '3401010101010010', 'Linda Kurniawati', '01.234.567.8-901.000', 'Sleman, DI Yogyakarta', '081234567810', 'BCA', 'Produksi', 'aktif', '0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_penggajian`
--

CREATE TABLE `laporan_penggajian` (
  `id_laporan` varchar(3) NOT NULL,
  `id_penggajian` varchar(3) DEFAULT NULL,
  `id_detail` varchar(3) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jumlah_hari_kerja` int DEFAULT NULL,
  `jumlah_hari_lembur` int DEFAULT NULL,
  `gaji_pokok` decimal(10,2) DEFAULT NULL,
  `total_lembur` decimal(10,2) DEFAULT NULL,
  `total_bonus` decimal(10,2) DEFAULT NULL,
  `total_potongan` decimal(10,2) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `keterangan` text,
  `status_pembayaran` enum('draft','approved','paid','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `laporan_penggajian`
--

INSERT INTO `laporan_penggajian` (`id_laporan`, `id_penggajian`, `id_detail`, `nama`, `jumlah_hari_kerja`, `jumlah_hari_lembur`, `gaji_pokok`, `total_lembur`, `total_bonus`, `total_potongan`, `tanggal_pembayaran`, `keterangan`, `status_pembayaran`) VALUES
('L01', 'G02', 'D05', 'Siti Nur Aini', 22, 5, '2816000.00', '110000.00', '50000.00', '40000.00', '2026-01-31', 'Simulasi Jan 2026 K02', 'draft'),
('L02', 'G02', 'D06', 'Siti Nur Aini', 22, 5, '2816000.00', '110000.00', '50000.00', '40000.00', '2026-01-31', 'Simulasi Jan 2026 K02', 'draft'),
('L03', 'G02', 'D07', 'Siti Nur Aini', 22, 5, '2816000.00', '110000.00', '50000.00', '40000.00', '2026-01-31', 'Simulasi Jan 2026 K02', 'draft'),
('L04', 'G02', 'D08', 'Siti Nur Aini', 22, 5, '2816000.00', '110000.00', '50000.00', '40000.00', '2026-01-31', 'Simulasi Jan 2026 K02', 'draft'),
('L05', 'G02', 'D09', 'Siti Nur Aini', 22, 5, '2816000.00', '110000.00', '50000.00', '40000.00', '2026-01-31', 'Simulasi Jan 2026 K02', 'draft'),
('L06', 'G03', 'D10', 'Rizky Pratama', 21, 0, '2688000.00', '0.00', '75000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K03', 'draft'),
('L07', 'G03', 'D11', 'Rizky Pratama', 21, 0, '2688000.00', '0.00', '75000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K03', 'draft'),
('L08', 'G03', 'D12', 'Rizky Pratama', 21, 0, '2688000.00', '0.00', '75000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K03', 'draft'),
('L09', 'G04', 'D13', 'Dewi Lestari', 24, 12, '3072000.00', '264000.00', '100000.00', '90000.00', '2026-01-31', 'Simulasi Jan 2026 K04', 'draft'),
('L10', 'G04', 'D14', 'Dewi Lestari', 24, 12, '3072000.00', '264000.00', '100000.00', '90000.00', '2026-01-31', 'Simulasi Jan 2026 K04', 'draft'),
('L11', 'G04', 'D15', 'Dewi Lestari', 24, 12, '3072000.00', '264000.00', '100000.00', '90000.00', '2026-01-31', 'Simulasi Jan 2026 K04', 'draft'),
('L12', 'G04', 'D16', 'Dewi Lestari', 24, 12, '3072000.00', '264000.00', '100000.00', '90000.00', '2026-01-31', 'Simulasi Jan 2026 K04', 'draft'),
('L13', 'G05', 'D17', 'Andi Saputra', 20, 8, '2560000.00', '176000.00', '50000.00', '30000.00', '2026-01-31', 'Simulasi Jan 2026 K05', 'draft'),
('L14', 'G05', 'D18', 'Andi Saputra', 20, 8, '2560000.00', '176000.00', '50000.00', '30000.00', '2026-01-31', 'Simulasi Jan 2026 K05', 'draft'),
('L15', 'G05', 'D19', 'Andi Saputra', 20, 8, '2560000.00', '176000.00', '50000.00', '30000.00', '2026-01-31', 'Simulasi Jan 2026 K05', 'draft'),
('L16', 'G05', 'D20', 'Andi Saputra', 20, 8, '2560000.00', '176000.00', '50000.00', '30000.00', '2026-01-31', 'Simulasi Jan 2026 K05', 'draft'),
('L17', 'G05', 'D21', 'Andi Saputra', 20, 8, '2560000.00', '176000.00', '50000.00', '30000.00', '2026-01-31', 'Simulasi Jan 2026 K05', 'draft'),
('L18', 'G06', 'D22', 'Tri Wulandari', 23, 15, '2944000.00', '330000.00', '125000.00', '60000.00', '2026-01-31', 'Simulasi Jan 2026 K06', 'draft'),
('L19', 'G06', 'D23', 'Tri Wulandari', 23, 15, '2944000.00', '330000.00', '125000.00', '60000.00', '2026-01-31', 'Simulasi Jan 2026 K06', 'draft'),
('L20', 'G06', 'D24', 'Tri Wulandari', 23, 15, '2944000.00', '330000.00', '125000.00', '60000.00', '2026-01-31', 'Simulasi Jan 2026 K06', 'draft'),
('L21', 'G06', 'D25', 'Tri Wulandari', 23, 15, '2944000.00', '330000.00', '125000.00', '60000.00', '2026-01-31', 'Simulasi Jan 2026 K06', 'draft'),
('L22', 'G07', 'D26', 'Budi Santoso', 22, 2, '2816000.00', '44000.00', '0.00', '20000.00', '2026-01-31', 'Simulasi Jan 2026 K07', 'draft'),
('L23', 'G07', 'D27', 'Budi Santoso', 22, 2, '2816000.00', '44000.00', '0.00', '20000.00', '2026-01-31', 'Simulasi Jan 2026 K07', 'draft'),
('L24', 'G07', 'D28', 'Budi Santoso', 22, 2, '2816000.00', '44000.00', '0.00', '20000.00', '2026-01-31', 'Simulasi Jan 2026 K07', 'draft'),
('L25', 'G08', 'D29', 'Nabila Rahma', 19, 0, '2432000.00', '0.00', '60000.00', '15000.00', '2026-01-31', 'Simulasi Jan 2026 K08', 'draft'),
('L26', 'G08', 'D30', 'Nabila Rahma', 19, 0, '2432000.00', '0.00', '60000.00', '15000.00', '2026-01-31', 'Simulasi Jan 2026 K08', 'draft'),
('L27', 'G08', 'D31', 'Nabila Rahma', 19, 0, '2432000.00', '0.00', '60000.00', '15000.00', '2026-01-31', 'Simulasi Jan 2026 K08', 'draft'),
('L28', 'G08', 'D32', 'Nabila Rahma', 19, 0, '2432000.00', '0.00', '60000.00', '15000.00', '2026-01-31', 'Simulasi Jan 2026 K08', 'draft'),
('L29', 'G09', 'D33', 'Fajar Maulana', 25, 18, '3200000.00', '396000.00', '150000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K09', 'draft'),
('L30', 'G09', 'D34', 'Fajar Maulana', 25, 18, '3200000.00', '396000.00', '150000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K09', 'draft'),
('L31', 'G09', 'D35', 'Fajar Maulana', 25, 18, '3200000.00', '396000.00', '150000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K09', 'draft'),
('L32', 'G09', 'D36', 'Fajar Maulana', 25, 18, '3200000.00', '396000.00', '150000.00', '25000.00', '2026-01-31', 'Simulasi Jan 2026 K09', 'draft'),
('L33', 'G10', 'D37', 'Linda Kurniawati', 22, 6, '2816000.00', '132000.00', '0.00', '0.00', '2026-01-31', 'Simulasi Jan 2026 K10', 'draft'),
('L34', 'G10', 'D38', 'Linda Kurniawati', 22, 6, '2816000.00', '132000.00', '0.00', '0.00', '2026-01-31', 'Simulasi Jan 2026 K10', 'draft'),
('L35', 'G11', 'D39', 'Ahmad Fauzan', 21, 4, '2688000.00', '88000.00', '90000.00', '35000.00', '2026-01-31', 'Simulasi Jan 2026 K01', 'draft'),
('L36', 'G11', 'D40', 'Ahmad Fauzan', 21, 4, '2688000.00', '88000.00', '90000.00', '35000.00', '2026-01-31', 'Simulasi Jan 2026 K01', 'draft'),
('L37', 'G11', 'D41', 'Ahmad Fauzan', 21, 4, '2688000.00', '88000.00', '90000.00', '35000.00', '2026-01-31', 'Simulasi Jan 2026 K01', 'draft'),
('L38', 'G11', 'D42', 'Ahmad Fauzan', 21, 4, '2688000.00', '88000.00', '90000.00', '35000.00', '2026-01-31', 'Simulasi Jan 2026 K01', 'draft'),
('L39', 'G11', 'D43', 'Ahmad Fauzan', 21, 4, '2688000.00', '88000.00', '90000.00', '35000.00', '2026-01-31', 'Simulasi Jan 2026 K01', 'draft');

-- --------------------------------------------------------

--
-- Table structure for table `penggajian`
--

CREATE TABLE `penggajian` (
  `id_penggajian` varchar(3) NOT NULL,
  `id_karyawan` varchar(3) DEFAULT NULL,
  `periode_bulan` int DEFAULT NULL,
  `periode_tahun` int DEFAULT NULL,
  `jumlah_hari_kerja` int DEFAULT NULL,
  `jumlah_lembur` int DEFAULT NULL,
  `gaji_pokok` decimal(10,2) DEFAULT NULL,
  `total_lembur` decimal(10,2) DEFAULT NULL,
  `total_bonus` decimal(10,2) DEFAULT NULL,
  `total_potongan` decimal(10,2) DEFAULT NULL,
  `total_gaji_kotor` decimal(10,2) DEFAULT NULL,
  `total_gaji_bersih` decimal(10,2) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `status_pembayaran` enum('draft','approved','paid','cancelled') DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggajian`
--

INSERT INTO `penggajian` (`id_penggajian`, `id_karyawan`, `periode_bulan`, `periode_tahun`, `jumlah_hari_kerja`, `jumlah_lembur`, `gaji_pokok`, `total_lembur`, `total_bonus`, `total_potongan`, `total_gaji_kotor`, `total_gaji_bersih`, `tanggal_pembayaran`, `status_pembayaran`, `keterangan`) VALUES
('G02', 'K02', 1, 2026, 22, 5, '2816000.00', '110000.00', '50000.00', '40000.00', '2976000.00', '2936000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K02'),
('G03', 'K03', 1, 2026, 21, 0, '2688000.00', '0.00', '75000.00', '25000.00', '2763000.00', '2738000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K03'),
('G04', 'K04', 1, 2026, 24, 12, '3072000.00', '264000.00', '100000.00', '90000.00', '3436000.00', '3346000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K04'),
('G05', 'K05', 1, 2026, 20, 8, '2560000.00', '176000.00', '50000.00', '30000.00', '2836000.00', '2806000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K05'),
('G06', 'K06', 1, 2026, 23, 15, '2944000.00', '330000.00', '125000.00', '60000.00', '3399000.00', '3339000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K06'),
('G07', 'K07', 1, 2026, 22, 2, '2816000.00', '44000.00', '0.00', '20000.00', '2860000.00', '2840000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K07'),
('G08', 'K08', 1, 2026, 19, 0, '2432000.00', '0.00', '60000.00', '15000.00', '2742000.00', '2727000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K08'),
('G09', 'K09', 1, 2026, 25, 18, '3200000.00', '396000.00', '150000.00', '25000.00', '3746000.00', '3721000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K09'),
('G10', 'K10', 1, 2026, 22, 6, '2816000.00', '132000.00', '0.00', '0.00', '2948000.00', '2948000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K10'),
('G11', 'K01', 1, 2026, 21, 4, '2688000.00', '88000.00', '90000.00', '35000.00', '3038000.00', '3003000.00', '2026-01-31', 'draft', 'Simulasi Jan 2026 K01');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` varchar(3) NOT NULL,
  `id_karyawan` varchar(3) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status_kehadiran` enum('hadir','izin','sakit','cuti','alpha') DEFAULT NULL,
  `jam_lembur` decimal(4,2) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id_presensi`, `id_karyawan`, `tanggal`, `jam_masuk`, `jam_keluar`, `status_kehadiran`, `jam_lembur`, `keterangan`) VALUES
('P01', 'K02', '2026-01-02', '08:00:00', '16:00:00', 'hadir', '0.00', 'Hadir normal'),
('P02', 'K03', '2026-01-02', '08:00:00', '16:00:00', 'hadir', '2.00', 'Lembur rekap harian'),
('P03', 'K04', '2026-01-02', '00:00:00', '00:00:00', 'izin', '0.00', 'Izin keperluan keluarga'),
('P04', 'K05', '2026-01-02', '08:00:00', '16:00:00', 'hadir', '1.00', 'Lembur pengecekan produksi'),
('P05', 'K06', '2026-01-02', '08:00:00', '16:00:00', 'hadir', '0.00', 'Hadir normal'),
('P06', 'K07', '2026-01-02', '00:00:00', '00:00:00', 'sakit', '0.00', 'Sakit (surat keterangan)'),
('P07', 'K08', '2026-01-02', '08:00:00', '16:00:00', 'hadir', '1.00', 'Lembur packing'),
('P08', 'K09', '2026-01-02', '08:00:00', '16:00:00', 'hadir', '0.00', 'Hadir normal'),
('P09', 'K10', '2026-01-02', '00:00:00', '00:00:00', 'alpha', '0.00', 'Tidak hadir tanpa keterangan'),
('P10', 'K02', '2026-01-03', '08:00:00', '16:00:00', 'hadir', '1.00', 'Lembur tambahan');

-- --------------------------------------------------------

--
-- Table structure for table `produk_resep`
--

CREATE TABLE `produk_resep` (
  `id_produk` varchar(4) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `target_produksi` decimal(10,2) NOT NULL,
  `satuan_produk` varchar(50) NOT NULL,
  `susu_liter` decimal(10,2) DEFAULT '0.00',
  `gula_gram` decimal(10,2) DEFAULT '0.00',
  `perasa_jenis` varchar(50) DEFAULT NULL,
  `perasa_gram` decimal(10,2) DEFAULT '0.00',
  `status` enum('aktif','non-aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk_resep`
--

INSERT INTO `produk_resep` (`id_produk`, `nama_produk`, `target_produksi`, `satuan_produk`, `susu_liter`, `gula_gram`, `perasa_jenis`, `perasa_gram`, `status`) VALUES
('PR01', 'Susu Kambing Original', '40.00', 'pcs / 1 kg', '5.00', '250.00', 'Vanila', '15.00', 'aktif'),
('PR02', 'Susu Kambing Jahe', '40.00', 'pcs / 1 kg', '5.00', '250.00', 'Jahe', '20.00', 'aktif'),
('PR03', 'Susu Kambing Coklat', '40.00', 'pcs / 1 kg', '5.00', '200.00', 'Coklat', '25.00', 'aktif'),
('PR04', 'Susu Kambing Karamel', '40.00', 'pcs / 1 kg', '5.00', '225.00', 'Karamel', '20.00', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas_karyawan`
--

CREATE TABLE `aktivitas_karyawan` (
  `id_aktivitas` varchar(4) NOT NULL,
  `id_karyawan` varchar(3) NOT NULL,
  `id_produk` varchar(4) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_produksi` decimal(10,2) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aktivitas_karyawan`
--

INSERT INTO `aktivitas_karyawan` (`id_aktivitas`, `id_karyawan`, `id_produk`, `tanggal`, `jumlah_produksi`, `keterangan`) VALUES
('AK01', 'K02', 'PR01', '2026-01-02', '60.00', 'Produksi harian dummy'),
('AK02', 'K02', 'PR02', '2026-01-03', '75.00', 'Produksi harian dummy'),
('AK03', 'K03', 'PR03', '2026-01-02', '55.00', 'Produksi harian dummy');

-- --------------------------------------------------------

--
-- Table structure for table `tarif_gaji_bonus`
--

CREATE TABLE `tarif_gaji_bonus` (
  `id_tarif_bonus` varchar(4) NOT NULL,
  `batas_normal` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `tarif` decimal(10,2) DEFAULT NULL,
  `status` enum('aktif','non-aktif') DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tarif_gaji_bonus`
--

INSERT INTO `tarif_gaji_bonus` (`id_tarif_bonus`, `batas_normal`, `satuan`, `nama`, `tarif`, `status`, `keterangan`) VALUES
('TB01', '10.00', 'liter', 'Bonus Penggunaan Susu', '2500.00', 'aktif', 'Dihitung dari selisih produksi susu berhasil di atas 10 liter per hari'),
('TB02', '500.00', 'gram', 'Bonus Penggunaan Gula', '100.00', 'aktif', 'Dihitung dari selisih pemakaian gula di atas 500 gram per hari'),
('TB03', '20.00', 'gram', 'Bonus Penggunaan Perasa Jahe', '150.00', 'aktif', 'Dihitung dari selisih perasa jahe di atas batas normal per hari'),
('TB04', '25.00', 'gram', 'Bonus Penggunaan Perasa Coklat', '175.00', 'aktif', 'Dihitung dari selisih perasa coklat di atas batas normal per hari'),
('TB05', '15.00', 'gram', 'Bonus Penggunaan Perasa Vanila', '200.00', 'aktif', 'Dihitung dari selisih perasa vanila di atas batas normal per hari'),
('TB06', '20.00', 'gram', 'Bonus Penggunaan Perasa Karamel', '180.00', 'aktif', 'Dihitung dari selisih perasa karamel di atas batas normal per hari'),
('TB07', '1.00', 'kali', 'Bonus THR', '1500000.00', 'aktif', 'Bonus hari raya yang diberikan satu kali pada periode hari raya jika ditetapkan admin'),
('TB08', '5.00', 'liter', 'Bonus Produksi Susu Peternakan', '5000.00', 'aktif', 'Diberikan untuk karyawan peternakan dari selisih produksi susu harian di atas 5 liter');

-- --------------------------------------------------------

--
-- Table structure for table `tarif_gaji_lembur`
--

CREATE TABLE `tarif_gaji_lembur` (
  `id_tarif_lembur` varchar(4) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `tarif` decimal(10,2) DEFAULT NULL,
  `keterangan` text,
  `status` enum('aktif','non-aktif') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tarif_gaji_lembur`
--

INSERT INTO `tarif_gaji_lembur` (`id_tarif_lembur`, `nama`, `tarif`, `keterangan`, `status`) VALUES
('GL01', 'Lembur per Jam', '20000.00', 'Tarif lembur dihitung per jam', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tarif_gaji_pokok`
--

CREATE TABLE `tarif_gaji_pokok` (
  `id_tarif_pokok` varchar(4) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `tarif` decimal(10,2) DEFAULT NULL,
  `status` enum('aktif','non-aktif') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tarif_gaji_pokok`
--

INSERT INTO `tarif_gaji_pokok` (`id_tarif_pokok`, `nama`, `tarif`, `status`) VALUES
('GP01', 'Gaji Pokok per Jam', '15000.00', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tarif_potongan`
--

CREATE TABLE `tarif_potongan` (
  `id_potongan` varchar(3) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tarif` decimal(10,2) DEFAULT NULL,
  `tipe_potongan` enum('tetap','persentase','variabel') DEFAULT NULL,
  `status` enum('aktif','non-aktif') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tarif_potongan`
--

INSERT INTO `tarif_potongan` (`id_potongan`, `nama`, `tarif`, `tipe_potongan`, `status`) VALUES
('P01', 'Potongan BPJS', '1.00', 'persentase', 'aktif'),
('P02', 'Potongan Keterlambatan', '10000.00', 'tetap', 'aktif'),
('P03', 'Potongan Pinjaman Karyawan', '5.00', 'persentase', 'aktif'),
('P04', 'Potongan Kerusakan/Barang Hilang', '0.00', 'variabel', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indexes for table `detail_penggajian`
--
ALTER TABLE `detail_penggajian`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_penggajian` (`id_penggajian`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `laporan_penggajian`
--
ALTER TABLE `laporan_penggajian`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_penggajian` (`id_penggajian`),
  ADD KEY `id_detail` (`id_detail`);

--
-- Indexes for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD PRIMARY KEY (`id_penggajian`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indexes for table `produk_resep`
--
ALTER TABLE `produk_resep`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `aktivitas_karyawan`
--
ALTER TABLE `aktivitas_karyawan`
  ADD PRIMARY KEY (`id_aktivitas`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `tarif_gaji_bonus`
--
ALTER TABLE `tarif_gaji_bonus`
  ADD PRIMARY KEY (`id_tarif_bonus`);

--
-- Indexes for table `tarif_gaji_lembur`
--
ALTER TABLE `tarif_gaji_lembur`
  ADD PRIMARY KEY (`id_tarif_lembur`);

--
-- Indexes for table `tarif_gaji_pokok`
--
ALTER TABLE `tarif_gaji_pokok`
  ADD PRIMARY KEY (`id_tarif_pokok`);

--
-- Indexes for table `tarif_potongan`
--
ALTER TABLE `tarif_potongan`
  ADD PRIMARY KEY (`id_potongan`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_penggajian`
--
ALTER TABLE `detail_penggajian`
  ADD CONSTRAINT `detail_penggajian_ibfk_1` FOREIGN KEY (`id_penggajian`) REFERENCES `penggajian` (`id_penggajian`);

--
-- Constraints for table `laporan_penggajian`
--
ALTER TABLE `laporan_penggajian`
  ADD CONSTRAINT `laporan_penggajian_ibfk_1` FOREIGN KEY (`id_penggajian`) REFERENCES `penggajian` (`id_penggajian`),
  ADD CONSTRAINT `laporan_penggajian_ibfk_2` FOREIGN KEY (`id_detail`) REFERENCES `detail_penggajian` (`id_detail`);

--
-- Constraints for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD CONSTRAINT `penggajian_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);

--
-- Constraints for table `aktivitas_karyawan`
--
ALTER TABLE `aktivitas_karyawan`
  ADD CONSTRAINT `aktivitas_karyawan_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`),
  ADD CONSTRAINT `aktivitas_karyawan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk_resep` (`id_produk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
