<style>
.ctdv-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.ctdv-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.ctdv-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.ctdv-table th, .ctdv-table td {
    padding: 12px 16px;
    text-align: center;
}
.ctdv-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.ctdv-table tr:nth-child(even) {
    background: #ffe4ec;
}
.ctdv-table tr:hover {
    background: #ffd1e6;
}
.ctdv-btn {
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
.ctdv-btn:hover {
    background: #e73370;
}
.ctdv-btn.ctdv-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .ctdv-container { padding: 10px; }
    .ctdv-title { font-size: 1.2rem; }
    .ctdv-table th, .ctdv-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="ctdv-container">
    <div class="ctdv-title">Quản lý Chi tiết Dịch vụ</div>
    
    <div id="ctdv-table-wrap">
        <table class="ctdv-table">
            <thead>
                <tr>
                    <th>Mã ĐL</th>
                    <th>Mã DV</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="ctdv-tbody">
                <tr><td colspan="3">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>

    <button class="ctdv-btn ctdv-add" id="ctdv-btn-add">+ Thêm mới</button>
    <div id="ctdv-content"></div>
</div>

<script>
let _ctdvData = null;
const ctdvTbody = document.getElementById('ctdv-tbody');
const ctdvContent = document.getElementById('ctdv-content');
const ctdvTableWrap = document.getElementById('ctdv-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        ctdvTbody.innerHTML = '<tr><td colspan="3">Không có dữ liệu</td></tr>';
        return;
    }
    ctdvTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MaDL}</td>
            <td>${item.MaDV}</td>
            <td>
                <button class="ctdv-btn ctdv-detail" data-madl="${item.MaDL}" data-madv="${item.MaDV}">Chi tiết</button>
                <button class="ctdv-btn ctdv-edit" data-madl="${item.MaDL}" data-madv="${item.MaDV}">Sửa</button>
                <button class="ctdv-btn ctdv-delete" data-madl="${item.MaDL}" data-madv="${item.MaDV}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchCTDV() {
    if (!_ctdvData) {
        ctdvTbody.innerHTML = '<tr><td colspan="3">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_ctdvData);
    }
    fetch("http://localhost:86/cnpm-BE/api/chitietdichvu")
        .then(res => res.json())
        .then(data => {
            _ctdvData = data;
            renderRows(data);
        })
        .catch(() => {
            ctdvTbody.innerHTML = '<tr><td colspan="3">Lỗi tải dữ liệu</td></tr>';
        });
}

function loadCTDVView(view, madl = '', madv = '') {
    fetch(`views/chitietdichvu/${view}.php`)
        .then(res => res.text())
        .then(html => {
            ctdvContent.innerHTML = html;
            ctdvTableWrap.style.display = 'none';
            ctdvContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && madl && madv) {
                document.querySelectorAll('[name="madl"]').forEach(e => e.value = madl);
                document.querySelectorAll('[name="madv"]').forEach(e => e.value = madv);
                if (view === 'detail') {
                    document.getElementById('ctdv-madl').textContent = madl;
                    document.getElementById('ctdv-madv').textContent = madv;
                }
            }
        });
}

function backToMain() {
    ctdvContent.innerHTML = '';
    ctdvTableWrap.style.display = '';
    renderRows(_ctdvData || []);
}

document.getElementById('ctdv-btn-add').onclick = () => loadCTDVView('add');

ctdvContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('ctdv-back')) backToMain();
});

ctdvTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('ctdv-detail')) {
        loadCTDVView('detail', e.target.dataset.madl, e.target.dataset.madv);
    } else if (e.target.classList.contains('ctdv-edit')) {
        loadCTDVView('edit', e.target.dataset.madl, e.target.dataset.madv);
    } else if (e.target.classList.contains('ctdv-delete')) {
        loadCTDVView('delete', e.target.dataset.madl, e.target.dataset.madv);
    }
});

fetchCTDV();
</script>
 