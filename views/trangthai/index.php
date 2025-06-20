<style>
.tt-container {
    max-width: 700px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.tt-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.tt-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.tt-table th, .tt-table td {
    padding: 12px 16px;
    text-align: center;
}
.tt-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.tt-table tr:nth-child(even) {
    background: #ffe4ec;
}
.tt-table tr:hover {
    background: #ffd1e6;
}
.tt-btn {
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
.tt-btn:hover {
    background: #e73370;
}
.tt-btn.tt-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .tt-container { padding: 10px; }
    .tt-title { font-size: 1.2rem; }
    .tt-table th, .tt-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="tt-container">
    <div class="tt-title">Quản lý Trạng thái</div>
    <div id="tt-table-wrap">
        <table class="tt-table">
            <thead>
                <tr>
                    <th>Mã trạng thái</th>
                    <th>Tên trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="tt-tbody">
                <tr><td colspan="3">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="tt-btn tt-add" id="tt-btn-add">+ Thêm mới</button>
    <div id="tt-content"></div>
</div>
<script>
let _ttData = null;
const ttTbody = document.getElementById('tt-tbody');
const ttContent = document.getElementById('tt-content');
const ttTableWrap = document.getElementById('tt-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        ttTbody.innerHTML = '<tr><td colspan="3">Không có dữ liệu</td></tr>';
        return;
    }
    ttTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.Matrangthai ?? ''}</td>
            <td>${item.Tentrangthai ?? ''}</td>
            <td>
                <button class="tt-btn tt-detail" data-matt="${item.Matrangthai}">Chi tiết</button>
                <button class="tt-btn tt-edit" data-matt="${item.Matrangthai}">Sửa</button>
                <button class="tt-btn tt-delete" data-matt="${item.Matrangthai}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchTrangThai() {
    if (!_ttData) {
        ttTbody.innerHTML = '<tr><td colspan="3">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_ttData);
    }
    fetch("http://localhost:86/cnpm-BE/api/trangthai")
        .then(res => res.json())
        .then(data => {
            _ttData = data;
            renderRows(data);
        })
        .catch(() => {
            ttTbody.innerHTML = '<tr><td colspan="3">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchTrangThai();

document.getElementById('tt-btn-add').onclick = () => loadTTView('add');

function loadTTView(view, matt = '') {
    fetch(`views/trangthai/${view}.php`)
        .then(res => res.text())
        .then(html => {
            ttContent.innerHTML = html;
            ttTableWrap.style.display = 'none';
            ttContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && matt) {
                document.querySelectorAll('[name="matt"]').forEach(e => e.value = matt);
                if (view === 'detail') {
                    document.getElementById('tt-matt').textContent = matt;
                }
            }
        });
}
function backToMain() {
    ttContent.innerHTML = '';
    ttTableWrap.style.display = '';
    renderRows(_ttData || []);
}
ttContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('tt-back')) backToMain();
});
ttTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('tt-detail')) {
        loadTTView('detail', e.target.dataset.matt);
    } else if (e.target.classList.contains('tt-edit')) {
        loadTTView('edit', e.target.dataset.matt);
    } else if (e.target.classList.contains('tt-delete')) {
        loadTTView('delete', e.target.dataset.matt);
    }
});
</script> 