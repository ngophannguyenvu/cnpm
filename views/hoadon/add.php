<?php
$madl = isset($_GET['madl']) ? $_GET['madl'] : '';
$manguoidung = '';
$services = [];
$tongtien = 0;
require_once '../../app/config/database.php';
require_once '../../app/models/DatLichModel.php';
require_once '../../app/models/HoaDonVaThanhToanModel.php';
$db = (new Database())->getConnection();
$datlichModel = new DatLichModel($db);
// Xử lý lưu hóa đơn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NgayThanhToan = date('Y-m-d');
    $Tongtien = $_POST['tongtien'];
    $MaDL = $_POST['madl'];
    $Manguoidung = $_POST['manguoidung'];
    $MaPT = $_POST['phuongthuc'];
    $Matrangthai = $_POST['trangthai'];
    $Maphong = null; // Nếu có mã phòng thì lấy thêm, còn không thì để null
    $hdModel = new HoaDonVaThanhToanModel($db);
    $result = $hdModel->addHoaDonVaThanhToan($NgayThanhToan, $Tongtien, $MaDL, $Manguoidung, $Maphong, $MaPT, $Matrangthai);
    if ($result === true) {
        // Cập nhật trạng thái lịch đặt sau khi lưu hóa đơn thành công
        $stmtTrangThai = $db->prepare('SELECT Tentrangthai FROM trangthai WHERE Matrangthai = :matrangthai');
        $stmtTrangThai->bindParam(':matrangthai', $Matrangthai);
        $stmtTrangThai->execute();
        $trangThaiResult = $stmtTrangThai->fetch(PDO::FETCH_ASSOC);
        
        if ($trangThaiResult) {
            $trangThaiMoi = $trangThaiResult['Tentrangthai'];
        } else {
            // Fallback cho các mã trạng thái phổ biến
            switch($Matrangthai) {
                case '1': $trangThaiMoi = 'Đã thanh toán'; break;
                case '0': $trangThaiMoi = 'Chưa thanh toán'; break;
                case '3': $trangThaiMoi = 'Chờ thanh toán'; break;
                case '4': $trangThaiMoi = 'Đang chờ'; break;
                default: $trangThaiMoi = 'Đã thanh toán'; break;
            }
        }
        
        $datlichModel->updateTrangThaiDatLich($MaDL, $trangThaiMoi);
        
        header('Location: /cnpm/views/hoadon/index.php?success=1');
        exit;
    } else {
        $errorMsg = is_array($result) && isset($result['error']) ? $result['error'] : 'Lưu hóa đơn thất bại!';
    }
}
// Lấy mã người dùng từ đặt lịch
if ($madl) {
    $datlich = $datlichModel->getDatLichById($madl);
    if ($datlich) {
        $manguoidung = $datlich->Manguoidung;
    }
}
// Lấy danh sách dịch vụ user đã chọn
if ($madl) {
    $stmt = $db->prepare('SELECT dv.Tendichvu, dv.Gia FROM chitietdichvu ctdv JOIN dichvu dv ON ctdv.MaDV = dv.MaDV WHERE ctdv.MaDL = :madl');
    $stmt->bindParam(':madl', $madl);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($services as $dv) {
        $tongtien += (int)$dv->Gia;
    }
}
// Lấy danh sách phương thức thanh toán
$phuongthucList = [];
$stmtPT = $db->prepare('SELECT MaPT, TenPT FROM phuongthuc');
$stmtPT->execute();
$phuongthucList = $stmtPT->fetchAll(PDO::FETCH_ASSOC);
// Lấy danh sách trạng thái
$trangthaiList = [];
$stmtTT = $db->prepare('SELECT Matrangthai, Tentrangthai FROM trangthai');
$stmtTT->execute();
$trangthaiList = $stmtTT->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
.hd-form {
    max-width: 600px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.hd-form-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.5rem;
    font-weight: bold;
}
.hd-form label {
    color: #ff4081;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.hd-form input, .hd-form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
    border-radius: 6px;
    font-size: 1rem;
    background: #fff;
}
.hd-form .hd-btn {
    width: 100%;
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 10px 0;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 8px;
    transition: background 0.2s;
}
.hd-form .hd-btn:hover {
    background: #e73370;
}
.hd-form .hd-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
    margin-top: 8px;
}
.hd-form .hd-back:hover {
    background: #ffe4ec;
}
.hd-form .hd-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.hd-form .hd-msg.success { color: #43a047; }
.hd-form .hd-msg.error { color: #e53935; }
.hd-form .service-list {
    background: #fff;
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 16px;
    border: 1px solid #ffb6d5;
}
.hd-form .service-list-title {
    color: #ff4081;
    font-weight: 600;
    margin-bottom: 6px;
}
.hd-form .service-item {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    border-bottom: 1px dashed #ffe4ec;
    font-size: 1rem;
}
.hd-form .service-item:last-child { border-bottom: none; }
.hd-form .service-total {
    text-align: right;
    color: #e84393;
    font-weight: bold;
    font-size: 1.1rem;
    margin-top: 8px;
}
@media (max-width: 600px) {
    .hd-form { padding: 10px; }
    .hd-form-title { font-size: 1.1rem; }
}
</style>
<form class="hd-form" id="hd-add-form" method="post">
    <div class="hd-form-title">Lập hóa đơn mới</div>
    <?php if (!empty($errorMsg)): ?>
        <div class="hd-msg error"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>
    <div class="hd-msg" id="hd-add-msg"></div>
    <label>Mã người dùng</label>
    <input type="text" name="manguoidung" value="<?php echo htmlspecialchars($manguoidung); ?>" readonly>
    <label>Dịch vụ đã chọn</label>
    <div class="service-list">
        <div class="service-list-title">Danh sách dịch vụ</div>
        <?php if (count($services)): foreach ($services as $dv): ?>
            <div class="service-item">
                <span><?php echo htmlspecialchars($dv->Tendichvu); ?></span>
                <span><?php echo number_format($dv->Gia); ?> VNĐ</span>
            </div>
        <?php endforeach; else: ?>
            <div>Không có dịch vụ nào!</div>
        <?php endif; ?>
        <div class="service-total">Tổng tiền: <?php echo number_format($tongtien); ?> VNĐ</div>
    </div>
    <input type="hidden" name="madl" value="<?php echo htmlspecialchars($madl); ?>">
    <input type="hidden" name="tongtien" value="<?php echo $tongtien; ?>">
    <label for="phuongthuc">Phương thức thanh toán</label>
    <select name="phuongthuc" id="phuongthuc" required>
        <option value="">-- Chọn phương thức --</option>
        <?php foreach ($phuongthucList as $pt): ?>
            <option value="<?php echo $pt['MaPT']; ?>"><?php echo htmlspecialchars($pt['TenPT']); ?></option>
        <?php endforeach; ?>
    </select>
    <label for="trangthai">Trạng thái</label>
    <select name="trangthai" id="trangthai" required>
        <option value="">-- Chọn trạng thái --</option>
        <?php foreach ($trangthaiList as $tt): ?>
            <option value="<?php echo $tt['Matrangthai']; ?>"><?php echo htmlspecialchars($tt['Tentrangthai']); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="hd-btn">Lưu hóa đơn</button>
    <button type="button" class="hd-btn hd-back" onclick="window.history.back()">Quay lại</button>
</form> 