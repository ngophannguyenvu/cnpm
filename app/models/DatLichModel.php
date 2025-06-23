<?php 
class DatLichModel 
{ 
private $conn; 
private $table_name = "datlich"; //
public function __construct($db) 
{ 
$this->conn = $db; 
} 
public function getDatLich() 
{ 
$query = "SELECT dl.MaDL, dl.Manguoidung, dl.Thoigiandatlich, dl.Trangthai_ FROM " . $this->table_name . " dl ";
$stmt = $this->conn->prepare($query); 
$stmt->execute(); 
$result = $stmt->fetchAll(PDO::FETCH_OBJ); 
return $result; 
} 
public function getDatLichById($id) 
{ 
    $query = "SELECT dl.MaDL, dl.Manguoidung, dl.Thoigiandatlich, dl.Trangthai_ 
    FROM " . $this->table_name . " dl 
    WHERE dl.MaDL = 1";

$stmt = $this->conn->prepare($query); 
//$stmt->bindParam(':id', $id); 
$stmt->execute(); 
$result = $stmt->fetch(PDO::FETCH_OBJ);
return $result;

}
// Thêm mới danh mục
public function addDatLich($Manguoidung, $Thoigiandatlich,$Trangthai)
{
    $errors = [];

    if (empty($Manguoidung)) {
        $errors['Manguoidung'] = 'Ma nguoi không được để trống';
    }
    if (empty($Thoigiandatlich)) {
        $errors['Thoigiandatlich'] = 'Thoigiandatlich không được để trống';
    }
    if (empty($Trangthai)) {
        $errors['Trangthai'] = 'Trangthai không được để trống';
    }

    if (count($errors) > 0) {
        return $errors;
    }
//INSERT INTO datlich ( Manguoidung,Thoigiandatlich, Trangthai_) VALUE (1,NOW(),"123")
    $query = "INSERT INTO " . $this->table_name . " ( Manguoidung,Thoigiandatlich, Trangthai_) 
    VALUES (:Manguoidung, :Thoigiandatlich,:Trangthai)";
    $stmt = $this->conn->prepare($query);

    $Manguoidung = htmlspecialchars(strip_tags($Manguoidung));
    $Thoigiandatlich = (new DateTime())->format('Y-m-d H:i:s');
    $Trangthai = htmlspecialchars(strip_tags($Trangthai));
    
    $stmt->bindParam(':Manguoidung', $Manguoidung);
    $stmt->bindParam(':Thoigiandatlich', $Thoigiandatlich);
    $stmt->bindParam(':Trangthai', $Trangthai);

    if ($stmt->execute()) {
        return true;
    }

    return false;
}

public function updateDatLich($id, $Manguoidung, $Thoigiandatlich,$Trangthai)
{
    $query = "UPDATE " . $this->table_name . " SET Manguoidung = :Manguoidung, Thoigiandatlich = :Thoigiandatlich,Trangthai_ = :Trangthai  WHERE MaDL = :id";
    $stmt = $this->conn->prepare($query);


    $Manguoidung = htmlspecialchars(strip_tags($Manguoidung));
    $Thoigiandatlich = (new DateTime())->format('Y-m-d H:i:s');
    $Trangthai = htmlspecialchars(strip_tags($Trangthai));

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':Manguoidung', $Manguoidung);
    $stmt->bindParam(':Thoigiandatlich', $Thoigiandatlich);
    $stmt->bindParam(':Trangthai', $Trangthai);
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

public function deleteDatLich($MaDL)
{
    $query = "DELETE FROM " . $this->table_name . " WHERE MaDL = :MaDL";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':MaDL', $MaDL);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

public function createBooking($userId, $thoigian, $trangthai, $dichvu_ids)
{
    $this->conn->beginTransaction();

    try {
        // Bước 1: Tạo một bản ghi trong bảng `datlich`
        $query_datlich = "INSERT INTO " . $this->table_name . " (Manguoidung, Thoigiandatlich, Trangthai_) VALUES (:userId, :thoigian, :trangthai)";
        $stmt_datlich = $this->conn->prepare($query_datlich);
        $stmt_datlich->bindParam(':userId', $userId);
        $stmt_datlich->bindParam(':thoigian', $thoigian);
        $stmt_datlich->bindParam(':trangthai', $trangthai);
        $stmt_datlich->execute();

        // Lấy ID của lịch đặt vừa được tạo
        $maDL = $this->conn->lastInsertId();

        // Bước 2: Thêm các dịch vụ vào bảng `chitietdichvu`
        $query_chitiet = "INSERT INTO chitietdichvu (MaDL, MaDV) VALUES (:maDL, :maDV)";
        $stmt_chitiet = $this->conn->prepare($query_chitiet);

        foreach ($dichvu_ids as $maDV) {
            $stmt_chitiet->bindParam(':maDL', $maDL);
            $stmt_chitiet->bindParam(':maDV', $maDV);
            $stmt_chitiet->execute();
        }

        // Nếu mọi thứ thành công, commit transaction
        $this->conn->commit();
        return true;

    } catch (PDOException $e) {
        // Nếu có lỗi, rollback transaction
        $this->conn->rollBack();
        return ['error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
    }
}

public function getBookingHistoryByUser($userId)
{
    try {
        // Lấy tất cả các lịch đã đặt của người dùng
        $query_bookings = "SELECT MaDL, Thoigiandatlich, Trangthai_ FROM " . $this->table_name . " WHERE Manguoidung = :userId ORDER BY Thoigiandatlich DESC";
        $stmt_bookings = $this->conn->prepare($query_bookings);
        $stmt_bookings->bindParam(':userId', $userId);
        $stmt_bookings->execute();
        $bookings = $stmt_bookings->fetchAll(PDO::FETCH_ASSOC);

        // Với mỗi lịch đặt, lấy chi tiết dịch vụ
        $query_services = "SELECT dv.Tendichvu, dv.Gia FROM dichvu dv JOIN chitietdichvu ctdv ON dv.MaDV = ctdv.MaDV WHERE ctdv.MaDL = :maDL";
        $stmt_services = $this->conn->prepare($query_services);

        for ($i = 0; $i < count($bookings); $i++) {
            $stmt_services->bindParam(':maDL', $bookings[$i]['MaDL']);
            $stmt_services->execute();
            $bookings[$i]['services'] = $stmt_services->fetchAll(PDO::FETCH_ASSOC);
        }

        return $bookings;

    } catch (PDOException $e) {
        return ['error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
    }
}

}