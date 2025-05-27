<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM chatbot WHERE id_chat = '$delete_id'";
    $delete_result = mysqli_query($con, $delete_sql);
    
    if ($delete_result) {
        $_SESSION['msg'] = "Data berhasil dihapus.";
        $_SESSION['msgClass'] = "alert-success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat menghapus data.";
        $_SESSION['msgClass'] = "alert-danger";
    }
    // Redirect to avoid refreshing issues
    header("Location: view_chatbot.php");
    exit;
}

// Handle form submission for add/edit
if (isset($_POST['save'])) {
    $pertanyaan = mysqli_real_escape_string($con, $_POST['pertanyaan']);
    $jawaban = mysqli_real_escape_string($con, $_POST['jawaban']);
    $id_chat = isset($_POST['id_chat']) ? $_POST['id_chat'] : '';

    if (!empty($id_chat)) {
        // Update existing record
        $sql = "UPDATE chatbot SET 
                pertanyaan_chat = '$pertanyaan',
                jawaban_chat = '$jawaban'
                WHERE id_chat = '$id_chat'";
        
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "Data berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui data.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        // Insert new record
        $sql = "INSERT INTO chatbot (pertanyaan_chat, jawaban_chat) 
                VALUES ('$pertanyaan', '$jawaban')";
        
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "Data berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan data.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    }
    // Redirect to avoid form resubmission
    header("Location: view_chatbot.php");
    exit;
}

// Get chatbot entry for editing if edit_id is provided
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_sql = "SELECT * FROM chatbot WHERE id_chat = '$edit_id'";
    $edit_result = mysqli_query($con, $edit_sql);
    $edit_data = mysqli_fetch_assoc($edit_result);
}

// Fetch all chatbot data for display
$query = "SELECT * FROM chatbot ORDER BY id_chat DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "title.php"; ?>
    <!-- Bootstrap & AdminLTE CSS -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include '../includes/logo.php'; ?>
    <style>
        .action-buttons {
            white-space: nowrap;
        }
        .form-container {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Kelola Chatbot</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Chatbot</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Display session alert -->
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

                <!-- Form to add/edit chatbot entries -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><?php echo $edit_data ? 'Edit Pertanyaan dan Jawaban' : 'Tambah Pertanyaan dan Jawaban Baru'; ?></h3>
                    </div>
                    <div class="card-body form-container">
                        <form action="" method="post" class="needs-validation" novalidate>
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="id_chat" value="<?php echo $edit_data['id_chat']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="pertanyaan">Pertanyaan:</label>
                                <input type="text" class="form-control" id="pertanyaan" name="pertanyaan" 
                                       value="<?php echo isset($edit_data) ? htmlspecialchars($edit_data['pertanyaan_chat']) : ''; ?>" 
                                       required>
                                <div class="invalid-feedback">
                                    Mohon isi pertanyaan.
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="jawaban">Jawaban:</label>
                                <textarea class="form-control" id="jawaban" name="jawaban" rows="4" required><?php echo isset($edit_data) ? htmlspecialchars($edit_data['jawaban_chat']) : ''; ?></textarea>
                                <div class="invalid-feedback">
                                    Mohon isi jawaban.
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="save" class="btn btn-primary">
                                    <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if ($edit_data): ?>
                                    <a href="view_chatbot.php" class="btn btn-secondary">Batal</a>
                                <?php else: ?>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table showing all chatbot entries -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">Daftar Pertanyaan dan Jawaban Chatbot</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="chatbotTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th width="5%">No</th> -->
                                        <th width="30%">Pertanyaan</th>
                                        <th width="45%">Jawaban</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <!-- <td><?php echo $no++; ?></td> -->
                                        <td><?php echo htmlspecialchars($row['pertanyaan_chat']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jawaban_chat']); ?></td>
                                        <td class="action-buttons">
                                            <a href="view_chatbot.php?edit_id=<?php echo $row['id_chat']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id_chat']; ?>)" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data chatbot.</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>

<!-- jQuery, Bootstrap, AdminLTE JS -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>

<script>
    // Initialize DataTables
    $(document).ready(function() {
        $('#chatbotTable').DataTable({
            "responsive": true,
            "autoWidth": false
        });
    });
    
    // Form validation
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })();
    
    // Confirmation dialog for delete
    function confirmDelete(id) {
        if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
            window.location.href = "view_chatbot.php?delete_id=" + id;
        }
    }
</script>
</body>
</html>
