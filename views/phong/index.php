<style>
.phong-container {
    max-width: 1000px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.phong-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.phong-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.phong-table th, .phong-table td {
    padding: 12px 16px;
    text-align: center;
}
.phong-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.phong-table tr:nth-child(even) {
    background: #ffe4ec;
}
.phong-table tr:hover {
    background: #ffd1e6;
}
.phong-btn {
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
.phong-btn:hover {
    background: #e73370;
}
.phong-btn.phong-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .phong-container { padding: 10px; }
    .phong-title { font-size: 1.2rem; }
    .phong-table th, .phong-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="phong-container">
    <div class="phong-title">Quản lý Phòng</div>
    <div id="phong-table-wrap">
        <table class="phong-table">
            <thead>
                <tr>
                    <th>Mã phòng</th>
                    <th>Tên phòng</th>
                    <th>Loại phòng</th>
                    <th>Mã trạng thái phòng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="phong-tbody">
                <tr><td colspan="5">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="phong-btn phong-add" id="phong-btn-add">+ Thêm mới</button>
    <div id="phong-content"></div>
</div>
<script>
let _phongData = null;
const phongTbody = document.getElementById('phong-tbody');
const phongContent = document.getElementById('phong-content');
const phongTableWrap = document.getElementById('phong-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        phongTbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        return;
    }
    phongTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.Maphong ?? ''}</td>
            <td>${item.Tenphong ?? ''}</td>
            <td>${item.Loaiphong ?? ''}</td>
            <td>${item.MatrangthaiP ?? ''}</td>
            <td>
                <button class="phong-btn phong-detail" data-maphong="${item.Maphong}">Chi tiết</button>
                <button class="phong-btn phong-edit" data-maphong="${item.Maphong}">Sửa</button>
                <button class="phong-btn phong-delete" data-maphong="${item.Maphong}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchPhong() {
    if (!_phongData) {
        phongTbody.innerHTML = '<tr><td colspan="5">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_phongData);
    }
    fetch("http://localhost:86/cnpm-BE/api/phong")
        .then(res => res.json())
        .then(data => {
            _phongData = data;
            renderRows(data);
        })
        .catch(() => {
            phongTbody.innerHTML = '<tr><td colspan="5">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchPhong();

document.getElementById('phong-btn-add').onclick = () => loadPhongView('add');

function loadPhongView(view, maphong = '') {
    fetch(`views/phong/${view}.php`)
        .then(res => res.text())
        .then(html => {
            phongContent.innerHTML = html;
            phongTableWrap.style.display = 'none';
            phongContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && maphong) {
                document.querySelectorAll('[name="maphong"]').forEach(e => e.value = maphong);
                if (view === 'detail') {
                    document.getElementById('phong-maphong').textContent = maphong;
                }
            }
        });
}
function backToMain() {
    phongContent.innerHTML = '';
    phongTableWrap.style.display = '';
    renderRows(_phongData || []);
}
phongContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('phong-back')) backToMain();
});
phongTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('phong-detail')) {
        loadPhongView('detail', e.target.dataset.maphong);
    } else if (e.target.classList.contains('phong-edit')) {
        loadPhongView('edit', e.target.dataset.maphong);
    } else if (e.target.classList.contains('phong-delete')) {
        loadPhongView('delete', e.target.dataset.maphong);
    }
});
</script> 