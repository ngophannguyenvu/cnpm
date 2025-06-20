<style>
.dg-container {
    max-width: 1000px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.dg-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.dg-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.dg-table th, .dg-table td {
    padding: 12px 16px;
    text-align: center;
}
.dg-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.dg-table tr:nth-child(even) {
    background: #ffe4ec;
}
.dg-table tr:hover {
    background: #ffd1e6;
}
.dg-btn {
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
.dg-btn:hover {
    background: #e73370;
}
.dg-btn.dg-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .dg-container { padding: 10px; }
    .dg-title { font-size: 1.2rem; }
    .dg-table th, .dg-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="dg-container">
    <div class="dg-title">Quản lý Đánh giá</div>
    <div id="dg-table-wrap">
        <table class="dg-table">
            <thead>
                <tr>
                    <th>Số sao</th>
                    <th>Nhận xét</th>
                    <th>Ngày đánh giá</th>
                    <th>Mã người dùng</th>
                    <th>Mã hóa đơn</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="dg-tbody">
                <tr><td colspan="6">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="dg-btn dg-add" id="dg-btn-add">+ Thêm mới</button>
    <div id="dg-content"></div>
</div>
<script>
let _dgData = null;
const dgTbody = document.getElementById('dg-tbody');
const dgContent = document.getElementById('dg-content');
const dgTableWrap = document.getElementById('dg-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        dgTbody.innerHTML = '<tr><td colspan="6">Không có dữ liệu</td></tr>';
        return;
    }
    dgTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.Danhgiasao}</td>
            <td>${item.Nhanxet}</td>
            <td>${item.Ngaydanhgia || ''}</td>
            <td>${item.Manguoidung}</td>
            <td>${item.MaHD}</td>
            <td>
                <button class="dg-btn dg-detail" data-madg="${item.MaDG}">Chi tiết</button>
                <button class="dg-btn dg-edit" data-madg="${item.MaDG}">Sửa</button>
                <button class="dg-btn dg-delete" data-madg="${item.MaDG}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchDanhGia() {
    if (!_dgData) {
        dgTbody.innerHTML = '<tr><td colspan="6">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_dgData);
    }
    fetch("http://localhost:86/cnpm-BE/api/danhgia")
        .then(res => res.json())
        .then(data => {
            _dgData = data;
            renderRows(data);
        })
        .catch(() => {
            dgTbody.innerHTML = '<tr><td colspan="6">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchDanhGia();

document.getElementById('dg-btn-add').onclick = () => loadDGView('add');

function loadDGView(view, madg = '') {
    fetch(`views/danhgia/${view}.php`)
        .then(res => res.text())
        .then(html => {
            dgContent.innerHTML = html;
            dgTableWrap.style.display = 'none';
            dgContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && madg) {
                document.querySelectorAll('[name="madg"]').forEach(e => e.value = madg);
                if (view === 'detail') {
                    document.getElementById('dg-madg').textContent = madg;
                }
            }
        });
}
function backToMain() {
    dgContent.innerHTML = '';
    dgTableWrap.style.display = '';
    renderRows(_dgData || []);
}
dgContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('dg-back')) backToMain();
});
dgTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('dg-detail')) {
        loadDGView('detail', e.target.dataset.madg);
    } else if (e.target.classList.contains('dg-edit')) {
        loadDGView('edit', e.target.dataset.madg);
    } else if (e.target.classList.contains('dg-delete')) {
        loadDGView('delete', e.target.dataset.madg);
    }
});
</script> 