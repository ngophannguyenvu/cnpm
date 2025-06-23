<?php 
class DanhGiaModel 
{ 
private $conn; 
private $table_name = "danhgia"; //
public function __construct($db) 
{ 
$this->conn = $db; 
} 
public function getDanhGias() 
{ 
$query = "SELECT dg.MaDG, dg.Danhgiasao, dg.Nhanxet, dg.Ngaydanhgia, dg.Manguoidung, dg.MaHD FROM " . $this->table_name . " dg ";
$stmt = $this->conn->prepare($query); 
$stmt->execute(); 
$result = $stmt->fetchAll(PDO::FETCH_OBJ); 
return $result; 
} 
public function getDanhGiaById($id) 
{ 
$query = "SELECT dg.MaDG, dg.Danhgiasao, dg.Nhanxet, dg.Ngaydanhgia, dg.Manguoidung, dg.MaHD FROM " . $this->table_name . " dg WHERE dg.MaDG = :id";

$stmt = $this->conn->prepare($query); 
$stmt->bindParam(':id', $id); 
$stmt->execute(); 
$result = $stmt->fetch(PDO::FETCH_OBJ); 
return $result; 
}
// Thêm mới 
public function addDanhGia($Danhgiasao,$Nhanxet,$Ngaydanhgia,$Manguoidung,$MaHD)       
{
    $errors = [];

    if (empty($Danhgiasao)) {
        $errors['Danhgiasao'] = 'Danh gia sao không được để trống';
    }
    if (empty($Nhanxet)) {
        $errors['Nhanxet'] = 'Nhanxet không được để trống';
    }
    if (empty($Manguoidung)) {
        $errors['Manguoidung'] = 'Manguoidung không được để trống';
    }
    if (empty($MaHD)) {
        $errors['MaHD'] = 'MaHD không được để trống';
    }
    if (count($errors) > 0) {
        return $errors;
    }
//INSERT INTO danhgia (Danhgiasao,Nhanxet,Ngaydanhgia,Manguoidung,MaHD) VALUE ("4","hssj",NOW(),"1","1")
    $query = "INSERT INTO " . $this->table_name . " ( Danhgiasao,Nhanxet,Ngaydanhgia,Manguoidung,MaHD) 
    VALUES (:Danhgiasao,:Nhanxet,:Ngaydanhgia,:Manguoidung,:MaHD)";
    $stmt = $this->conn->prepare($query);

    $Danhgiasao = htmlspecialchars(strip_tags($Danhgiasao));
    $Nhanxet = htmlspecialchars(strip_tags($Nhanxet));
    $Ngaydanhgia = (new DateTime())->format('Y-m-d H:i:s');
    $Manguoidung = htmlspecialchars(strip_tags($Manguoidung));
    $MaHD = htmlspecialchars(strip_tags($MaHD));

    $stmt->bindParam(':Danhgiasao', $Danhgiasao);
    $stmt->bindParam(':Nhanxet', $Nhanxet);
    $stmt->bindParam(':Ngaydanhgia', $Ngaydanhgia);
    $stmt->bindParam(':Manguoidung', $Manguoidung);
    $stmt->bindParam(':MaHD', $MaHD);

    if ($stmt->execute()) {
        return true;
    }

    return false;
}

public function updateDanhGia($id, $Danhgiasao,$Nhanxet,$Ngaydanhgia,$Manguoidung,$MaHD)
{
    $query = "UPDATE " . $this->table_name . " SET Danhgiasao = :Danhgiasao, Nhanxet = :Nhanxet,Ngaydanhgia = :Ngaydanhgia, Manguoidung = :Manguoidung,
    MaHD = :MaHD WHERE MaDG = :id";
    $stmt = $this->conn->prepare($query);


    $Danhgiasao = htmlspecialchars(strip_tags($Danhgiasao));
    $Nhanxet = htmlspecialchars(strip_tags($Nhanxet));
    $Ngaydanhgia = (new DateTime())->format('Y-m-d H:i:s');
    $Manguoidung = htmlspecialchars(strip_tags($Manguoidung));
    $MaHD = htmlspecialchars(strip_tags($MaHD));

    $stmt->bindParam(':Danhgiasao', $Danhgiasao);
    $stmt->bindParam(':Nhanxet', $Nhanxet);
    $stmt->bindParam(':Ngaydanhgia', $Ngaydanhgia);
    $stmt->bindParam(':Manguoidung', $Manguoidung);
    $stmt->bindParam(':MaHD', $MaHD);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

public function deleteDanhGia($MaDG)
{
    $query = "DELETE FROM " . $this->table_name . " WHERE MaDG = :MaDG";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':MaDG', $MaDG);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// Kiểm tra xem người dùng đã đánh giá cho hóa đơn này chưa
public function checkUserHasRated($maHD, $manguoidung)
{
    $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE MaHD = :MaHD AND Manguoidung = :Manguoidung";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':MaHD', $maHD);
    $stmt->bindParam(':Manguoidung', $manguoidung);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

// Lấy đánh giá theo mã hóa đơn
public function getDanhGiaByMaHD($maHD)
{
    $query = "SELECT dg.MaDG, dg.Danhgiasao, dg.Nhanxet, dg.Ngaydanhgia, dg.Manguoidung, dg.MaHD 
              FROM " . $this->table_name . " dg 
              WHERE dg.MaHD = :MaHD";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':MaHD', $maHD);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    return $result;
}

// Lấy tất cả đánh giá của một người dùng
public function getDanhGiasByUser($manguoidung)
{
    $query = "SELECT dg.MaDG, dg.Danhgiasao, dg.Nhanxet, dg.Ngaydanhgia, dg.Manguoidung, dg.MaHD 
              FROM " . $this->table_name . " dg 
              WHERE dg.Manguoidung = :Manguoidung 
              ORDER BY dg.Ngaydanhgia DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':Manguoidung', $manguoidung);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    if (!$result) return [];
    return $result;
}
} 