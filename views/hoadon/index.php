<style>
.hd-container {
    max-width: 1000px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.hd-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.hd-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.hd-table th, .hd-table td {
    padding: 12px 16px;
    text-align: center;
}
.hd-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.hd-table tr:nth-child(even) {
    background: #ffe4ec;
}
.hd-table tr:hover {
    background: #ffd1e6;
}
.hd-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    margin: 0 2px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.hd-btn:hover {
    background: #e73370;
}
.hd-btn.hd-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .hd-container { padding: 10px; }
    .hd-title { font-size: 1.2rem; }
    .hd-table th, .hd-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="hd-container">
    <div class="hd-title">Quản lý Hóa đơn & Thanh toán</div>
    <div id="hd-table-wrap">
        <table class="hd-table">
            <thead>
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Ngày lập</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="hd-tbody">
                <tr><td colspan="5">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="hd-btn hd-add" id="hd-btn-add">+ Thêm mới</button>
    <div id="hd-content"></div>
</div>
<script>
let _hdData = null;
const hdTbody = document.getElementById('hd-tbody');
const hdContent = document.getElementById('hd-content');
const hdTableWrap = document.getElementById('hd-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        hdTbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        return;
    }
    hdTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MaHD}</td>
            <td>${item.NgayThanhToan || ''}</td>
            <td>${item.Tongtien ? item.Tongtien.toLocaleString('vi-VN') + 'đ' : ''}</td>
            <td>${item.Matrangthai || ''}</td>
            <td>
                <button class="hd-btn hd-detail" data-mahd="${item.MaHD}">Chi tiết</button>
                <button class="hd-btn hd-edit" data-mahd="${item.MaHD}">Sửa</button>
                <button class="hd-btn hd-delete" data-mahd="${item.MaHD}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchHoaDon() {
    if (!_hdData) {
        hdTbody.innerHTML = '<tr><td colspan="5">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_hdData);
    }
    fetch("http://localhost:86/cnpm-BE/api/hoadonvathanhtoan")
        .then(res => res.json())
        .then(data => {
            _hdData = data;
            renderRows(data);
        })
        .catch(() => {
            hdTbody.innerHTML = '<tr><td colspan="5">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchHoaDon();

document.getElementById('hd-btn-add').onclick = () => loadHDView('add');

function loadHDView(view, mahd = '') {
    fetch(`views/hoadon/${view}.php`)
        .then(res => res.text())
        .then(html => {
            hdContent.innerHTML = html;
            hdTableWrap.style.display = 'none';
            hdContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && mahd) {
                document.querySelectorAll('[name="mahd"]').forEach(e => e.value = mahd);
                if (view === 'detail') {
                    document.getElementById('hd-mahd').textContent = mahd;
                }
            }
        });
}
function backToMain() {
    hdContent.innerHTML = '';
    hdTableWrap.style.display = '';
    renderRows(_hdData || []);
}
hdContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('hd-back')) backToMain();
});
hdTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('hd-detail')) {
        loadHDView('detail', e.target.dataset.mahd);
    } else if (e.target.classList.contains('hd-edit')) {
        loadHDView('edit', e.target.dataset.mahd);
    } else if (e.target.classList.contains('hd-delete')) {
        loadHDView('delete', e.target.dataset.mahd);
    }
});
</script> 