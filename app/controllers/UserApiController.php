<?php
require_once('app/config/database.php');

require_once('app/models/UserModel.php');

class UserApiController
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    // Lấy danh sách
    public function index()
    {
        header('Content-Type: application/json');
        $users = $this->userModel->getUsers();
        echo json_encode($users);
    }

    // Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $user = $this->userModel->getUserById($id);
        
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Người dùng không tìm thấy']);
        }
    }

    // Thêm người dùng mới
    public function register()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $hoten = $data['hoten'] ?? '';
        $sdt = $data['sdt'] ?? '';
        $diachi = $data['diachi'] ?? '';
        $email = $data['email'] ?? '';
        $ngaysinh = $data['ngaysinh'] ?? '';
        $gioitinh = $data['gioitinh'] ?? '';
        $matkhau = $data['matkhau'] ?? '';

        if (empty($hoten) || empty($sdt) || empty($email) || empty($matkhau)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Email không hợp lệ']);
            return;
        }
        if (!preg_match('/^[0-9]{10,11}$/', $sdt)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Số điện thoại không hợp lệ']);
            return;
        }
        if (strlen($matkhau) < 6) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Mật khẩu phải từ 6 ký tự']);
            return;
        }
        $result = $this->userModel->registerUser($hoten, $sdt, $diachi, $email, $ngaysinh, $gioitinh, $matkhau);
        if ($result === true) {
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Đăng ký thất bại']);
        }
    }

    // Cập nhật thông tin người dùng
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data) || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Dữ liệu hoặc ID không hợp lệ']);
            return;
        }

        // Lấy dữ liệu từ request
        $hoten = $data['hoten'] ?? '';
        $sdt = $data['sdt'] ?? '';
        $diachi = $data['diachi'] ?? '';
        $email = $data['email'] ?? '';
        $ngaysinh = $data['ngaysinh'] ?? '';
        $gioitinh = $data['gioitinh'] ?? '';

        // Kiểm tra dữ liệu bắt buộc
        if (empty($hoten) || empty($sdt) || empty($email)) {
            http_response_code(400);
            echo json_encode(['error' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            return;
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email không hợp lệ']);
            return;
        }

        // Kiểm tra định dạng số điện thoại
        if (!preg_match('/^[0-9]{10,11}$/', $sdt)) {
            http_response_code(400);
            echo json_encode(['error' => 'Số điện thoại không hợp lệ']);
            return;
        }

        // Cập nhật thông tin người dùng
        $result = $this->userModel->updateUser(
            $id,
            $hoten,
            $sdt,
            $diachi,
            $email,
            $ngaysinh,
            $gioitinh
        );

        if ($result === true) {
            echo json_encode([
                'message' => 'Cập nhật thông tin thành công',
                'data' => [
                    'id' => $id,
                    'hoten' => $hoten,
                    'sdt' => $sdt,
                    'email' => $email
                ]
            ]);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }

    // Xóa người dùng
    public function deleteUser($id)
    {
        header('Content-Type: application/json');
        
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID không hợp lệ']);
            return;
        }

        $result = $this->userModel->deleteUser($id);

        if ($result === true) {
            echo json_encode([
                'message' => 'Xóa người dùng thành công',
                'id' => $id
            ]);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }

    public function login()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ']);
            return;
        }
        $email = $data['email'] ?? '';
        $matkhau = $data['matkhau'] ?? '';
        if (empty($email) || empty($matkhau)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Vui lòng nhập email và mật khẩu']);
            return;
        }
        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Email hoặc mật khẩu không đúng']);
            return;
        }
        // Lưu session
        // session_start(); // Đã được gọi ở index.php
        $_SESSION['user_id'] = $user->Manguoidung;
        $_SESSION['username'] = $user->Hoten;
        $_SESSION['role'] = $user->Vaitro ?? 'user';
        echo json_encode(['success' => true, 'message' => 'Đăng nhập thành công', 'role' => $_SESSION['role']]);
    }

    public function logout()
    {
        // session_start(); // Đã được gọi ở index.php, gọi lại sẽ gây lỗi
        session_unset();
        session_destroy();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Đăng xuất thành công']);
    }

    public function profile()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Chưa đăng nhập']);
            return;
        }
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if ($user) {
            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Không tìm thấy người dùng']);
        }
    }

    public function updateProfile()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Chưa đăng nhập']);
            return;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $userId = $_SESSION['user_id'];

        $result = $this->userModel->updateUserProfile(
            $userId,
            $data['hoten'],
            $data['email'],
            $data['sdt'],
            $data['ngaysinh'],
            $data['diachi'],
            $data['gioitinh'],
            $data['matkhau'] // Mật khẩu mới, có thể trống
        );

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật hồ sơ thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Cập nhật thất bại']);
        }
    }
}