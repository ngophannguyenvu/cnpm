<style>
.ttp-container {
    max-width: 700px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.ttp-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.ttp-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.ttp-table th, .ttp-table td {
    padding: 12px 16px;
    text-align: center;
}
.ttp-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.ttp-table tr:nth-child(even) {
    background: #ffe4ec;
}
.ttp-table tr:hover {
    background: #ffd1e6;
}
.ttp-btn {
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
.ttp-btn:hover {
    background: #e73370;
}
.ttp-btn.ttp-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .ttp-container { padding: 10px; }
    .ttp-title { font-size: 1.2rem; }
    .ttp-table th, .ttp-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="ttp-container">
    <div class="ttp-title">Quản lý Trạng thái Phòng</div>
    <div id="ttp-table-wrap">
        <table class="ttp-table">
            <thead>
                <tr>
                    <th>Mã trạng thái phòng</th>
                    <th>Tên trạng thái phòng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="ttp-tbody">
                <tr><td colspan="3">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="ttp-btn ttp-add" id="ttp-btn-add">+ Thêm mới</button>
    <div id="ttp-content"></div>
</div>
<script>
let _ttpData = null;
const ttpTbody = document.getElementById('ttp-tbody');
const ttpContent = document.getElementById('ttp-content');
const ttpTableWrap = document.getElementById('ttp-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        ttpTbody.innerHTML = '<tr><td colspan="3">Không có dữ liệu</td></tr>';
        return;
    }
    ttpTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MatrangthaiP ?? ''}</td>
            <td>${item.Tentrangthai ?? ''}</td>
            <td>
                <button class="ttp-btn ttp-detail" data-mattp="${item.MatrangthaiP}">Chi tiết</button>
                <button class="ttp-btn ttp-edit" data-mattp="${item.MatrangthaiP}">Sửa</button>
                <button class="ttp-btn ttp-delete" data-mattp="${item.MatrangthaiP}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchTrangThaiPhong() {
    if (!_ttpData) {
        ttpTbody.innerHTML = '<tr><td colspan="3">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_ttpData);
    }
    fetch("http://localhost:86/cnpm-BE/api/trangthaiphong")
        .then(res => res.json())
        .then(data => {
            _ttpData = data;
            renderRows(data);
        })
        .catch(() => {
            ttpTbody.innerHTML = '<tr><td colspan="3">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchTrangThaiPhong();

document.getElementById('ttp-btn-add').onclick = () => loadTTPView('add');

function loadTTPView(view, mattp = '') {
    fetch(`views/trangthaiphong/${view}.php`)
        .then(res => res.text())
        .then(html => {
            ttpContent.innerHTML = html;
            ttpTableWrap.style.display = 'none';
            ttpContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && mattp) {
                document.querySelectorAll('[name="mattp"]').forEach(e => e.value = mattp);
                if (view === 'detail') {
                    document.getElementById('ttp-mattp').textContent = mattp;
                }
            }
        });
}
function backToMain() {
    ttpContent.innerHTML = '';
    ttpTableWrap.style.display = '';
    renderRows(_ttpData || []);
}
ttpContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('ttp-back')) backToMain();
});
ttpTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('ttp-detail')) {
        loadTTPView('detail', e.target.dataset.mattp);
    } else if (e.target.classList.contains('ttp-edit')) {
        loadTTPView('edit', e.target.dataset.mattp);
    } else if (e.target.classList.contains('ttp-delete')) {
        loadTTPView('delete', e.target.dataset.mattp);
    }
});
</script> 