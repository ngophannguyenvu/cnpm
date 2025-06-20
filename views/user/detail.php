<h3>Chi tiết người dùng</h3>
<ul>
    <li><b>Mã người dùng:</b> ND001</li>
    <li><b>Tên người dùng:</b> Nguyễn Văn A</li>
    <li><b>Email:</b> a@gmail.com</li>
</ul>
<button>Quay lại</button>

<script>
if (typeof _userData !== 'undefined') {
    const manguoidung = document.querySelector('[name="manguoidung"]') ? document.querySelector('[name="manguoidung"]').value : (typeof user_manguoidung !== 'undefined' ? user_manguoidung : '');
    const detailBox = document.querySelector('.user-detail-box');
    const loadingDiv = document.createElement('div');
    loadingDiv.textContent = 'Đang tải dữ liệu...';
    loadingDiv.style.textAlign = 'center';
    detailBox.appendChild(loadingDiv);
    fetch('http://localhost:86/cnpm-BE/api/user/getUserById', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ manguoidung })
    })
    .then(res => res.json())
    .then(user => {
        loadingDiv.remove();
        if (user) {
            document.getElementById('user-manguoidung').textContent = user.Manguoidung || '';
            document.getElementById('user-hoten').textContent = user.Hoten || '';
            document.getElementById('user-sdt').textContent = user.SDT || '';
            document.getElementById('user-diachi').textContent = user.DiaChi || '';
            document.getElementById('user-email').textContent = user.Email || '';
            document.getElementById('user-ngaysinh').textContent = user.Ngaysinh || '';
            document.getElementById('user-gioitinh').textContent = user.Gioitinh || '';
        }
    });
}
</script> 