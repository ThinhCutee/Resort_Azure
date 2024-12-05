<?php
// Kết nối đến cơ sở dữ liệu
include("essentials.php");
function connect()
{
    $host = 'localhost';
    $db_name = 'azuredb';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("set names utf8");
        return $conn;
    } catch (PDOException $e) {
        echo 'Lỗi kết nối: ' . $e->getMessage();
        return null;
    }
}

// Đăng nhập
function login1($admin_name, $admin_pass)
{
    $conn = connect();

    if ($conn) {
        $query = 'SELECT * FROM admin WHERE admin_name = :admin_name AND admin_pass = :admin_pass AND trang_thai = 1';
        $stmt = $conn->prepare($query);

        // $admin_pass = md5($admin_pass);

        $stmt->bindParam(':admin_name', $admin_name);
        $stmt->bindParam(':admin_pass', $admin_pass);

        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count > 0) {
            $_SESSION['adminLogin'] = $admin_name;
            alert('success', 'Login successful - Đăng nhập thành công !');
            direct('./dashboard/index.php');
        } else {
            alert('error', 'Login fail - Sai tên đăng nhập/ mật khẩu hoặc tài khoản đã dừng hoạt động !');
        }
    }
}
function login($admin_name, $admin_pass)
{
    $conn = connect();

    if ($conn) {
        // Thực hiện phép JOIN giữa bảng admin và nhân viên để lấy phòng ban
        $query = 'SELECT a.*, n.phong_ban FROM admin a
                  JOIN nhanvien n ON a.id = n.id_admin
                  WHERE a.admin_name = :admin_name AND a.admin_pass = :admin_pass AND a.trang_thai = 1';
        $stmt = $conn->prepare($query);

        // $admin_pass = md5($admin_pass); // Nếu mật khẩu cần mã hóa MD5, bạn có thể bỏ comment dòng này

        $stmt->bindParam(':admin_name', $admin_name);
        $stmt->bindParam(':admin_pass', $admin_pass);

        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Lưu thông tin đăng nhập vào session
            $_SESSION['adminLogin'] = $admin_name;
            $_SESSION['phong_ban'] = $user['phong_ban'];  // Lưu phòng ban vào session

            // Dựa vào phòng ban, chuyển hướng đến menu khác nhau
            switch ($user['phong_ban']) {
                case 'AD':
                    direct('./dashboard/index.php');
                    break;
                case 'LT':
                    direct('./dashboard/index.php');
                    break;
                case 'DV-Spa':
                    direct('./dashboard/index.php');
                    break;
                case 'DV-HoBoi':
                    direct('./dashboard/index.php');
                    break;
                case 'DV-NhaHang':
                    direct('./dashboard/index.php');
                    break;
                case 'DV-Gym':
                    direct('./dashboard/index.php');
                    break;
                case 'DV-Golf':
                    direct('./dashboard/index.php');
                    break;
                default:
                    direct('./dashboard/index.php');
                    break;
            }

            alert('success', 'Login successful - Đăng nhập thành công !');
        } else {
            alert('error', 'Login fail - Sai tên đăng nhập/ mật khẩu hoặc tài khoản đã dừng hoạt động !');
        }
    }
}
function show($query)
{
    $conn = connect();
    // Câu truy vấn

    // Thực hiện truy vấn
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Lấy kết quả
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return  $result;
}
function showTK($query, $params = array())
{
    try {
        $conn = connect();

        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception($e->getMessage());
    }
}

function option_khachsan()
{

    $conn = connect();
    // Truy vấn để lấy danh sách khách sạn
    $sql = "SELECT id, ten_khach_san FROM khachsan";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Hiển thị danh sách khách sạn trong dropdown menu
    while ($row = $stmt->fetch()) {
        echo "<option value='" . $row['id'] . "'>" . $row['ten_khach_san'] . "</option>";
    }
}
function option_goidichvu()
{

    $conn = connect();
    // Truy vấn để lấy danh sách khách sạn
    $sql = "SELECT id, ten_goi_dich_vu FROM goidichvu";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Hiển thị danh sách khách sạn trong dropdown menu
    while ($row = $stmt->fetch()) {
        echo "<option value='" . $row['id'] . "'>" . $row['ten_goi_dich_vu'] . "</option>";
    }
}
function option_hangphong()
{

    $conn = connect();
    // Truy vấn để lấy danh sách khách sạn
    $sql = "SELECT id, hang_phong FROM phong";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Hiển thị danh sách khách sạn trong dropdown menu
    while ($row = $stmt->fetch()) {
        echo "<option value='" . $row['id'] . "'>" . $row['ten_goi_dich_vu'] . "</option>";
    }
}
function option_uudai()
{

    $conn = connect();
    // Truy vấn để lấy danh sách ưu đãi còn trong thời gian đặt phòng
    $sql = "SELECT id, ten_uu_dai FROM uudai WHERE ngay_ket_thuc >= now()";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Hiển thị danh sách khách sạn trong dropdown menu
    while ($row = $stmt->fetch()) {
        echo "<option value='" . $row['id'] . "'>" . $row['ten_uu_dai'] . "</option>";
    }
}

function ten_khachsan($khachSanID)
{
    $conn = connect();
    // Truy vấn để lấy tên khách sạn
    $sql = "SELECT ten_khach_san FROM khachsan WHERE id = :khachSanID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':khachSanID', $khachSanID, PDO::PARAM_INT);
    $stmt->execute();

    // Lấy tên khách sạn
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row['ten_khach_san'];
    } else {
        return 'Không tìm thấy khách sạn';
    }
}
function insertPhong($khachSanID, $soPhong, $soNguoi, $gia, $hangPhong, $hinhAnh, $trangThai, $tenPhong, $dienTich, $loaiPhong)
{
    $conn = connect();

    // SQL statement for prepared statement
    // Check if the room number already exists
    $check_sql = "SELECT COUNT(*) FROM phong WHERE so_phong = :soPhong";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bindParam(':soPhong', $soPhong);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        throw new Exception("Số phòng đã tồn tại. Vui lòng chọn một số phòng khác.");
    } else {
        $sql = "INSERT INTO 
        phong (so_phong, ten_phong ,id_khach_san, gia, dien_tich, so_nguoi ,loai_phong, hang_phong, trang_thai, hinh_anh) 
        VALUES 
        (:soPhong, :tenPhong ,:khachSanID, :gia, :dienTich, :soNguoi, :loaiPhong, :hangPhong, :trangThai, :hinhAnh)
        ";
    }
    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':soPhong', $soPhong);
    $stmt->bindParam(':tenPhong', $tenPhong);
    $stmt->bindParam(':khachSanID', $khachSanID);
    $stmt->bindParam(':gia', $gia);
    $stmt->bindParam(':dienTich', $dienTich);
    $stmt->bindParam(':soNguoi', $soNguoi);
    $stmt->bindParam(':loaiPhong', $loaiPhong);
    $stmt->bindParam(':hangPhong', $hangPhong);
    $stmt->bindParam(':trangThai', $trangThai);
    $stmt->bindParam(':hinhAnh', $hinhAnh);

    $stmt->execute();
}
function deletePhong($id)
{
    try {
        $conn = connect();

        // Use a prepared statement to prevent SQL injection
        $sql = "DELETE FROM phong WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Xóa thành công!'
            );
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể xóa dữ liệu.',
                'errorInfo' => $errorInfo
            );
        }

        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }

    return $response;
}
//
function updatePhong($id, $khachSanID, $soPhong, $tenPhong, $gia, $hangPhong, $loaiPhong, $dienTich, $songuoi, $trangThai, $hinhAnh)
{
    try {
        $conn = connect();

        // Sử dụng prepare statement để tránh SQL injection
        $sql = "UPDATE phong SET so_phong = :soPhong, ten_phong =:tenPhong ,gia = :gia, 
                dien_tich = :dienTich, so_nguoi = :songuoi, loai_phong = :loaiPhong, hang_phong = :hangPhong,
               trang_thai = :trangThai ";

        // Nếu có hình ảnh mới được chọn, cập nhật hình ảnh
        if ($hinhAnh != null) {
            $sql .= ", hinh_anh = :hinhAnh ";
        }

        $sql .= "WHERE id= :id AND id_khach_san = :khachSanID";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':soPhong', $soPhong, PDO::PARAM_INT);
        $stmt->bindParam(':tenPhong', $tenPhong, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':khachSanID', $khachSanID, PDO::PARAM_INT);
        $stmt->bindParam(':gia', $gia, PDO::PARAM_STR);
        $stmt->bindParam(':dienTich', $dienTich, PDO::PARAM_STR);
        $stmt->bindParam(':songuoi', $songuoi, PDO::PARAM_INT);
        $stmt->bindParam(':loaiPhong', $loaiPhong, PDO::PARAM_INT);
        $stmt->bindParam(':hangPhong', $hangPhong, PDO::PARAM_INT);
        $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_INT);

        // Nếu có hình ảnh mới được chọn, gắn giá trị vào prepare statement
        if ($hinhAnh != null) {
            $stmt->bindParam(':hinhAnh', $hinhAnh, PDO::PARAM_STR);
            if ($stmt->execute())
                $response = array(
                    'status' => 'success',
                    'message' => 'Cập nhật thành công'
                );
        }

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cập nhật thành công'
            );
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể cập nhật dữ liệu.',
                'errorInfo' => $errorInfo
            );
        }

        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }
    return $response;
}
// ưu đãi
function insertUuDai($id, $giaGiam, $tenUuDai, $moTa, $ngayBatDau, $ngayKetThuc)
{
    $conn = connect();
    // SQL statement for prepared statement
    $sql = "INSERT INTO uudai (id, ten_uu_dai, mo_ta, gia_giam, ngay_bat_dau, ngay_ket_thuc) 
            VALUES (:id, :tenUuDai, :moTa, :giaGiam, :ngayBatDau, :ngayKetThuc)";

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':tenUuDai', $tenUuDai);
    $stmt->bindParam(':moTa', $moTa);
    $stmt->bindParam(':giaGiam', $giaGiam);
    $stmt->bindParam(':ngayBatDau', $ngayBatDau);
    $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);

    $stmt->execute();
}

function deleteUuDai($id)
{
    if ($id) {
        try {
            $conn = connect();

            // Sử dụng prepare statement để tránh SQL injection
            $sql = "DELETE FROM uudai WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Xóa thành công!'
                );
            } else {
                $errorInfo = $stmt->errorInfo();
                $response = array(
                    'status' => 'error',
                    'message' => 'Không thể xóa dữ liệu.',
                    'errorInfo' => $errorInfo
                );
            }

            $stmt->closeCursor();
            $conn = null;
        } catch (PDOException $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Lỗi xảy ra: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Dữ liệu không hợp lệ.'
        );
    }
    return $response;
}

function updateUuDai($id, $giaGiam, $tenUuDai, $moTa, $ngayBatDau, $ngayKetThuc)
{
    $conn = connect();

    // SQL statement for prepared statement
    $sql = "UPDATE uudai 
                SET ten_uu_dai = :tenUuDai, mo_ta = :moTa, gia_giam = :giaGiam, ngay_bat_dau = :ngayBatDau, ngay_ket_thuc = :ngayKetThuc 
                WHERE id = :id";

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':tenUuDai', $tenUuDai);
    $stmt->bindParam(':moTa', $moTa);
    $stmt->bindParam(':giaGiam', $giaGiam);
    $stmt->bindParam(':ngayBatDau', $ngayBatDau);
    $stmt->bindParam(':ngayKetThuc', $ngayKetThuc);

    if ($stmt->execute()) {
        $response = array(
            'status' => 'success',
            'message' => 'Cập nhật thành công'
        );
    } else {
        $errorInfo = $stmt->errorInfo();
        $response = array(
            'status' => 'error',
            'message' => 'Không thể cập nhật dữ liệu.',
            'errorInfo' => $errorInfo
        );
    }

    $stmt->closeCursor();
    $conn = null;
    return $response;
}
// phiếu đặt phòng
function insertPhongDat(
    $id,
    $soDT,
    $email,
    $khachHangID,
    $soPhong,
    $tenGoiDichVu,
    $soNguoi,
    $tenUuDai,
    $ngayNhanPhong,
    $ngayTraPhong,
    $trangThai
) {
    $conn = connect();

    // Lấy ID khách hàng từ cơ sở dữ liệu
    $query = "SELECT id FROM khachhang WHERE sdt = ? AND email = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($soDT, $email));
    $khachHang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$khachHang) {
        throw new Exception("Khách hàng không tồn tại.");
    }
    $khachHangID = $khachHang['id'];

    // Bắt đầu một giao dịch PDO
    $conn->beginTransaction();
    $transactionActive = true; // Biến để theo dõi trạng thái giao dịch
    // Tính toán giá trị hóa đơn
    // Lấy giá của phòng
    $query = "SELECT gia FROM phong WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($soPhong));
    $giaPhong = $stmt->fetchColumn();

    // Lấy giá của gói dịch vụ
    $query = "SELECT gia FROM goidichvu WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($tenGoiDichVu));
    $giaGoiDichVu = $stmt->fetchColumn();

    // Lấy giảm giá từ ưu đãi (nếu có)
    $giamGia = 0;
    if (!empty($tenUuDai)) {
        $query = "SELECT gia_giam
        FROM uudai u join phongdat p 
        ON u.id = p.id_uu_dai
        WHERE id = ? and ngay_ket_thuc < ngay_dat_phong";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($tenUuDai));
        $giamGia = $stmt->fetchColumn();
    }
    // Tính số ngày đặt phòng
    $date1 = new DateTime($ngayNhanPhong);
    $date2 = new DateTime($ngayTraPhong);
    $soNgay = $date2->diff($date1)->days;

    // Tính tổng tiền
    $tongTien = ($giaPhong + $giaGoiDichVu) * (100 - $giamGia) / 100 * $soNgay;

    // Thêm phiếu đặt phòng vào cơ sở dữ liệu
    $query = "INSERT INTO phongdat (id_phong, id_uu_dai, id_goi_dich_vu, id_khach_hang, so_nguoi, tong_tien, ngay_dat_phong, ngay_nhan_phong, trang_thai, ngay_tra_phong) 
              VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($soPhong, $tenUuDai, $tenGoiDichVu, $khachHangID, $soNguoi, $tongTien, $ngayNhanPhong, $trangThai, $ngayTraPhong));
    $phieuDatPhongID = $conn->lastInsertId();

    // Commit giao dịch
    $conn->commit();
    $transactionActive = false; // Đặt lại biến theo dõi trạng thái giao dịch
}

function updatePhongDat() {}

//user admin
function insertAdmin($admin_name, $admin_pass, $trangThai)
{
    $conn = connect();

    // SQL statement for prepared statement
    $sql = "INSERT INTO admin (admin_name, admin_pass, trang_thai) VALUES 
        (:admin_name, :admin_pass, :trangThai)";

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':admin_name', $admin_name);
    $stmt->bindParam(':admin_pass', $admin_pass);
    $stmt->bindParam(':trangThai', $trangThai);

    $stmt->execute();
}

function deleteAdmin($id)
{
    try {
        $conn = connect();

        // Sử dụng prepare statement để tránh SQL injection
        $sql = "DELETE FROM admin WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Xóa thành công!'
            );
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể xóa dữ liệu.',
                'errorInfo' => $errorInfo
            );
        }

        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }
    return $response;
}

function updateAdmin($id, $admin_name, $admin_pass, $trangThai)
{
    try {

        $conn = connect();

        // Sử dụng prepare statement để tránh SQL injection
        $sql = "UPDATE admin SET admin_name = :admin_name, admin_pass = :admin_pass, trang_thai = :trangThai WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':admin_name', $admin_name, PDO::PARAM_STR);
        $stmt->bindParam(':admin_pass', $admin_pass, PDO::PARAM_STR);
        $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cập nhật thành công'
            );
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể cập nhật dữ liệu.',
                'errorInfo' => $errorInfo
            );
        }

        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }
    return $response;
}

function insertDV($tenDV, $loaiDV, $moTa, $gioiHan, $hinhAnh, $gia)
{
    $conn = connect();

    // SQL statement for prepared statement
    // Check if the room number already exists
    $check_sql = "SELECT COUNT(*) FROM dichvu WHERE ten_dich_vu = :tenDV AND loai_dich_vu = :loaiDV";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bindParam(':tenDV', $tenDV);
    $stmt_check->bindParam(':loaiDV', $loaiDV);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        throw new Exception("Dịch vụ đã tồn tại. Vui lòng nhập tên dịch vụ khác.");
    } else {
        $sql = "INSERT INTO 
        dichvu (ten_dich_vu, loai_dich_vu, gioi_han, mo_ta, don_gia, hinh_anh) 
        VALUES 
        (:tenDV, :loaiDV, :gioiHan, :moTa, :gia, :hinhAnh)
        ";
    }
    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tenDV', $tenDV);
    $stmt->bindParam(':loaiDV', $loaiDV);
    $stmt->bindParam(':gioiHan', $gioiHan);
    $stmt->bindParam(':gia', $gia);
    $stmt->bindParam(':moTa', $moTa);
    $stmt->bindParam(':hinhAnh', $hinhAnh);
    $stmt->execute();
}
function deleteDV($id)
{
    try {
        $conn = connect();

        // Use a prepared statement to prevent SQL injection
        $sql = "DELETE FROM dichvu WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Xóa thành công!'
            );
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể xóa dữ liệu.',
                'errorInfo' => $errorInfo
            );
        }

        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }

    return $response;
}
function updateDV($id, $tenDV, $loaiDV, $moTa, $gioiHan, $hinhAnh, $gia)
{
    try {
        $conn = connect();

        // Khởi tạo câu lệnh SQL cơ bản
        $sql = "UPDATE dichvu SET 
                ten_dich_vu = :tenDV, 
                loai_dich_vu = :loaiDV, 
                mo_ta = :moTa, 
                don_gia = :gia, 
                gioi_han = :gioiHan";

        // Nếu có hình ảnh mới được chọn, thêm vào câu lệnh SQL
        if ($hinhAnh != null) {
            $sql .= ", hinh_anh = :hinhAnh";
        }

        $sql .= " WHERE id = :id";

        // Chuẩn bị câu lệnh SQL
        $stmt = $conn->prepare($sql);

        // Gắn giá trị cho các tham số
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':tenDV', $tenDV, PDO::PARAM_STR);
        $stmt->bindParam(':loaiDV', $loaiDV, PDO::PARAM_STR);
        $stmt->bindParam(':moTa', $moTa, PDO::PARAM_STR);
        $stmt->bindParam(':gia', $gia, PDO::PARAM_INT);
        $stmt->bindParam(':gioiHan', $gioiHan, PDO::PARAM_INT);

        // Nếu có hình ảnh mới được chọn, gắn giá trị cho tham số hình ảnh
        if ($hinhAnh != null) {
            $stmt->bindParam(':hinhAnh', $hinhAnh, PDO::PARAM_STR);
        }

        // Thực thi câu lệnh cập nhật dịch vụ
        if ($stmt->execute()) {
            // Kiểm tra xem dịch vụ có thuộc gói nào không
            $sqlCheckGoi = "SELECT id_goi_dich_vu FROM dichvu WHERE id = :id";
            $stmtCheckGoi = $conn->prepare($sqlCheckGoi);
            $stmtCheckGoi->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCheckGoi->execute();
            $id_goi_dich_vu = $stmtCheckGoi->fetchColumn();

            // Nếu dịch vụ thuộc một gói, tính lại giá của gói
            if ($id_goi_dich_vu) {
                $sqlGetGia = "SELECT SUM(don_gia) AS total FROM dichvu WHERE id_goi_dich_vu = :id_goi_dich_vu";
                $stmtGetGia = $conn->prepare($sqlGetGia);
                $stmtGetGia->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
                $stmtGetGia->execute();
                $totalGia = $stmtGetGia->fetchColumn();

                if ($totalGia !== false) {
                    // Giảm 10% tổng giá
                    $giaMoi = $totalGia * 0.9;

                    // Cập nhật giá gói dịch vụ
                    $sqlUpdateGoi = "UPDATE goidichvu SET gia = :giaMoi WHERE id = :id_goi_dich_vu";
                    $stmtUpdateGoi = $conn->prepare($sqlUpdateGoi);
                    $stmtUpdateGoi->bindParam(':giaMoi', $giaMoi, PDO::PARAM_STR);
                    $stmtUpdateGoi->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
                    $stmtUpdateGoi->execute();
                }
            }

            $response = array(
                'status' => 'success',
                'message' => 'Cập nhật dịch vụ và giá gói dịch vụ thành công.'
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Không thể cập nhật dữ liệu.',
                'errorInfo' => $stmt->errorInfo()
            );
        }

        // Đóng kết nối
        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }

    return $response;
}

// tìm vé máy bay
function search_vemaybay($sdt = null, $ngayBatDau = null, $ngayKetThuc = null)
{
    // Kết nối đến cơ sở dữ liệu
    $conn = connect();

    // Xây dựng truy vấn SQL cơ bản
    $sql = "SELECT 
                v.id AS id_ve,
                v.ngay_dat_ve AS ngay_dat_ve,
                v.trang_thai AS trang_thai,
                k.sdt AS so_khachhang,
                g.so_ghe AS so_ghe,
                k.id AS id_kh,
                k.ho AS ho,
                k.ten AS ten,
                k.email AS email,
                k.dia_chi AS dia_chi,
                nb.id AS id_nb,
                nb.ho AS ho_nb,
                nb.ten AS ten_nb,
                nb.ngay_sinh AS ngay_sinh,
                nb.gioi_tinh AS gioi_tinh,
                nb.cccd AS cccd,
                nb.sdt AS sdt_nb,
                nb.quoc_tich AS quoc_tich
            FROM 
                vemaybay v 
            JOIN khachhang k ON v.id_khach_hang = k.id
            JOIN ghe_chuyenbay gcb ON v.id_ghe_chuyenbay = gcb.id
            JOIN ghe g ON g.id = gcb.id_ghe
            JOIN thongtinnguoibay nb ON nb.id = v.id_nguoi_bay
            WHERE 1=1"; // "1=1" để dễ dàng thêm điều kiện

    // Khởi tạo mảng tham số
    $params = [];

    // Thêm điều kiện tìm kiếm nếu có
    if (!empty($sdt)) {
        $sql .= " AND k.sdt LIKE :sdt";
        $params[':sdt'] = '%' . $sdt . '%';
    }
    if (!empty($ngayBatDau)) {
        $sql .= " AND v.ngay_dat_ve >= :ngayBatDau";
        $params[':ngayBatDau'] = $ngayBatDau;
    }
    if (!empty($ngayKetThuc)) {
        $sql .= " AND v.ngay_dat_ve <= :ngayKetThuc";
        $params[':ngayKetThuc'] = $ngayKetThuc;
    }

    try {
        // Chuẩn bị truy vấn
        $stmt = $conn->prepare($sql);

        // Gán giá trị cho các tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        // Thực thi truy vấn
        $stmt->execute();

        // Lấy dữ liệu
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kiểm tra nếu có dữ liệu
        if (count($results) > 0) {
            // Hiển thị dữ liệu trong bảng
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id_ve']) . '</td>';
                echo '<td>' . htmlspecialchars($row['so_khachhang']) . '</td>';
                echo '<td>' . htmlspecialchars($row['so_ghe']) . '</td>';
                echo '<td>' . htmlspecialchars($row['ngay_dat_ve']) . '</td>';
                echo '<td>' . ($row['trang_thai'] == 0 ? 'Chưa check-in' : 'Đã check-in') . '</td>';
                echo '<td>';
                echo '<button class="btn btn-success btn-sm chiTietKH" 
                          data-id="' . htmlspecialchars($row['id_kh']) . '">Chi tiết người đặt</button>';
                echo " ";
                echo '<button class="btn btn-warning btn-sm chiTietNB" 
                          data-id="' . htmlspecialchars($row['id_nb']) . '">Chi tiết người bay</button>';

                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6">Không tìm thấy vé máy bay nào phù hợp.</td></tr>';
        }
    } catch (PDOException $e) {
        // Hiển thị lỗi nếu có
        echo '<tr><td colspan="6">Lỗi truy vấn: ' . $e->getMessage() . '</td></tr>';
    } finally {
        // Đóng kết nối
        $conn = null;
    }
}
// tìm kiếm dịch vụ trong gói dịch vụ
function searchDV($search)
{
    // Chuẩn bị câu truy vấn SQL
    $conn = connect();

    if (!empty($search)) {
        $sql = "SELECT * FROM dichvu WHERE ten_dich_vu LIKE :search AND id_goi_dich_vu IS NULL";
        $stmt = $conn->prepare($sql);
        $search_param = "%" . $search . "%";
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
    } else {
        // Nếu không có từ khóa tìm kiếm, lấy tất cả dịch vụ
        $sql = "SELECT * FROM dichvu WHERE id_goi_dich_vu IS NULL";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về kết quả dưới dạng HTML
    if (count($services) > 0) {
        foreach ($services as $pdv) {
            echo '<tr>';
            echo '<th scope="row"><input class="form-check-input service-checkbox" type="checkbox" data-id="' . $pdv['id'] . '"></th>';
            echo '<td>' . $pdv['id'] . '</td>';
            echo '<td>' . $pdv['ten_dich_vu'] . '</td>';
            echo '<td>' . number_format($pdv['don_gia'], 0, '.', '.') . '</td>';

            echo '<td>';
            if ($pdv['loai_dich_vu'] == 'HoBoi') {
                echo 'Hồ Bơi';
            } elseif ($pdv['loai_dich_vu'] == 'NhaHang') {
                echo 'Nhà Hàng';
            } elseif ($pdv['loai_dich_vu'] == 'Spa') {
                echo 'Spa';
            } elseif ($pdv['loai_dich_vu'] == 'Gym') {
                echo 'Gym';
            } elseif ($pdv['loai_dich_vu'] == 'Golf') {
                echo 'Golf';
            }
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">Không tìm thấy dịch vụ phù hợp.</td></tr>';
    }

    $conn = null;
}
function searchDVDat($search, $loai_dich_vu = null)
{
    // Chuẩn bị câu truy vấn SQL
    $conn = connect();

    // Xây dựng câu lệnh SQL với điều kiện tìm kiếm tên dịch vụ
    $sql = "SELECT * FROM dichvu WHERE ten_dich_vu LIKE :search";

    // Nếu có loại dịch vụ được chọn, thêm điều kiện tìm kiếm cho loại dịch vụ
    if (!empty($loai_dich_vu)) {
        $sql .= " AND loai_dich_vu = :loai_dich_vu";
    }

    // Chuẩn bị câu truy vấn
    $stmt = $conn->prepare($sql);

    // Gán tham số tìm kiếm cho tên dịch vụ
    $search_param = "%" . $search . "%";
    $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);

    // Nếu có loại dịch vụ được chọn, gán tham số cho loại dịch vụ
    if (!empty($loai_dich_vu)) {
        $stmt->bindParam(':loai_dich_vu', $loai_dich_vu, PDO::PARAM_STR);
    }

    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về kết quả dưới dạng HTML
    if (count($services) > 0) {
        foreach ($services as $pdv) {
            echo '<tr>';
            echo '<th scope="row"><input class="form-check-input service-checkbox" type="checkbox" data-id="' . $pdv['id'] . '"></th>';
            echo '<td>' . $pdv['id'] . '</td>';
            echo '<td>' . $pdv['ten_dich_vu'] . '</td>';
            echo '<td>' . number_format($pdv['don_gia'], 0, '.', '.') . '</td>';
            echo '<td>' . $pdv['gioi_han'] . '</td>';
            echo '<td>';
            if ($pdv['loai_dich_vu'] == 'HoBoi') {
                echo 'Hồ Bơi';
            } elseif ($pdv['loai_dich_vu'] == 'NhaHang') {
                echo 'Nhà Hàng';
            } elseif ($pdv['loai_dich_vu'] == 'Spa') {
                echo 'Spa';
            } elseif ($pdv['loai_dich_vu'] == 'Gym') {
                echo 'Gym';
            } elseif ($pdv['loai_dich_vu'] == 'Golf') {
                echo 'Golf';
            }
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">Không tìm thấy dịch vụ phù hợp.</td></tr>';
    }

    $conn = null;
}


function searchNV($searchTen, $phongBan)
{
    // Chuẩn bị câu truy vấn SQL
    $conn = connect();
    try {
        // if (!empty($search)) {
        //     $sql = "SELECT * FROM nhanvien WHERE ho_ten LIKE :search ";
        //     $stmt = $conn->prepare($sql);
        //     $search_param = "%" . $search . "%";
        //     $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        // Xây dựng câu SQL với điều kiện tìm kiếm
        $sql = "SELECT * FROM nhanvien WHERE 1=1";
        if (!empty($searchTen)) {
            $sql .= " AND ho_ten LIKE :searchTen";
        }
        if ($phongBan != 0) {
            $sql .= " AND phong_ban = :phongBan";
        } else if ($phongBan == 0 && empty($searchTen)) {
            // Nếu không có từ khóa tìm kiếm, lấy tất cả dịch vụ
            $sql = "SELECT * FROM nhanvien ";
            $stmt = $conn->prepare($sql);
        }
        $stmt = $conn->prepare($sql);

        // Gắn tham số tìm kiếm nếu có
        if (!empty($searchTen)) {
            $searchTen = '%' . $searchTen . '%';
            $stmt->bindParam(':searchTen', $searchTen, PDO::PARAM_STR);
        }
        if (!empty($phongBan)) {
            $stmt->bindParam(':phongBan', $phongBan, PDO::PARAM_STR);
        }
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Trả về kết quả dưới dạng HTML
        if (count($services) > 0) {
            foreach ($services as $pdv) {
                echo '<tr>';
                echo '<td>' . $pdv['id'] . '</td>';
                echo '<td>' . $pdv['ho_ten'] . '</td>';

                echo '<td>';
                if ($pdv['phong_ban'] == 'AD') {
                    echo 'Admin';
                } else if ($pdv['phong_ban'] == 'LT') {
                    echo 'Lễ Tân';
                } else if ($pdv['phong_ban'] == 'DV-Spa') {
                    echo 'Dịch vụ Spa';
                } else if ($pdv['phong_ban'] == 'DV-NhaHang') {
                    echo 'Dịch vụ Nhà hàng';
                } else if ($pdv['phong_ban'] == 'DV-Gym') {
                    echo 'Dịch vụ Gym';
                } else if ($pdv['phong_ban'] == 'DV-Golf') {
                    echo 'Dịch vụ Golf';
                } else if ($pdv['phong_ban'] == 'DV-HoBoi') {
                    echo 'Dịch vụ Hồ bơi';
                }
                echo '</td>';
                echo '<td>' . $pdv['dia_chi'] . '</td>';
                echo '<td>' . $pdv['id_admin'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5" class="text-center text-danger">Không tìm thấy nhân viên.</td></tr>';
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='5'>Lỗi cơ sở dữ liệu: {$e->getMessage()}</td></tr>";
    }
    $conn = null;
}
