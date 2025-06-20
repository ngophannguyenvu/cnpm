<style>
.pt-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.pt-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 16px;
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.pt-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.pt-table th, .pt-table td {
    padding: 12px 16px;
    text-align: center;
}
.pt-table th {
    background: #ff80ab;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
}
.pt-table tr:nth-child(even) {
    background: #ffe4ec;
}
.pt-table tr:hover {
    background: #ffd1e6;
}
.pt-btn {
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
.pt-btn:hover {
    background: #e73370;
}
.pt-btn.pt-add {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    font-weight: bold;
    margin-top: 8px;
    width: 160px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 600px) {
    .pt-container { padding: 10px; }
    .pt-title { font-size: 1.2rem; }
    .pt-table th, .pt-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="pt-container">
    <div class="pt-title">Quản lý Phương thức thanh toán</div>
    <div id="pt-table-wrap">
        <table class="pt-table">
            <thead>
                <tr>
                    <th>Mã PT</th>
                    <th>Tên phương thức</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="pt-tbody">
                <tr><td colspan="4">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="pt-btn pt-add" id="pt-btn-add">+ Thêm mới</button>
    <div id="pt-content"></div>
</div>
<script>
let _ptData = null;
const ptTbody = document.getElementById('pt-tbody');
const ptContent = document.getElementById('pt-content');
const ptTableWrap = document.getElementById('pt-table-wrap');

function renderRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        ptTbody.innerHTML = '<tr><td colspan="4">Không có dữ liệu</td></tr>';
        return;
    }
    ptTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MaPT ?? ''}</td>
            <td>${item.TenPT ?? ''}</td>
            <td>${item.Mota ?? ''}</td>
            <td>
                <button class="pt-btn pt-detail" data-mapt="${item.MaPT}">Chi tiết</button>
                <button class="pt-btn pt-edit" data-mapt="${item.MaPT}">Sửa</button>
                <button class="pt-btn pt-delete" data-mapt="${item.MaPT}">Xoá</button>
            </td>
        </tr>
    `).join('');
}

function fetchPhuongThuc() {
    if (!_ptData) {
        ptTbody.innerHTML = '<tr><td colspan="4">Đang tải dữ liệu...</td></tr>';
    } else {
        renderRows(_ptData);
    }
    fetch("http://localhost:86/cnpm-BE/api/phuongthuc")
        .then(res => res.json())
        .then(data => {
            _ptData = data;
            renderRows(data);
        })
        .catch(() => {
            ptTbody.innerHTML = '<tr><td colspan="4">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchPhuongThuc();

document.getElementById('pt-btn-add').onclick = () => loadPTView('add');

function loadPTView(view, mapt = '') {
    fetch(`views/phuongthuc/${view}.php`)
        .then(res => res.text())
        .then(html => {
            ptContent.innerHTML = html;
            ptTableWrap.style.display = 'none';
            ptContent.scrollIntoView({behavior: 'smooth'});
            if (view !== 'add' && mapt) {
                document.querySelectorAll('[name="mapt"]').forEach(e => e.value = mapt);
                if (view === 'detail') {
                    document.getElementById('pt-mapt').textContent = mapt;
                }
            }
        });
}
function backToMain() {
    ptContent.innerHTML = '';
    ptTableWrap.style.display = '';
    renderRows(_ptData || []);
}
ptContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('pt-back')) backToMain();
});
ptTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('pt-detail')) {
        loadPTView('detail', e.target.dataset.mapt);
    } else if (e.target.classList.contains('pt-edit')) {
        loadPTView('edit', e.target.dataset.mapt);
    } else if (e.target.classList.contains('pt-delete')) {
        loadPTView('delete', e.target.dataset.mapt);
    }
});
</script> 