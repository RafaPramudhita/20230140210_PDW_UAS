<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header('Location: ../login.php');
    exit;
}
// Ambil data filter
$filter_modul = $_GET['modul'] ?? '';
$filter_mahasiswa = $_GET['mahasiswa'] ?? '';
$filter_status = $_GET['status'] ?? '';
// Query modul
$modul_list = mysqli_query($conn, "SELECT m.id, m.judul, p.nama as praktikum FROM modul m JOIN praktikum p ON m.praktikum_id=p.id ORDER BY p.nama, m.pertemuan_ke");
// Query mahasiswa
$mahasiswa_list = mysqli_query($conn, "SELECT DISTINCT u.id, u.nama FROM laporan l JOIN users u ON l.user_id=u.id WHERE u.role='mahasiswa'");
// Query laporan
$where = [];
if ($filter_modul) $where[] = "l.modul_id='" . intval($filter_modul) . "'";
if ($filter_mahasiswa) $where[] = "l.user_id='" . intval($filter_mahasiswa) . "'";
if ($filter_status) $where[] = "l.status='" . mysqli_real_escape_string($conn, $filter_status) . "'";
$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
$laporan = mysqli_query($conn, "SELECT l.*, m.judul as modul_judul, m.pertemuan_ke, p.nama as praktikum_nama, u.nama as mahasiswa_nama FROM laporan l JOIN modul m ON l.modul_id=m.id JOIN praktikum p ON m.praktikum_id=p.id JOIN users u ON l.user_id=u.id $where_sql ORDER BY l.tanggal_kumpul DESC");
include 'templates/header.php';
?>
<div class='w-full max-w-5xl'>
    <div class="max-w-6xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8 text-blue-900">Laporan Masuk Mahasiswa</h1>
        <?php if(isset($_GET['msg'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300 shadow text-center text-lg font-semibold"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <form method="get" class="flex flex-wrap gap-3 mb-8 bg-white p-6 rounded shadow border border-blue-100 items-end">
            <div>
                <label class="block font-semibold mb-1">Modul</label>
                <select name="modul" class="border border-blue-300 rounded px-3 py-2 w-48">
                    <option value="">Semua Modul</option>
                    <?php while($m = mysqli_fetch_assoc($modul_list)): ?>
                        <option value="<?php echo $m['id']; ?>" <?php if($filter_modul==$m['id']) echo 'selected'; ?>><?php echo htmlspecialchars($m['praktikum']) . ' - ' . htmlspecialchars($m['judul']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Mahasiswa</label>
                <select name="mahasiswa" class="border border-blue-300 rounded px-3 py-2 w-48">
                    <option value="">Semua Mahasiswa</option>
                    <?php while($u = mysqli_fetch_assoc($mahasiswa_list)): ?>
                        <option value="<?php echo $u['id']; ?>" <?php if($filter_mahasiswa==$u['id']) echo 'selected'; ?>><?php echo htmlspecialchars($u['nama']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="border border-blue-300 rounded px-3 py-2 w-40">
                    <option value="">Semua Status</option>
                    <option value="Dikirim" <?php if($filter_status=='Dikirim') echo 'selected'; ?>>Dikirim</option>
                    <option value="Dinilai" <?php if($filter_status=='Dinilai') echo 'selected'; ?>>Dinilai</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold shadow">Filter</button>
            </div>
        </form>
        <div class="bg-white rounded shadow p-8 border border-blue-100">
            <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="py-3 px-4 border-b font-semibold">Tanggal</th>
                        <th class="py-3 px-4 border-b font-semibold">Mahasiswa</th>
                        <th class="py-3 px-4 border-b font-semibold">Praktikum</th>
                        <th class="py-3 px-4 border-b font-semibold">Modul</th>
                        <th class="py-3 px-4 border-b font-semibold">File</th>
                        <th class="py-3 px-4 border-b font-semibold">Status</th>
                        <th class="py-3 px-4 border-b font-semibold">Nilai</th>
                        <th class="py-3 px-4 border-b font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($laporan)): ?>
                    <tr class="hover:bg-blue-50">
                        <td class="py-2 px-4 border-b align-top text-xs"><?php echo $row['tanggal_kumpul']; ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['mahasiswa_nama']); ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['praktikum_nama']); ?></td>
                        <td class="py-2 px-4 border-b align-top">Pertemuan <?php echo $row['pertemuan_ke']; ?>: <?php echo htmlspecialchars($row['modul_judul']); ?></td>
                        <td class="py-2 px-4 border-b align-top">
                            <?php if($row['file_laporan']): ?>
                                <a href="../uploads/laporan/<?php echo $row['file_laporan']; ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold" download>Download</a>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo $row['nilai'] !== null ? $row['nilai'] : '-'; ?></td>
                        <td class="py-2 px-4 border-b align-top">
                            <a href="penilaian.php?id=<?php echo $row['id']; ?>" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">Nilai</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?> 