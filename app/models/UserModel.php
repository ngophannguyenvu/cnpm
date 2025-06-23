<?php 
class UserModel 
{ 
private $conn; 
private $table_name = "users"; //
public function __construct($db) 
{ 
$this->conn = $db; 
} 
public function getUsers() 
{ 
$query = "SELECT u.Manguoidung, u.Hoten, u.SDT, u.DiaChi, u.Email, u.Ngaysinh, u.Gioitinh FROM  " . $this->table_name . " u ";
$stmt = $this->conn->prepare($query); 
$stmt->execute(); 
$result = $stmt->fetchAll(PDO::FETCH_OBJ); 
return $result; 
} 
public function getUserById($id) 
{ 
$query = "SELECT u.Manguoidung, u.Hoten, u.SDT, u.DiaChi, u.Email, u.Ngaysinh, u.Gioitinh FROM " . $this->table_name . " u WHERE u.Manguoidung = :id";

$stmt = $this->conn->prepare($query); 
$stmt->bindParam(':id', $id); 
$stmt->execute(); 
$result = $stmt->fetch(PDO::FETCH_OBJ); 
return $result; 
}

public function addUser($hoten, $sdt, $diachi, $email, $ngaysinh, $gioitinh) 
{
    try {
        // Kiểm tra email đã tồn tại chưa
        $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Email = :email";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return ['error' => 'Email đã tồn tại'];
        }

        // Thêm người dùng mới
        $query = "INSERT INTO " . $this->table_name . " (Hoten, SDT, DiaChi, Email, Ngaysinh, Gioitinh) 
                 VALUES (:hoten, :sdt, :diachi, :email, :ngaysinh, :gioitinh)";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind các tham số
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':diachi', $diachi);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':ngaysinh', $ngaysinh);
        $stmt->bindParam(':gioitinh', $gioitinh);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return ['error' => 'Không thể thêm người dùng'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Lỗi: ' . $e->getMessage()];
    }
}

public function updateUser($id, $hoten, $sdt, $diachi, $email, $ngaysinh, $gioitinh) 
{
    try {
        // Kiểm tra người dùng tồn tại
        $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Manguoidung = :id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            return ['error' => 'Người dùng không tồn tại'];
        }

        // Kiểm tra email đã tồn tại chưa (trừ email của người dùng hiện tại)
        $checkEmailQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Email = :email AND Manguoidung != :id";
        $checkEmailStmt = $this->conn->prepare($checkEmailQuery);
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->bindParam(':id', $id);
        $checkEmailStmt->execute();
        $emailResult = $checkEmailStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($emailResult['count'] > 0) {
            return ['error' => 'Email đã tồn tại'];
        }

        // Cập nhật thông tin người dùng
        $query = "UPDATE " . $this->table_name . " 
                 SET Hoten = :hoten, 
                     SDT = :sdt, 
                     DiaChi = :diachi, 
                     Email = :email, 
                     Ngaysinh = :ngaysinh, 
                     Gioitinh = :gioitinh 
                 WHERE Manguoidung = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind các tham số
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':diachi', $diachi);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':ngaysinh', $ngaysinh);
        $stmt->bindParam(':gioitinh', $gioitinh);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return ['error' => 'Không thể cập nhật thông tin người dùng'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Lỗi: ' . $e->getMessage()];
    }
}

public function deleteUser($id) 
{
    $query = "DELETE FROM " . $this->table_name . " WHERE Manguoidung = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

public function registerUser($hoten, $sdt, $diachi, $email, $ngaysinh, $gioitinh, $matkhau)
{
    try {
        // Kiểm tra email đã tồn tại chưa
        $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Email = :email";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if ($result['count'] > 0) {
            return ['error' => 'Email đã tồn tại'];
        }
        // Hash mật khẩu
        $hashedPassword = password_hash($matkhau, PASSWORD_DEFAULT);
        // Thêm người dùng mới
        $query = "INSERT INTO " . $this->table_name . " (Hoten, SDT, DiaChi, Email, Ngaysinh, Gioitinh, Matkhau) 
                 VALUES (:hoten, :sdt, :diachi, :email, :ngaysinh, :gioitinh, :matkhau)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':diachi', $diachi);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':ngaysinh', $ngaysinh);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':matkhau', $hashedPassword);
        if ($stmt->execute()) {
            return true;
        } else {
            return ['error' => 'Không thể thêm người dùng'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Lỗi: ' . $e->getMessage()];
    }
}

public function getUserByEmail($email)
{
    $query = "SELECT * FROM " . $this->table_name . " WHERE Email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function updateUserProfile($id, $hoten, $email, $sdt, $ngaysinh, $diachi, $gioitinh, $matkhau)
{
    try {
        // Kiểm tra email đã tồn tại ở người dùng khác chưa
        $checkEmailQuery = "SELECT Manguoidung FROM " . $this->table_name . " WHERE Email = :email AND Manguoidung != :id";
        $checkStmt = $this->conn->prepare($checkEmailQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        if ($checkStmt->fetch()) {
            return ['error' => 'Email đã được sử dụng bởi một tài khoản khác.'];
        }

        // Xây dựng câu lệnh UPDATE
        $query = "UPDATE " . $this->table_name . " SET Hoten = :hoten, Email = :email, SDT = :sdt, Ngaysinh = :ngaysinh, DiaChi = :diachi, Gioitinh = :gioitinh";
        
        // Nếu có mật khẩu mới thì cập nhật
        if (!empty($matkhau)) {
            $hashedPassword = password_hash($matkhau, PASSWORD_DEFAULT);
            $query .= ", Matkhau = :matkhau";
        }
        
        $query .= " WHERE Manguoidung = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind các tham số
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':ngaysinh', $ngaysinh);
        $stmt->bindParam(':diachi', $diachi);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':id', $id);
        
        if (!empty($matkhau)) {
            $stmt->bindParam(':matkhau', $hashedPassword);
        }
        
        if ($stmt->execute()) {
            // Cập nhật lại session nếu tên thay đổi
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                $_SESSION['username'] = $hoten;
            }
            return true;
        } else {
            return ['error' => 'Không thể cập nhật hồ sơ.'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
    }
}

} 