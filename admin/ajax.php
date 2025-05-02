<?php
include 'conn.php';
header('Content-Type: application/json');
// Nonaktifkan keluaran error agar tidak tercampur dalam JSON
error_reporting(0);
ini_set('display_errors', 0);
// Ambil parameter action
$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($action == 'fetch_services') {
    // =======================
    //        LAYANAN
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT id, title, descrip,price, img, date FROM services";
    $totalQuery = "SELECT COUNT(id) as total FROM services";
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE title LIKE '%$searchValueEsc%' 
                   OR descrip LIKE '%$searchValueEsc%'";
    }
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    // Urutan default (tanpa sorting dari DataTables)
    $orderColumn = "id";
    $orderDir    = "DESC";
    // Jika ada parameter order dari DataTables, gunakan
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        // Peta indeks kolom ke nama kolom database (sesuaikan urutan dengan definisi kolom DataTables)
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'descrip',
            3 => 'price',
            4 => 'date'
        );
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    $data = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group">
                        <a href="add-services.php?edit=' . $id . '" class="btn btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-services.php?delete_id=' . $id . '" onclick="return confirm(\'Apakah Anda yakin?\')" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['aksi'] = $actions;
        $data[] = $row;
    }
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} elseif ($action == 'fetch_partner') {
    // =======================
    //        MITRA
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT id, klien, gambar FROM klien";
    $totalQuery = "SELECT COUNT(id) as total FROM klien";
    // Siapkan klausa WHERE untuk filter pencarian
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE klien LIKE '%$searchValueEsc%'";
    }
    // Ambil jumlah total data
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    // Pengaturan default sorting berdasarkan 'id' DESC
    $orderColumn = "id"; // Default ke 'id'
    $orderDir    = "DESC"; // Default DESC
    // Cek apakah ada parameter sorting dari DataTables, dan ubah urutannya
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        // Hanya izinkan pengurutan berdasarkan 'id' (kolom 0)
        $columns = array(
            0 => 'id',  // Kolom 0 adalah 'id' (kolom yang diizinkan untuk diurutkan)
            1 => 'klien', // Kolom 1 adalah 'klien' (kolom yang diizinkan untuk diurutkan)
        );
        // Jika indeks kolom yang diminta ada dalam peta, ubah kolom pengurutan
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    // Bangun query untuk mengambil data dengan pengurutan yang dinamis
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    // Siapkan data untuk DataTables
    $data = array();
    $no   = $start + 1; // Untuk menampilkan nomor baris yang benar
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group btn-group-sm">
                        <a href="add-partner.php?edit=' . $id . '" class="btn btn-info" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-partner.php?delete_id=' . $id . '" class="btn btn-danger" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['no']  = $no++;  // Set nomor baris
        $row['aksi'] = $actions;  // Menambahkan tombol aksi
        $data[] = $row;
    }
    // Menyiapkan respons akhir yang akan dikirimkan ke DataTables
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} elseif ($action == 'fetch_blog') {
    // =======================
    //         BLOG
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT id, title, category, descrip, img FROM blog";
    $totalQuery = "SELECT COUNT(id) as total FROM blog";
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE title LIKE '%$searchValueEsc%' 
                   OR category LIKE '%$searchValueEsc%' 
                   OR descrip LIKE '%$searchValueEsc%'";
    }
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    $orderColumn = "id";
    $orderDir    = "DESC";
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'category',
            3 => 'descrip'
        );
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    $data = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group">
                        <a href="add-blog.php?edit=' . $id . '" class="btn btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-blog.php?delete_id=' . $id . '" onclick="return confirm(\'Apakah Anda yakin?\')" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['aksi'] = $actions;
        $data[] = $row;
    }
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} elseif ($action == 'fetch_testimonials') {
    // =======================
    //    TESTIMONIALS
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT id, title, designation, descrip, img FROM testimonials";
    $totalQuery = "SELECT COUNT(id) as total FROM testimonials";
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE title LIKE '%$searchValueEsc%' 
                   OR designation LIKE '%$searchValueEsc%' 
                   OR descrip LIKE '%$searchValueEsc%'";
    }
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    $orderColumn = "id";
    $orderDir    = "DESC";
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'designation',
            3 => 'deskrip'
        );
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    $data = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group btn-group-sm">
                        <a href="add-testimonials.php?edit=' . $id . '" onclick="return confirm(\'Apakah Anda yakin?\')" class="btn btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-testimonials.php?delete_id=' . $id . '" onclick="return confirm(\'Apakah Anda yakin?\')" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['aksi'] = $actions;
        $data[] = $row;
    }
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} elseif ($action == 'fetch_teams') {
    // =======================
    //        TIM
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT * FROM teams";
    $totalQuery = "SELECT COUNT(id) as total FROM teams";
    // Siapkan klausa WHERE untuk filter pencarian
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE title LIKE '%$searchValueEsc%' 
                   OR designation LIKE '%$searchValueEsc%' 
                   OR facebook LIKE '%$searchValueEsc%' 
                   OR twitter LIKE '%$searchValueEsc%' 
                   OR instagram LIKE '%$searchValueEsc%' 
                   OR linkedin LIKE '%$searchValueEsc%' 
                   OR whatsapp LIKE '%$searchValueEsc%'";
    }
    // Ambil jumlah total data
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    // Pengaturan default sorting berdasarkan 'id' DESC
    $orderColumn = "id"; // Default ke 'id'
    $orderDir    = "DESC"; // Default DESC
    // Cek apakah ada parameter sorting dari DataTables, dan ubah urutannya
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        // Kolom yang diizinkan untuk sorting
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'designation',
            3 => 'facebook',
            4 => 'twitter',
            5 => 'instagram',
            6 => 'linkedin',
            7 => 'whatsapp'
        );
        // Jika indeks kolom yang diminta ada dalam peta, ubah kolom pengurutan
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    // Bangun query untuk mengambil data dengan pengurutan yang dinamis
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    // Siapkan data untuk DataTables
    $data = array();
    $no   = $start + 1; // Untuk menampilkan nomor baris yang benar
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group btn-group-sm">
                        <a href="add-teams.php?edit=' . $id . '" class="btn btn-info" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-teams.php?delete_id=' . $id . '" class="btn btn-danger" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['no']  = $no++;  // Set nomor baris
        $row['aksi'] = $actions;  // Menambahkan tombol aksi
        $data[] = $row;
    }
    // Menyiapkan respons akhir yang akan dikirimkan ke DataTables
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} elseif ($action == 'fetch_projek') {
    // =======================
    //        PROJEK
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT * FROM projek";
    $totalQuery = "SELECT COUNT(id) as total FROM projek";
    // Siapkan klausa WHERE untuk filter pencarian
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE judul LIKE '%$searchValueEsc%' 
                   OR tahun LIKE '%$searchValueEsc%' 
                   OR deskrip LIKE '%$searchValueEsc%' 
                   OR upload LIKE '%$searchValueEsc%'";
    }
    // Ambil jumlah total data
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    // Pengaturan default sorting berdasarkan 'id' DESC
    $orderColumn = "id"; // Default ke 'id'
    $orderDir    = "DESC"; // Default DESC
    // Cek apakah ada parameter sorting dari DataTables, dan ubah urutannya
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        // Kolom yang diizinkan untuk sorting
        $columns = array(
            0 => 'judul',
            1 => 'tahun',
            2 => 'deskrip',
            3 => 'upload'
        );
        // Jika indeks kolom yang diminta ada dalam peta, ubah kolom pengurutan
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    // Bangun query untuk mengambil data dengan pengurutan yang dinamis
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    // Siapkan data untuk DataTables
    $data = array();
    $no   = $start + 1; // Untuk menampilkan nomor baris yang benar
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group btn-group-sm">
                        <a href="add-projek.php?edit=' . $id . '" class="btn btn-info" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-projek.php?delete_id=' . $id . '" class="btn btn-danger" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['no']  = $no++;  // Set nomor baris
        $row['aksi'] = $actions;  // Menambahkan tombol aksi
        $data[] = $row;
    }
    // Menyiapkan respons akhir yang akan dikirimkan ke DataTables
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
}   elseif ($action == 'fetch_kegiatan') {
    // =======================
    //        Kegiatan
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery  = "SELECT * FROM kegiatan";
    $totalQuery = "SELECT COUNT(id) as total FROM kegiatan";
    // Siapkan klausa WHERE untuk filter pencarian
    $where = "";
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE nama_kegiatan LIKE '%$searchValueEsc%' 
                   OR deskripsi LIKE '%$searchValueEsc%' 
                   OR url LIKE '%$searchValueEsc%'";
    }
    // Ambil jumlah total data
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];
    // Pengaturan default sorting berdasarkan 'id' DESC
    $orderColumn = "id"; // Default ke 'id'
    $orderDir    = "DESC"; // Default DESC
    // Cek apakah ada parameter sorting dari DataTables, dan ubah urutannya
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        // Kolom yang diizinkan untuk sorting
        $columns = array(
            0 => 'nama_kegiatan',
            1 => 'deskripsi',
            2 => 'url'
        );
        // Jika indeks kolom yang diminta ada dalam peta, ubah kolom pengurutan
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }
    // Bangun query untuk mengambil data dengan pengurutan yang dinamis
    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    // Siapkan data untuk DataTables
    $data = array();
    $no   = $start + 1; // Untuk menampilkan nomor baris yang benar
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group btn-group-sm">
                        <a href="add-kegiatan.php?edit=' . $id . '" class="btn btn-info" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-kegiatan.php?delete_id=' . $id . '" class="btn btn-danger" onclick="return confirm(\'Apakah Anda yakin?\')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['no']  = $no++;  // Set nomor baris
        $row['aksi'] = $actions;  // Menambahkan tombol aksi
        $data[] = $row;
    }
    // Menyiapkan respons akhir yang akan dikirimkan ke DataTables
    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} elseif ($action == 'fetch_articles') {
    // =======================
    //        ARTIKEL
    // =======================
    $draw        = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
    $start       = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    $baseQuery   = "SELECT id, title, content, img, author, created_at FROM articles";
    $totalQuery  = "SELECT COUNT(id) as total FROM articles";
    $where       = "";

    // Filter pencarian
    if (!empty($searchValue)) {
        $searchValueEsc = mysqli_real_escape_string($con, $searchValue);
        $where = " WHERE title LIKE '%$searchValueEsc%' 
                   OR content LIKE '%$searchValueEsc%' 
                   OR author LIKE '%$searchValueEsc%'";
    }

    // Hitung total data
    $totalDataQuery = $totalQuery . $where;
    $resultTotal    = mysqli_query($con, $totalDataQuery);
    if (!$resultTotal) {
        echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
        exit;
    }

    $rowTotal       = mysqli_fetch_assoc($resultTotal);
    $totalRecords   = $rowTotal['total'];

    $orderColumn = "id";
    $orderDir    = "DESC";
    if (isset($_GET['order'][0]['column']) && isset($_GET['order'][0]['dir'])) {
        $orderColumnIndex = intval($_GET['order'][0]['column']);
        $orderDir = ($_GET['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
        $columns = array(
            0 => 'img',
            1 => 'title',
            2 => 'content',
            3 => 'created_at',
            4 => 'author'
        );
        if (isset($columns[$orderColumnIndex])) {
            $orderColumn = $columns[$orderColumnIndex];
        }
    }

    $dataQuery = $baseQuery . $where . " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
    $resultData = mysqli_query($con, $dataQuery);
    if (!$resultData) {
        echo json_encode(["error" => "Database error: " . mysqli_error($con)]);
        exit;
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $id = $row['id'];
        $actions = '<div class="btn-group">
                        <a href="add-artikel.php?edit=' . $id . '" class="btn btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="view-artikel.php?delete_id=' . $id . '" onclick="return confirm(\'Apakah Anda yakin?\')" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
        $row['aksi'] = $actions;
        $data[] = $row;
    }

    $response = array(
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    );
    echo json_encode($response);
    exit;
} else {
    echo json_encode(["error" => "Aksi tidak valid"]);
    exit;
}
?>