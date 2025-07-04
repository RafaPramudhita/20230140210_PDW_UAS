<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header('Location: ../login.php');
    exit;
}
$edit = null;
// Tambah/edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'] === 'asisten' ? 'asisten' : 'mahasiswa';
    if (isset($_POST['id']) && $_POST['id']) {
        $id = intval($_POST['id']);
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id='$id'");
        header('Location: akun.php?msg=Berhasil update');
        exit;
    } else {
        $password = password_hash('123456', PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')");
        header('Location: akun.php?msg=Berhasil tambah');
        exit;
    }
}
// Edit user
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$id'"));
}
// Hapus user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    header('Location: akun.php?msg=Berhasil hapus');
    exit;
}
// Reset password
if (isset($_GET['reset'])) {
    $id = intval($_GET['reset']);
    $password = password_hash('123456', PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$password' WHERE id='$id'");
    header('Location: akun.php?msg=Password direset ke 123456');
    exit;
}
// Ambil semua user
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY role, nama");
include 'templates/header.php';
?>
<div class='w-full max-w-5xl'>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8 text-blue-900">Kelola Akun Pengguna</h1>
        <?php if(isset($_GET['msg'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded border border-green-300 shadow text-center text-lg font-semibold"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <div class="bg-white rounded shadow p-8 mb-10 border border-blue-100">
            <h2 class="text-xl font-semibold mb-4 text-blue-700"><?php echo $edit ? 'Edit Akun' : 'Tambah Akun'; ?></h2>
            <form method="post" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                <div>
                    <label class="block font-semibold mb-1">Nama</label>
                    <input type="text" name="nama" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" value="<?php echo htmlspecialchars($edit['nama'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" required class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200" value="<?php echo htmlspecialchars($edit['email'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Role</label>
                    <select name="role" class="w-full border border-blue-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-200">
                        <option value="mahasiswa" <?php if(($edit['role'] ?? '')=='mahasiswa') echo 'selected'; ?>>Mahasiswa</option>
                        <option value="asisten" <?php if(($edit['role'] ?? '')=='asisten') echo 'selected'; ?>>Asisten</option>
                    </select>
                </div>
                <div class="flex gap-2 items-center">
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold shadow">Simpan</button>
                    <?php if($edit): ?>
                        <a href="akun.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="bg-white rounded shadow p-8 border border-blue-100">
            <h2 class="text-lg font-semibold mb-4 text-blue-700">Daftar Akun</h2>
            <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 rounded-lg">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="py-3 px-4 border-b font-semibold">Nama</th>
                        <th class="py-3 px-4 border-b font-semibold">Email</th>
                        <th class="py-3 px-4 border-b font-semibold">Role</th>
                        <th class="py-3 px-4 border-b font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($users)): ?>
                    <tr class="hover:bg-blue-50">
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="py-2 px-4 border-b align-top"><?php echo htmlspecialchars($row['role']); ?></td>
                        <td class="py-2 px-4 border-b align-top flex flex-wrap gap-2">
                            <a href="akun.php?edit=<?php echo $row['id']; ?>" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 font-semibold">Edit</a>
                            <a href="akun.php?delete=<?php echo $row['id']; ?>" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 font-semibold" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            <a href="akun.php?reset=<?php echo $row['id']; ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold" onclick="return confirm('Reset password ke 123456?')">Reset Password</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="text-xs text-gray-500 mt-2">Password default user baru/reset: <b>123456</b></div>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?> 