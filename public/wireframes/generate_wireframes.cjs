const fs = require('fs');
const path = require('path');
const dir = 'd:/Folder Antigravity/Sistem Penggajian v1.0/public/wireframes';
if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });

// Clear old files from the directory first
try {
    const oldFiles = fs.readdirSync(dir);
    for (const file of oldFiles) {
        if (file.endsWith('.html')) {
            fs.unlinkSync(path.join(dir, file));
        }
    }
} catch (e) {
    console.log("Could not clear old files:", e.message);
}

const generateHTML = (title, role, activeIndex, content, topRightControls = "") => {
    let sidebarHTML = '';
    
    if (role === 'Admin') {
        const adminMenus = [
            { name: 'Dashboard', idx: 1 },
            { name: 'Data Karyawan', idx: 2 },
            { name: 'Data Presensi', idx: 3 },
            { name: 'Data Produk', idx: 4 },
            { name: 'Aktivitas Karyawan', idx: 5 },
            { name: 'Gaji Pokok & Lembur', idx: 6 },
            { name: 'Gaji Bonus', idx: 7 },
            { name: 'Potongan Gaji', idx: 8 },
            { name: 'Penggajian', idx: 9 },
            { name: 'Laporan Penggajian', idx: 10 }
        ];
        
        sidebarHTML += '<div class="wf-menu-header">Menu Utama</div>';
        adminMenus.forEach(item => {
            const isActive = activeIndex === item.idx;
            sidebarHTML += `<div class="wf-menu-item ${isActive ? 'active' : ''}">- ${item.name}</div>`;
        });
    } else {
        const pimpinanMenus = [
            { name: 'Dashboard', idx: 1 },
            { name: 'Data Karyawan', idx: 2 },
            { name: 'Laporan Penggajian', idx: 3 },
            { name: 'Kelola Pengguna', idx: 4 }
        ];
        
        sidebarHTML += '<div class="wf-menu-header">Menu Pimpinan</div>';
        pimpinanMenus.forEach(item => {
            const isActive = activeIndex === item.idx;
            sidebarHTML += `<div class="wf-menu-item ${isActive ? 'active' : ''}">- ${item.name}</div>`;
        });
    }

    return `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireframe - ${title}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #fafafa; color: #000000; font-family: monospace; zoom: 0.85; }
        .wf-border { border: 2px solid #000000; }
        .wf-box { border: 2px solid #000000; background-color: #ffffff; display: flex; align-items: center; justify-content: center; color: #000000; font-weight: bold; text-align: center; }
        .wf-sidebar { width: 288px; min-height: 100vh; border-right: 2px solid #000000; background-color: #ffffff; position: fixed; left: 0; top: 0; }
        .wf-header { height: 80px; border-bottom: 2px solid #000000; background-color: #ffffff; position: fixed; left: 288px; right: 0; top: 0; display: flex; align-items: center; justify-content: space-between; z-index: 10; }
        .wf-menu-item { padding: 0.6rem 1rem; border: 2px solid transparent; border-radius: 0.5rem; margin-bottom: 0.25rem; font-weight: bold; cursor: default; font-size: 0.9rem; }
        .wf-menu-item.active { border: 2px solid #000000; background-color: #f3f4f6; }
        .wf-menu-item.active::before { content: ""; width: 4px; height: 1.5rem; background-color: #000; margin-right: 8px; border-radius: 9999px; }
        .wf-menu-header { font-size: 0.75rem; color: #666; margin-top: 1.5rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 2px; }
        table { border-collapse: collapse; width: 100%; text-align: center; margin-bottom: 1rem; }
        th, td { border: 1px solid #000000; padding: 0.75rem; }
        thead { border-bottom: 2px solid #000000; background-color: #f0f0f0; }
    </style>
</head>
<body class="flex">
    <!-- Sidebar (Fixed) -->
    <div class="wf-sidebar flex flex-col justify-between p-6">
        <div>
            <!-- Profile Area -->
            <div class="flex flex-col items-center pb-6 border-b-2 border-black mb-6">
                <div class="w-20 h-20 rounded-full wf-box mb-4">FOTO</div>
                <div class="text-xs uppercase tracking-widest text-gray-500">Selamat Datang</div>
                <div class="font-bold text-sm uppercase">${role}</div>
            </div>
            
            <!-- Menu -->
            <nav class="space-y-1">
                ${sidebarHTML}
            </nav>
        </div>
        
        <!-- Logout Button -->
        <div class="wf-box p-3 cursor-default font-bold text-center uppercase tracking-wider">
            KELUAR SISTEM
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="ml-72 flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <div class="wf-header px-8">
            <div class="wf-box px-4 py-2 text-sm font-bold uppercase tracking-widest">Sahabat Ternak</div>
            <div class="flex items-center space-x-6 text-sm">
                <div class="flex items-center space-x-2 text-black font-bold border-r border-black pr-6">
                    <span>--:--:--</span>
                </div>
                <div class="wf-box px-3 py-1 text-xs font-black uppercase">${role.toUpperCase()}</div>
            </div>
        </div>
        
        <!-- Page Content -->
        <main class="flex-1 p-10 mt-20">
            <div class="flex items-start justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tight border-l-4 border-black pl-3 py-0.5">
                        ${title}
                    </h1>
                </div>
                ${topRightControls}
            </div>
            
            ${content}
        </main>
    </div>
</body>
</html>`;
};

// 0. Login Page Content (Special layout)
const generateLoginHTML = () => {
    return `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireframe - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #fafafa; color: #000000; font-family: monospace; zoom: 0.85; }
        .wf-border { border: 2px solid #000000; }
        .wf-box { border: 2px solid #000000; background-color: #ffffff; display: flex; align-items: center; justify-content: center; color: #000000; font-weight: bold; text-align: center; }
    </style>
</head>
<body class="h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-[460px] rounded-[32px] overflow-hidden wf-border shadow-md">
        <!-- Oval Header Block -->
        <div class="relative w-full h-[150px] border-b-2 border-black flex items-center justify-center bg-gray-50" style="border-radius: 0 0 50% 50% / 0 0 40px 40px;">
            <div class="w-[110px] h-[110px] bg-white rounded-full absolute -bottom-12 z-10 wf-border flex items-center justify-center font-bold text-xs uppercase">
                LOGO
            </div>
        </div>
        
        <!-- Form Fields -->
        <div class="px-8 pt-16 pb-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-black uppercase tracking-tight">SAHABAT TERNAK</h1>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase">Masukkan akun anda</p>
            </div>
            
            <div class="space-y-4">
                <!-- Username -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-widest pl-1">ID Pengguna / Email</label>
                    <div class="wf-box w-full py-3 justify-start px-4 text-xs text-gray-400 font-bold uppercase">
                        [ID PENGGUNA]
                    </div>
                </div>
                
                <!-- Password -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center pl-1 pr-1">
                        <label class="block text-xs font-bold uppercase tracking-widest">Kata Sandi</label>
                        <span class="text-xs font-bold uppercase">Lupa sandi?</span>
                    </div>
                    <div class="wf-box w-full py-3 justify-start px-4 text-xs text-gray-400 font-bold uppercase">
                        [KATA SANDI]
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="pt-2">
                    <button class="w-full bg-black text-white py-3.5 font-bold text-sm uppercase tracking-widest flex justify-center items-center gap-2">
                        <span>MASUK</span>
                    </button>
                </div>
            </div>
            
            <div class="mt-6 text-center text-xs font-bold text-gray-400 tracking-wider">
                <p>&copy; 2026 PT. Sahabat Ternak</p>
            </div>
        </div>
    </div>
</body>
</html>`;
};

// 1. Dashboard content (Blank metrics and charts)
const cDashboard = `
<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="wf-border bg-white p-7 relative group">
        <p class="text-xs font-black uppercase tracking-widest text-gray-500">KARYAWAN AKTIF</p>
        <p class="mt-3 text-4xl font-black">-</p>
    </div>
    <div class="wf-border bg-white p-7 relative group">
        <p class="text-xs font-black uppercase tracking-widest text-gray-500">KARYAWAN INAKTIF</p>
        <p class="mt-3 text-4xl font-black">-</p>
    </div>
    <div class="wf-border bg-white p-7 relative group">
        <p class="text-xs font-black uppercase tracking-widest text-gray-500">DATA PRESENSI</p>
        <div class="mt-3 flex items-end gap-2">
            <span class="text-4xl font-black">-</span>
            <span class="pb-1 text-2xl font-black text-gray-400">/</span>
            <span class="pb-1 text-2xl font-black text-gray-400">-</span>
        </div>
    </div>
</div>
<div class="grid grid-cols-[1.1fr_0.9fr] gap-6">
    <section class="wf-border bg-white p-8">
        <h2 class="text-base font-bold uppercase tracking-wider border-b border-black pb-4 mb-4">STATUS PRESENSI</h2>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs font-bold mb-1"><span>Hadir</span><span>-</span></div>
                <div class="h-3 bg-gray-100 wf-border"><div class="h-full bg-black w-[0%]"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1"><span>Izin</span><span>-</span></div>
                <div class="h-3 bg-gray-100 wf-border"><div class="h-full bg-black w-[0%]"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1"><span>Sakit</span><span>-</span></div>
                <div class="h-3 bg-gray-100 wf-border"><div class="h-full bg-black w-[0%]"></div></div>
            </div>
        </div>
    </section>
    <section class="wf-border bg-white p-8">
        <h2 class="text-base font-bold uppercase tracking-wider border-b border-black pb-4 mb-4">BAGIAN KARYAWAN AKTIF</h2>
        <div class="space-y-4">
            <div class="wf-border bg-white p-5 flex justify-between items-center">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">PRODUKSI</span>
                    <p class="mt-1 text-3xl font-black">-</p>
                </div>
                <div class="w-12 h-12 wf-box">ICON</div>
            </div>
            <div class="wf-border bg-white p-5 flex justify-between items-center">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">PETERNAKAN</span>
                    <p class="mt-1 text-3xl font-black">-</p>
                </div>
                <div class="w-12 h-12 wf-box">ICON</div>
            </div>
            <div class="wf-border bg-white p-5 flex justify-between items-center">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">PACKING</span>
                    <p class="mt-1 text-3xl font-black">-</p>
                </div>
                <div class="w-12 h-12 wf-box">ICON</div>
            </div>
        </div>
    </section>
</div>`;

// 2. Data Karyawan content
const cDataKaryawan = `
<div class="flex justify-between items-end mb-6">
    <div class="flex items-end space-x-6">
        <div class="wf-box px-4 py-2 text-xs font-bold">[FILTER STATUS]</div>
        <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari...</div>
    </div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ Tambah</div>
</div>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NIK</th>
                <th>NAMA</th>
                <th>NPWP</th>
                <th>ALAMAT</th>
                <th>NO. TELP</th>
                <th>BAGIAN</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Bagian]</div></td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td><div class="flex justify-center space-x-2"><div class="wf-box w-12 h-6 text-xs cursor-default">Ubah</div><div class="wf-box w-12 h-6 text-xs cursor-default">Hapus</div></div></td>
            </tr>
        </tbody>
    </table>
</div>`;

// 3. Data Presensi content
const cDataPresensi = `
<div class="flex justify-between items-end mb-6">
    <div class="flex items-end space-x-6">
        <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari nama karyawan...</div>
        <div class="wf-box px-4 py-2 text-xs font-bold">[PILIH TANGGAL]</div>
    </div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ Susulan / Koreksi</div>
</div>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>TANGGAL</th>
                <th>JAM MASUK</th>
                <th>JAM KELUAR</th>
                <th>KEHADIRAN</th>
                <th>LEMBUR</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td><div class="wf-box text-xs w-20 py-0.5 inline-block">--:--</div></td>
                <td><div class="wf-box text-xs w-20 py-0.5 inline-block">--:--</div></td>
                <td><div class="flex justify-center space-x-1"><div class="wf-box w-6 h-6 text-xs">H</div><div class="wf-box w-6 h-6 text-xs">I</div><div class="wf-box w-6 h-6 text-xs">S</div><div class="wf-box w-6 h-6 text-xs">C</div><div class="wf-box w-6 h-6 text-xs">A</div></div></td>
                <td><div class="wf-box text-xs w-16 py-0.5 inline-block">- JAM</div></td>
                <td><div class="wf-box w-12 h-6 text-xs mx-auto cursor-default">Ubah</div></td>
            </tr>
        </tbody>
    </table>
</div>`;

// 4. Data Produk content
const cDataProduk = `
<div class="flex justify-between items-end mb-6">
    <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari berdasarkan nama produk...</div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ Tambah</div>
</div>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA PRODUK</th>
                <th>TARGET PRODUKSI</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td>
                    <div class="flex justify-center space-x-2">
                        <div class="wf-box px-2 py-1 text-xs cursor-default">Detail</div>
                        <div class="wf-box px-2 py-1 text-xs cursor-default">Ubah</div>
                        <div class="wf-box px-2 py-1 text-xs cursor-default">Hapus</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>`;

// 5. Aktivitas Karyawan content
const cAktivitasKaryawan = `
<div class="flex justify-between items-center wf-border bg-white px-6 py-4 mb-6">
    <div class="flex items-center gap-12">
        <div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Baris Aktivitas</span>
            <p class="text-xl font-black mt-0.5">-</p>
        </div>
        <div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Status Memenuhi</span>
            <p class="text-xl font-black mt-0.5">-</p>
        </div>
        <div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Status Kurang</span>
            <p class="text-xl font-black mt-0.5">-</p>
        </div>
    </div>
</div>
<div class="wf-border bg-white overflow-hidden">
    <div class="flex justify-between items-center border-b border-black p-4 bg-gray-50">
        <div>
            <h2 class="text-base font-bold uppercase tracking-widest">[Bagian] - [Tanggal]</h2>
            <p class="text-xs text-gray-500 font-bold mt-1">Target Produksi: - gram / Target Setoran: - liter</p>
        </div>
        <div class="flex gap-4">
            <div class="wf-box w-64 text-xs font-bold justify-start px-3">Cari nama/NIK...</div>
            <button class="wf-box px-6 py-2 text-xs font-bold cursor-default">Simpan Rekap</button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th>Susu (Liter)</th>
                <th>Gula (Gram)</th>
                <th>Rasa</th>
                <th>Perasa (Gram)</th>
                <th>Hasil Produksi (Gram)</th>
                <th>Status Capaian</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left font-bold">[Nama Karyawan]<br><span class="text-xs text-gray-400 font-normal">NIK: -</span></td>
                <td><div class="wf-box w-24 h-8 text-xs ml-auto">Jml Susu</div></td>
                <td><div class="wf-box w-24 h-8 text-xs ml-auto">Jml Gula</div></td>
                <td><div class="wf-box w-24 h-8 text-xs ml-auto">Pilih Rasa</div></td>
                <td><div class="wf-box w-24 h-8 text-xs ml-auto">Jml Perasa</div></td>
                <td><div class="wf-box w-24 h-8 text-xs ml-auto">Hasil (g)</div></td>
                <td><div class="wf-box text-xs px-2 py-1 mx-auto">[Status]</div></td>
            </tr>
        </tbody>
    </table>
</div>`;

// 6. Gaji Pokok & Lembur content
const cGajiPokokLembur = `
<div class="flex justify-between items-end mb-6">
    <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari berdasarkan nama tarif...</div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ Tambah</div>
</div>
<h2 class="text-base font-bold uppercase tracking-wider mb-2 border-l-4 border-black pl-2">Gaji Pokok</h2>
<div class="bg-white wf-border overflow-hidden mb-8">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>TARIF</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td>
                    <div class="flex justify-center space-x-2">
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Ubah</div>
                        <div class="wf-box w-24 h-6 text-xs cursor-default">Nonaktifkan</div>
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Hapus</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<h2 class="text-base font-bold uppercase tracking-wider mb-2 border-l-4 border-black pl-2">Gaji Lembur</h2>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>TARIF</th>
                <th>KETERANGAN</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td>
                    <div class="flex justify-center space-x-2">
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Ubah</div>
                        <div class="wf-box w-24 h-6 text-xs cursor-default">Nonaktifkan</div>
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Hapus</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>`;

// 7. Gaji Bonus content
const cGajiBonus = `
<div class="flex justify-between items-end mb-6">
    <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari berdasarkan nama bonus...</div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ Tambah</div>
</div>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>TARIF / NOMINAL</th>
                <th>TIPE BONUS</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td>
                    <div class="flex justify-center space-x-2">
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Ubah</div>
                        <div class="wf-box w-24 h-6 text-xs cursor-default">Nonaktifkan</div>
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Hapus</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>`;

// 8. Potongan Gaji content
const cPotonganGaji = `
<div class="flex justify-between items-end mb-6">
    <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari...</div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ Tambah</div>
</div>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA POTONGAN</th>
                <th>TARIF / NOMINAL</th>
                <th>TIPE POTONGAN</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td>
                    <div class="flex justify-center space-x-2">
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Ubah</div>
                        <div class="wf-box w-24 h-6 text-xs cursor-default">Nonaktifkan</div>
                        <div class="wf-box w-12 h-6 text-xs cursor-default">Hapus</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>`;

// 9. Penggajian content (Data cleared/blank placeholders)
const cPenggajian = `
<div class="wf-border bg-white p-4 mb-6">
    <div class="grid grid-cols-[minmax(0,1fr)_360px] gap-4">
        <!-- Detail Left Panel -->
        <div class="min-w-0">
            <div class="flex items-start justify-between gap-4 border border-black bg-gray-50 px-4 py-3">
                <div>
                    <h2 class="text-3xl font-black">[Nama Karyawan]</h2>
                    <p class="text-sm font-bold text-gray-500 mt-1">[Bagian] - [Periode]</p>
                </div>
                <span class="wf-box px-4 py-1.5 text-sm font-black uppercase">[Status Bayar]</span>
            </div>
            
            <div class="mt-3 grid grid-cols-3 gap-3">
                <div class="border border-black p-3 bg-white">
                    <div class="flex items-center gap-2 border-b border-black pb-2 mb-2 font-bold text-xs uppercase">IDENTITAS</div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between"><span>ID Penggajian</span><span class="font-bold">-</span></div>
                        <div class="flex justify-between"><span>ID Karyawan</span><span class="font-bold">-</span></div>
                        <div class="flex justify-between"><span>Tanggal Bayar</span><span class="font-bold">-</span></div>
                    </div>
                </div>
                <div class="border border-black p-3 bg-white">
                    <div class="flex items-center gap-2 border-b border-black pb-2 mb-2 font-bold text-xs uppercase">Periode & Waktu</div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between"><span>Bulan</span><span class="font-bold">-</span></div>
                        <div class="flex justify-between"><span>Tahun</span><span class="font-bold">-</span></div>
                        <div class="flex justify-between"><span>Hari Kerja</span><span class="font-bold">- hari</span></div>
                        <div class="flex justify-between"><span>Lembur</span><span class="font-bold">- jam</span></div>
                    </div>
                </div>
                <div class="border border-black p-3 bg-white">
                    <div class="flex items-center gap-2 border-b border-black pb-2 mb-2 font-bold text-xs uppercase">Komponen</div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between"><span>Gaji Pokok</span><span class="font-bold">Rp -</span></div>
                        <div class="flex justify-between"><span>Lembur</span><span class="font-bold">Rp -</span></div>
                        <div class="flex justify-between"><span>Bonus</span><span class="font-bold">Rp -</span></div>
                        <div class="flex justify-between"><span>Potongan</span><span class="font-bold">Rp -</span></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 flex justify-between text-xs font-bold uppercase">
                <span>Rincian Komponen Gaji</span>
                <span class="text-gray-400">Menyesuaikan jumlah rincian</span>
            </div>
            <div class="mt-2 grid grid-cols-4 gap-2">
                <div class="wf-box p-3 text-xs">Gaji Pokok (Detail)</div>
                <div class="wf-box p-3 text-xs">Lembur (Detail)</div>
                <div class="wf-box p-3 text-xs">Bonus (Detail)</div>
                <div class="wf-box p-3 text-xs">Potongan (Detail)</div>
            </div>
        </div>
        
        <!-- Totals Right Panel -->
        <div class="grid grid-cols-2 grid-rows-[1fr_1fr_auto_auto] gap-3 border border-black bg-gray-50 p-3">
            <div class="col-span-2 flex flex-col justify-center border border-black bg-white p-4">
                <p class="text-sm font-black uppercase text-gray-400">Total Gaji</p>
                <p class="text-2xl font-black mt-2">Rp -</p>
            </div>
            <div class="col-span-2 flex flex-col justify-center border border-black bg-black text-white p-4 shadow-md">
                <p class="text-sm font-black uppercase text-gray-300">Total Gaji Bersih</p>
                <p class="text-2xl font-black mt-2">Rp -</p>
            </div>
            <div class="wf-box p-3 text-xs cursor-default">EDIT BONUS</div>
            <div class="wf-box p-3 text-xs cursor-default">EDIT POTONGAN</div>
            <div class="col-span-2 wf-box bg-black text-white p-3 text-sm cursor-default">SIMPAN</div>
        </div>
    </div>
</div>

<div class="wf-border bg-white">
    <div class="flex justify-between items-center border-b border-black bg-gray-50 px-4 py-3">
        <div class="wf-box w-72 h-10 justify-start px-3 text-xs">Cari nama karyawan...</div>
        <div class="wf-box w-52 h-10 justify-start px-3 text-xs">Semua Bagian</div>
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-left">NAMA KARYAWAN</th>
                <th>BAGIAN</th>
                <th>PERIODE</th>
                <th>HARI KERJA</th>
                <th>TOTAL GAJI BERSIH</th>
                <th>STATUS PEMBAYARAN</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left font-bold bg-gray-50">[Nama Karyawan]</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>Rp -</td>
                <td><div class="wf-box text-xs px-3 py-1 inline-block">[Status Bayar]</div></td>
                <td><div class="flex justify-center gap-2"><div class="wf-box px-3 py-1.5 text-xs cursor-default">Proses</div><div class="wf-box px-3 py-1.5 text-xs cursor-default">Slip</div></div></td>
            </tr>
        </tbody>
    </table>
</div>`;

// 10. Laporan Penggajian content
const cLaporan = `
<div class="grid grid-cols-6 gap-4 mb-6">
    <div class="wf-border bg-white p-5">
        <span class="text-[10px] font-black uppercase text-gray-500">Penerima Gaji</span>
        <p class="text-2xl font-black mt-2">-</p>
    </div>
    <div class="wf-border bg-white p-5">
        <span class="text-[10px] font-black uppercase text-gray-500">Gaji Pokok</span>
        <p class="text-base font-black mt-2">Rp -</p>
    </div>
    <div class="wf-border bg-white p-5">
        <span class="text-[10px] font-black uppercase text-gray-500">Total Lembur</span>
        <p class="text-base font-black mt-2">Rp -</p>
    </div>
    <div class="wf-border bg-white p-5">
        <span class="text-[10px] font-black uppercase text-gray-500">Total Bonus</span>
        <p class="text-base font-black mt-2">Rp -</p>
    </div>
    <div class="wf-border bg-white p-5">
        <span class="text-[10px] font-black uppercase text-gray-500">Total Potongan</span>
        <p class="text-base font-black mt-2">Rp -</p>
    </div>
    <div class="wf-border bg-black text-white p-5">
        <span class="text-[10px] font-black uppercase text-gray-300">Pengeluaran Gaji</span>
        <p class="text-base font-black mt-2">Rp -</p>
    </div>
</div>

<div class="wf-border bg-white p-4 mb-6">
    <div class="grid grid-cols-[1.4fr_1fr_1fr_1fr_auto] gap-4 items-end">
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Cari Laporan</label>
            <div class="wf-box h-10 mt-1 justify-start px-3 text-xs">Nama / ID laporan / ID penggajian</div>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Bulan</label>
            <div class="wf-box h-10 mt-1 justify-start px-3 text-xs">Semua Bulan</div>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Tahun</label>
            <div class="wf-box h-10 mt-1 justify-start px-3 text-xs">Semua Tahun</div>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
            <div class="wf-box h-10 mt-1 justify-start px-3 text-xs">Semua Status</div>
        </div>
        <button class="wf-box px-5 h-10 text-xs font-bold">EXCEL</button>
    </div>
</div>

<div class="wf-border bg-white overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>ID LAPORAN</th>
                <th>NAMA KARYAWAN</th>
                <th>PERIODE</th>
                <th>HARI KERJA</th>
                <th>LEMBUR</th>
                <th>TOTAL BERSIH</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>Rp -</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td><div class="wf-box w-12 h-6 text-xs mx-auto cursor-default">SLIP</div></td>
            </tr>
        </tbody>
    </table>
</div>`;

// Pimpinan Kelola Pengguna content
const cKelolaPengguna = `
<div class="flex justify-between items-end mb-6">
    <div class="wf-box px-4 py-2 text-xs font-bold w-72 justify-start">Cari nama atau username...</div>
    <div class="wf-box px-6 py-2 text-xs font-bold cursor-default">+ TAMBAH PENGGUNA</div>
</div>
<div class="bg-white wf-border overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>USERNAME</th>
                <th>ROLE</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td class="text-left">-</td>
                <td>-</td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Role]</div></td>
                <td><div class="wf-box text-xs px-2 py-0.5 inline-block">[Status]</div></td>
                <td><div class="flex justify-center space-x-2"><div class="wf-box w-12 h-6 text-xs cursor-default">Ubah</div><div class="wf-box w-12 h-6 text-xs cursor-default">Hapus</div></div></td>
            </tr>
        </tbody>
    </table>
</div>`;

// Generate Login Page (Special)
fs.writeFileSync(path.join(dir, '0_login.html'), generateLoginHTML());

// Generate Admin Pages
fs.writeFileSync(path.join(dir, '1_dashboard.html'), generateHTML('DASHBOARD', 'Admin', 1, cDashboard, `<div class="wf-box px-4 py-2 text-xs font-bold">[BULAN YYYY]</div>`));
fs.writeFileSync(path.join(dir, '2_data_karyawan.html'), generateHTML('DATA KARYAWAN', 'Admin', 2, cDataKaryawan));
fs.writeFileSync(path.join(dir, '3_data_presensi.html'), generateHTML('DATA PRESENSI', 'Admin', 3, cDataPresensi));
fs.writeFileSync(path.join(dir, '4_data_produk.html'), generateHTML('DATA PRODUK', 'Admin', 4, cDataProduk));
fs.writeFileSync(path.join(dir, '5_aktivitas_karyawan.html'), generateHTML('REKAP AKTIVITAS KARYAWAN', 'Admin', 5, cAktivitasKaryawan, `
<div class="flex gap-3">
    <div class="wf-box px-3 py-2 text-xs font-bold">DD/MM/YYYY</div>
    <div class="wf-box px-3 py-2 text-xs font-bold">BAGIAN: PRODUKSI</div>
    <button class="wf-box px-4 py-2 text-xs font-bold">TAMPILKAN</button>
</div>`));
fs.writeFileSync(path.join(dir, '6_gaji_pokok_lembur.html'), generateHTML('DATA GAJI POKOK & LEMBUR', 'Admin', 6, cGajiPokokLembur));
fs.writeFileSync(path.join(dir, '7_gaji_bonus.html'), generateHTML('DATA GAJI BONUS', 'Admin', 7, cGajiBonus));
fs.writeFileSync(path.join(dir, '8_potongan_gaji.html'), generateHTML('DATA POTONGAN GAJI', 'Admin', 8, cPotonganGaji));
fs.writeFileSync(path.join(dir, '9_penggajian.html'), generateHTML('PENGGAJIAN KARYAWAN', 'Admin', 9, cPenggajian, `
<div class="flex gap-3">
    <div class="wf-box px-3 py-2 text-xs font-bold">BULAN: -</div>
    <div class="wf-box px-3 py-2 text-xs font-bold">TAHUN: -</div>
</div>`));
fs.writeFileSync(path.join(dir, '10_laporan_penggajian.html'), generateHTML('LAPORAN PENGGAJIAN', 'Admin', 10, cLaporan));

// Generate Pimpinan Pages
fs.writeFileSync(path.join(dir, '11_pimpinan_dashboard.html'), generateHTML('DASHBOARD PIMPINAN', 'Pimpinan', 1, cDashboard, `<div class="wf-box px-4 py-2 text-xs font-bold">[BULAN YYYY]</div>`));
fs.writeFileSync(path.join(dir, '12_pimpinan_data_karyawan.html'), generateHTML('DATA KARYAWAN', 'Pimpinan', 2, cDataKaryawan));
fs.writeFileSync(path.join(dir, '13_pimpinan_laporan_penggajian.html'), generateHTML('LAPORAN PENGGAJIAN', 'Pimpinan', 3, cLaporan));
fs.writeFileSync(path.join(dir, '14_pimpinan_kelola_pengguna.html'), generateHTML('KELOLA PENGGUNA', 'Pimpinan', 4, cKelolaPengguna));

console.log('15 wireframe files generated successfully, including Login!');
