<?php include 'header.php'; ?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <?php
                include 'config.php';

                $stmt = $pdo->query("SELECT MaLoai, TenLoai FROM LoaiSach WHERE IsDel = 1");

                while ($row = $stmt->fetch()) {
                    echo '<a href="view_books.php?MaLoai=' . $row['MaLoai'] . '" class="list-group-item list-group-item-action">' . $row['TenLoai'] . '</a>';
                }
                ?>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <?php
                if (isset($_GET['MaLoai'])) {
                    $maLoai = $_GET['MaLoai'];
                    $stmt = $pdo->prepare(query: "SELECT Sach.MaSach, Sach.TenSach, Sach.TacGia, Sach.MoTa, Sach.HinhAnh, LoaiSach.TenLoai, Sach.NamXuatBan, Sach.TinhTrang 
                                           FROM Sach 
                                           JOIN LoaiSach ON Sach.MaLoai = LoaiSach.MaLoai 
                                           WHERE Sach.MaLoai = ? AND Sach.IsDel = 1");
                    $stmt->execute(params: [$maLoai]);
                } else {
                    $stmt = $pdo->query("SELECT Sach.MaSach, Sach.TenSach, Sach.TacGia, Sach.MoTa, Sach.HinhAnh, LoaiSach.TenLoai, Sach.NamXuatBan, Sach.TinhTrang 
                                         FROM Sach 
                                         JOIN LoaiSach ON Sach.MaLoai = LoaiSach.MaLoai 
                                         WHERE Sach.IsDel = 1");
                }


                while ($row = $stmt->fetch()) {
                    $imagePath = !empty($row['HinhAnh']) ? $row['HinhAnh'] : 'images/default_book.jpg';

                    echo '
                    <div class="col-md-3 mb-3 d-flex">
                        <div class="card d-flex flex-column" style="width: 100%; height: 100%;">
                            <img src="' . $imagePath . '" class="card-img-top" alt="' . $row['TenSach'] . '" style="height: 400px; object-fit: cover;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title">' . $row['TenSach'] . '</h5>
                                <p class="card-text">Tác giả: ' . $row['TacGia'] . '</p>
                                <p class="card-text">' . substr($row['MoTa'], 0, 10) . '...</p>
                                <button type="button" class="btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="loadBookDetail(' . $row['MaSach'] . ')">Chi Tiết</button>
                            </div>
                        </div>
                    </div>';
                }

                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>

<script>
    function loadBookDetail(maSach) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'popupDetailsach.php?MaSach=' + maSach, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.querySelector('#detailModal .modal-content').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>

<?php include 'footer.php'; ?>