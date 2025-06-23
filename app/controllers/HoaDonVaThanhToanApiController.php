<?php
require_once('app/config/database.php');

require_once('app/models/HoaDonVaThanhToanModel.php');
require_once('app/models/DatLichModel.php');


class HoaDonVaThanhToanApiController
{
    private $hoaDonVaThanhToanModel;
    private $datLichModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->hoaDonVaThanhToanModel = new HoaDonVaThanhToanModel($this->db);
        $this->datLichModel = new DatLichModel($this->db);
    }

    // Lấy danh sách
    public function index()
    {
        header('Content-Type: application/json');
        $hoaDonVaThanhToans = $this->hoaDonVaThanhToanModel->getHoaDonVaThanhToans();
        echo json_encode($hoaDonVaThanhToans);
    }

    // Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $hoaDonVaThanhToan = $this->hoaDonVaThanhToanModel->getHoaDonVaThanhToanById($id);
        
        if ($hoaDonVaThanhToan) {
            echo json_encode($hoaDonVaThanhToan);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Hóa đơn không tìm thấy']);
        }
    }

    // Thêm mới hóa đơn và thanh toán
    public function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
            return;
        }
        $NgayThanhToan = $data['NgayThanhToan'] ?? null;
        $Tongtien = $data['Tongtien'] ?? null;
        $MaDL = $data['MaDL'] ?? null;
        $Manguoidung = $data['Manguoidung'] ?? null;
        $Maphong = $data['Maphong'] ?? null;
        $MaPT = $data['MaPT'] ?? null;
        $Matrangthai = $data['Matrangthai'] ?? null;
        if (!$NgayThanhToan || !$Tongtien || !$Manguoidung || !$MaDL|| !$Maphong || !$MaPT || !$Matrangthai) {
            http_response_code(400);
            echo json_encode(['error' => 'Vui lòng nhập đầy đủ thông tin']);
            return;
        }
        $result = $this->hoaDonVaThanhToanModel->addHoaDonVaThanhToan(
            $NgayThanhToan, 
            $Tongtien,
            $MaDL,
             $Manguoidung,
             $Maphong,
              $MaPT, 
             $Matrangthai)
             ;
             if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } elseif ($result === true) {
                // Cập nhật trạng thái lịch đặt sau khi lập hóa đơn thành công
                $trangThaiMoi = $this->getTrangThaiTheoMaTrangThai($Matrangthai);
                $this->datLichModel->updateTrangThaiDatLich($MaDL, $trangThaiMoi);
                
                http_response_code(201);
                echo json_encode(['message' => 'Hóa đơn được thêm thành công']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => $result['error'] ?? 'Thêm hóa đơn thất bại']);
            }
    }

    // Cập nhật hóa đơn và thanh toán
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
            return;
        }
        $NgayThanhToan = $data['NgayThanhToan'] ?? null;
        $Tongtien = $data['Tongtien'] ?? null;
        $MaDL = $data['MaDL'] ?? null;
        $Manguoidung = $data['Manguoidung'] ?? null;
        $Maphong = $data['Maphong'] ?? null;
        $MaPT = $data['MaPT'] ?? null;
        $Matrangthai = $data['Matrangthai'] ?? null;
        if (!$NgayThanhToan || !$Tongtien || !$MaDL|| !$Manguoidung || !$Maphong || !$MaPT || !$Matrangthai) {
            http_response_code(400);
            echo json_encode(['error' => 'Vui lòng nhập đầy đủ thông tin']);
            return;
        }
        $result = $this->hoaDonVaThanhToanModel->updateHoaDonVaThanhToan($id, $NgayThanhToan, $Tongtien,$MaDL, $Manguoidung, $Maphong, $MaPT, $Matrangthai);
        if ($result === true) {
            // Cập nhật trạng thái lịch đặt sau khi cập nhật hóa đơn thành công
            $trangThaiMoi = $this->getTrangThaiTheoMaTrangThai($Matrangthai);
            $this->datLichModel->updateTrangThaiDatLich($MaDL, $trangThaiMoi);
            
            echo json_encode(['message' => 'Cập nhật hóa đơn thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Cập nhật hóa đơn thất bại']);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID không hợp lệ']);
            return;
        }

        $result = $this->hoaDonVaThanhToanModel->deleteHoaDonVaThanhToan($id);
        if ($result) {
            echo json_encode(['message' => 'Xóa hóa đơn thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Xóa hóa đơn thất bại']);
        }
    }

    // Hàm helper để lấy tên trạng thái theo mã trạng thái
    private function getTrangThaiTheoMaTrangThai($maTrangThai)
    {
        $query = "SELECT Tentrangthai FROM trangthai WHERE Matrangthai = :maTrangThai";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maTrangThai', $maTrangThai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['Tentrangthai'];
        }
        
        // Fallback cho các mã trạng thái phổ biến
        switch($maTrangThai) {
            case '1': return 'Đã thanh toán';
            case '0': return 'Chưa thanh toán';
            case '3': return 'Chờ thanh toán';
            case '4': return 'Đang chờ';
            default: return 'Đã thanh toán';
        }
    }

}