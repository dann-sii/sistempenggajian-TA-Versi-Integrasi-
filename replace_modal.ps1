$adminPath = 'd:\Folder Antigravity\Sistem Penggajian v1.0\resources\views\admin_penggajian\laporan_penggajian.blade.php'
$pimPath = 'd:\Folder Antigravity\Sistem Penggajian v1.0\resources\views\pimpinan\laporan_penggajian.blade.php'
$adminLines = Get-Content $adminPath -Raw
$pimLines = Get-Content $pimPath -Raw
$adminMatch = $adminLines.IndexOf('<div id="modal-laporan"')
$pimMatch = $pimLines.IndexOf('<div id="modal-laporan"')
if ($adminMatch -ge 0 -and $pimMatch -ge 0) {
    $newPim = $pimLines.Substring(0, $pimMatch) + $adminLines.Substring($adminMatch)
    [IO.File]::WriteAllText($pimPath, $newPim, [System.Text.Encoding]::UTF8)
    Write-Host 'Replacement successful!'
} else {
    Write-Host 'Marker not found'
}
