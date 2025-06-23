<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cnpm/app/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cnpm/app/models/DanhGiaModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cnpm/app/helpers/SessionHelper.php';

class DanhGiaApiController
{
    private $danhGiaModel;
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->danhGiaModel = new DanhGiaModel($this->db->getConnection());
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';

        switch ($method) {
            case 'GET':
                $this->handleGet($action);
                break;
            case 'POST':
                $this->handlePost($action);
                break;
            case 'PUT':
                $this->handlePut($action);
                break;
            case 'DELETE':
                $this->handleDelete($action);
                break;
            default:
                $this->sendResponse(['success' => false, 'message' => 'Method not allowed'], 405);
        }
    }

    private function handleGet($action)
    {
        switch ($action) {
            case 'list':
                $this->getDanhGias();
                break;
            case 'detail':
                $this->getDanhGiaById();
                break;
            case 'user_ratings':
                $this->getUserRatings();
                break;
            case 'check_rating':
                $this->checkUserRating();
                break;
            default:
                $this->sendResponse(['success' => false, 'message' => 'Action not found'], 404);
        }
    }

    private function handlePost($action)
    {
        switch ($action) {
            case 'add':
                $this->addDanhGia();
                break;
            case 'customer_rate':
                $this->customerRate();
                break;
            default:
                $this->sendResponse(['success' => false, 'message' => 'Action not found'], 404);
        }
    }

    private function handlePut($action)
    {
        switch ($action) {
            case 'update':
                $this->updateDanhGia();
                break;
            default:
                $this->sendResponse(['success' => false, 'message' => 'Action not found'], 404);
        }
    }

    private function handleDelete($action)
    {
        switch ($action) {
            case 'delete':
                $this->deleteDanhGia();
                break;
            default:
                $this->sendResponse(['success' => false, 'message' => 'Action not found'], 404);
        }
    }

    private function getDanhGias()
    {
        try {
            $danhGias = $this->danhGiaModel->getDanhGias();
            $this->sendResponse(['success' => true, 'data' => $danhGias]);
        } catch (Exception $e) {
            $this->sendResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function getDanhGiaById()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->sendResponse(['success' => false, 'message' => 'ID is required'], 400);
            return;
        }

        try {
            $danhGia = $this->danhGiaModel->getDanhGiaById($id);
            if ($danhGia) {
                $this->sendResponse(['success' => true, 'data' => $danhGia]);
            } else {
                $this->sendResponse(['success' => false, 'message' => 'Danh gia not found'], 404);
            }
        } catch (Exception $e) {
            $this->sendResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function addDanhGia()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $danhGiasao = $data['Danhgiasao'] ?? '';
        $nhanxet = $data['Nhanxet'] ?? '';
        $ngaydanhgia = $data['Ngaydanhgia'] ?? '';
        $manguoidung = $data['Manguoidung'] ?? '';
        $maHD = $data['MaHD'] ?? '';

        $result = $this->danhGiaModel->addDanhGia($danhGiasao, $nhanxet, $ngaydanhgia, $manguoidung, $maHD);
        
        if ($result === true) {
            $this->sendResponse(['success' => true, 'message' => 'Danh gia added successfully']);
        } else {
            $this->sendResponse(['success' => false, 'errors' => $result], 400);
        }
    }

    private function updateDanhGia()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $id = $data['MaDG'] ?? '';
        $danhGiasao = $data['Danhgiasao'] ?? '';
        $nhanxet = $data['Nhanxet'] ?? '';
        $ngaydanhgia = $data['Ngaydanhgia'] ?? '';
        $manguoidung = $data['Manguoidung'] ?? '';
        $maHD = $data['MaHD'] ?? '';

        $result = $this->danhGiaModel->updateDanhGia($id, $danhGiasao, $nhanxet, $ngaydanhgia, $manguoidung, $maHD);
        
        if ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Danh gia updated successfully']);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to update danh gia'], 500);
        }
    }

    private function deleteDanhGia()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $maDG = $data['MaDG'] ?? '';

        if (!$maDG) {
            $this->sendResponse(['success' => false, 'message' => 'MaDG is required'], 400);
            return;
        }

        $result = $this->danhGiaModel->deleteDanhGia($maDG);
        
        if ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Danh gia deleted successfully']);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to delete danh gia'], 500);
        }
    }

    // Endpoint mới cho khách hàng đánh giá
    private function customerRate()
    {
        // Kiểm tra đăng nhập
        $user = SessionHelper::getUser();
        if (!$user) {
            $this->sendResponse(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        $danhGiasao = $data['Danhgiasao'] ?? '';
        $nhanxet = $data['Nhanxet'] ?? '';
        $maHD = $data['MaHD'] ?? '';

        // Validate dữ liệu
        if (empty($danhGiasao) || empty($nhanxet) || empty($maHD)) {
            $this->sendResponse(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin đánh giá'], 400);
            return;
        }

        // Kiểm tra xem đã đánh giá chưa
        if ($this->danhGiaModel->checkUserHasRated($maHD, $user['id'])) {
            $this->sendResponse(['success' => false, 'message' => 'Bạn đã đánh giá cho hóa đơn này rồi'], 400);
            return;
        }

        // Thêm đánh giá
        $result = $this->danhGiaModel->addDanhGia($danhGiasao, $nhanxet, '', $user['id'], $maHD);
        
        if ($result === true) {
            $this->sendResponse(['success' => true, 'message' => 'Đánh giá thành công! Cảm ơn bạn đã chia sẻ ý kiến.']);
        } else {
            $this->sendResponse(['success' => false, 'errors' => $result], 400);
        }
    }

    // Lấy đánh giá của người dùng
    private function getUserRatings()
    {
        $user = SessionHelper::getUser();
        if (!$user) {
            $this->sendResponse(['success' => false, 'message' => 'Bạn cần đăng nhập'], 401);
            return;
        }

        try {
            error_log('User ID for ratings: ' . $user['id']);
            $ratings = $this->danhGiaModel->getDanhGiasByUser($user['id']);
            if (!is_array($ratings)) $ratings = [];
            $this->sendResponse(['success' => true, 'data' => $ratings]);
        } catch (Exception $e) {
            error_log('Error in getUserRatings: ' . $e->getMessage());
            $this->sendResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Kiểm tra xem người dùng đã đánh giá cho hóa đơn chưa
    private function checkUserRating()
    {
        $user = SessionHelper::getUser();
        if (!$user) {
            $this->sendResponse(['success' => false, 'message' => 'Bạn cần đăng nhập'], 401);
            return;
        }

        $maHD = $_GET['maHD'] ?? '';
        if (!$maHD) {
            $this->sendResponse(['success' => false, 'message' => 'Mã hóa đơn là bắt buộc'], 400);
            return;
        }

        try {
            $hasRated = $this->danhGiaModel->checkUserHasRated($maHD, $user['id']);
            $this->sendResponse(['success' => true, 'has_rated' => $hasRated]);
        } catch (Exception $e) {
            $this->sendResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Xử lý request
$controller = new DanhGiaApiController();
$controller->handleRequest();
?>