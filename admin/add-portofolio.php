<?php
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta'); // Ensure timezone is set to Indonesia (WIB)
$today = date("Y-m-d H:i:s");

// Ambil data proposal terbaru dengan mengurutkan berdasarkan tanggal secara menurun
$query  = "SELECT * FROM proposal ORDER BY date DESC LIMIT 1";
$result = mysqli_query($con, $query);
$row    = mysqli_fetch_assoc($result);
$dataExists = ($row) ? true : false;

// Jika delete action di-trigger
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $deleteId = $_GET['id'];

    // Ambil record spesifik untuk dihapus
    $query = "SELECT * FROM proposal WHERE id_pro = '$deleteId'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $pdfToDelete = $row['pdf'];

        // Hapus file dari server
        if (!empty($pdfToDelete) && file_exists("../pdf/" . $pdfToDelete)) {
            unlink("../pdf/" . $pdfToDelete);
        }

        // Hapus record dari database
        $sql = "DELETE FROM proposal WHERE id_pro = '$deleteId'";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "PDF berhasil dihapus.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menghapus PDF.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        $_SESSION['msg'] = "Data tidak ditemukan.";
        $_SESSION['msgClass'] = "alert-warning";
    }

    // Redirect untuk menghindari resubmission
    header("Location: add-portofolio.php");
    exit;
}

// Jika form disubmit untuk update
if (isset($_POST['save'])) {
    // Gunakan nama file pdf yang sudah ada jika tersedia
    $proposal_pdf = isset($row['pdf']) ? $row['pdf'] : '';
    $proposal_name = isset($_POST['proposal_name']) ? $_POST['proposal_name'] : $row['name'];

    // Jika ada file PDF baru yang diupload, proses filenya
    if (!empty($_FILES['proposal_pdf']['name'])) {
        $newFileName = rand() . '_' . $_FILES['proposal_pdf']['name'];
        $tempFile    = $_FILES['proposal_pdf']['tmp_name'];
        $folder      = "../pdf/" . $newFileName;
        // Hanya izinkan ekstensi pdf dan image
        $valid_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $file_ext  = strtolower(pathinfo($newFileName, PATHINFO_EXTENSION));
        $file_size = $_FILES['proposal_pdf']['size'];

        if (in_array($file_ext, $valid_ext)) {
            if (in_array($file_ext, ['jpg', 'jpeg', 'png']) && $file_size > 512000) { // Ukuran gambar maksimal 500KB
                $_SESSION['msg'] = "Gambar yang diunggah harus berukuran maksimal 500KB.";
                $_SESSION['msgClass'] = "alert-danger";
                header("Location: add-portofolio.php");
                exit;
            }
            move_uploaded_file($tempFile, $folder);
            $proposal_pdf = $newFileName;
        } else {
            $_SESSION['msg'] = "File yang diunggah harus berupa PDF atau gambar.";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: add-portofolio.php");
            exit;
        }
    }

    // Jika tidak ada record, insert; jika sudah ada, update record terbaru
    if (!$dataExists) {
        $sql = "INSERT INTO proposal (pdf, name, date) 
                VALUES ('$proposal_pdf', '$proposal_name', '$today')";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "PDF berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan PDF.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        $sql = "UPDATE proposal SET 
                    pdf  = '$proposal_pdf',
                    name = '$proposal_name',
                    date = '$today'
                WHERE id_pro = '".$row['id_pro']."'";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "PDF berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui PDF.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    }
    header("Location: add-portofolio.php");
    exit;
}

// Jika form disubmit untuk menambahkan PDF baru
if (isset($_POST['add'])) {
    $proposal_pdf = '';
    $proposal_name = isset($_POST['new_proposal_name']) ? $_POST['new_proposal_name'] : '';

    if (!empty($_FILES['new_proposal_pdf']['name'])) {
        $newFileName = rand() . '_' . $_FILES['new_proposal_pdf']['name'];
        $tempFile    = $_FILES['new_proposal_pdf']['tmp_name'];
        $folder      = "../pdf/" . $newFileName;
        $valid_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $file_ext  = strtolower(pathinfo($newFileName, PATHINFO_EXTENSION));
        $file_size = $_FILES['new_proposal_pdf']['size'];

        if (in_array($file_ext, $valid_ext)) {
            if (in_array($file_ext, ['jpg', 'jpeg', 'png']) && $file_size > 512000) {
                $_SESSION['msg'] = "Gambar yang diunggah harus berukuran maksimal 500KB.";
                $_SESSION['msgClass'] = "alert-danger";
                header("Location: add-portofolio.php");
                exit;
            }
            move_uploaded_file($tempFile, $folder);
            $proposal_pdf = $newFileName;
        } else {
            $_SESSION['msg'] = "File yang diunggah harus berupa PDF atau gambar.";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: add-portofolio.php");
            exit;
        }
    }

    if (!empty($proposal_pdf) && !empty($proposal_name)) {
        $sql = "INSERT INTO proposal (name, pdf, date) VALUES ('$proposal_name','$proposal_pdf', '$today')";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "PDF berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan PDF.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        $_SESSION['msg'] = "Mohon unggah file PDF yang valid dan masukkan nama proposal.";
        $_SESSION['msgClass'] = "alert-warning";
    }

    header("Location: add-portofolio.php");
    exit;
}

// Ambil semua record untuk ditampilkan dalam daftar portofolio
$queryAll = "SELECT * FROM proposal ORDER BY date DESC";
$resultAll = mysqli_query($con, $queryAll);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "title.php"; ?>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Portofolio</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Portofolio</h1>
        </section>
        <section class="content">
            <div class="container">
                <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
                    <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']); 
                            unset($_SESSION['msgClass']);
                        ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Tampilkan PDF terbaru jika ada -->
                <?php if ($dataExists && !empty($row['pdf'])): ?>
                    <?php $pdfPath = "../pdf/" . $row['pdf']; ?>
                    <div class="existing-pdf mb-4">
                        <h4><?php echo $row['name']; ?></h4>
                        <iframe src="<?php echo $pdfPath; ?>" 
                                style="width:100%; height:600px;" data-aos="fade-up" data-aos-delay="500" frameborder="0"></iframe>
                    </div>
                <?php endif; ?>

                <!-- Form untuk menambahkan portofolio baru -->
                <div class="container mt-4">
                    <h3>Tambah Portofolio Baru</h3>
                    <form action="" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate style="margin:0;">
                        <div class="col-md-12">
                            <label for="newProposalName" class="form-label">Nama Proposal</label>
                            <input type="text" class="form-control" id="newProposalName" name="new_proposal_name" placeholder="Masukkan nama proposal" required>
                            <div class="invalid-feedback">
                                Mohon masukkan nama proposal.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="newValidationPDF" class="form-label">Upload Portofolio Baru</label><br>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="newValidationPDF" name="new_proposal_pdf" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this); previewPDF(this);" required>
                                <label class="custom-file-label" for="newValidationPDF">Pilih File</label>
                                <div class="invalid-feedback">
                                    Mohon unggah file yang valid (PDF atau gambar).
                                </div>
                            </div>
                            <div id="pdfPreview" style="margin-top: 20px; display: none;">
                                <h5>Preview File:</h5>
                                <iframe id="pdfPreviewFrame" style="width:50%; height:70vh;" frameborder="0"></iframe>
                            </div>
                        </div>
                        <div style="padding-top: 3%;" class="col-12">
                            <button type="submit" name="add" class="btn btn-success">Tambah</button>
                            <a href="add-portofolio.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>

                <script>
                (function () {
                    'use strict';
                    var forms = document.querySelectorAll('.needs-validation');
                    Array.prototype.slice.call(forms).forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                })();
                </script>
            </div>
        </section>

        <!-- Tampilkan daftar semua portofolio -->
        <section class="content">
            <div class="container mt-4">
                <h3>Daftar Portofolio</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Proposal</th>
                            <th>Proposal</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($resultAll) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($resultAll)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><a href="../pdf/<?php echo $row['pdf']; ?>" target="_blank">Lihat File</a></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td>
                                        <a href="edit-portofolio.php?id=<?php echo $row['id_pro']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="add-portofolio.php?delete=true&id=<?php echo $row['id_pro']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus PDF ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada portofolio yang diunggah.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
document.getElementById("validationPDF").addEventListener("change", function () {
    var fileName = this.files[0] ? this.files[0].name : "Tidak ada file yang dipilih";
    this.nextElementSibling.innerText = fileName;
});

document.getElementById("newValidationPDF").addEventListener("change", function () {
    var fileName = this.files[0] ? this.files[0].name : "Tidak ada file yang dipilih";
    this.nextElementSibling.innerText = fileName;
});

const proposalNameInput = document.getElementById('proposalName');
const proposalFileInput = document.getElementById('validationPDF');
const saveButton = document.querySelector('button[name="save"]');

function checkChanges() {
    const originalName = '<?php echo isset($row['name']) ? $row['name'] : ''; ?>';
    const nameChanged = proposalNameInput.value !== originalName;
    const fileChanged = proposalFileInput.files.length > 0;
    saveButton.disabled = !(nameChanged || fileChanged);
}

proposalNameInput.addEventListener('input', checkChanges);
proposalFileInput.addEventListener('change', checkChanges);
checkChanges();

function updateFileName(input) {
    var fileName = input.files[0] ? input.files[0].name : "Pilih Portofolio";
    input.nextElementSibling.innerText = fileName;
}

function previewPDF(input) {
    const file = input.files[0];
    if (file && (file.type === "application/pdf" || file.type.startsWith("image/"))) {
        const fileURL = URL.createObjectURL(file);
        const previewFrame = document.getElementById("pdfPreviewFrame");
        previewFrame.src = fileURL;
        document.getElementById("pdfPreview").style.display = "block";
    } else {
        document.getElementById("pdfPreview").style.display = "none";
    }
}
</script>
</body>
</html>
