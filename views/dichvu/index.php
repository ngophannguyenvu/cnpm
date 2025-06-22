<style>
.dv-container { max-width: 900px; margin: 40px auto; background: #fff0f6; border-radius: 16px; box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15); padding: 32px 24px 24px 24px; font-family: 'Segoe UI', Arial, sans-serif; }
.dv-title { color: #ff4081; text-align: center; margin-bottom: 16px; font-size: 2rem; font-weight: bold; letter-spacing: 1px; }
.dv-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; background: #fff; border-radius: 8px; overflow: hidden; }
.dv-table th, .dv-table td { padding: 12px 16px; text-align: center; }
.dv-table th { background: #ff80ab; color: #fff; font-size: 1.1rem; font-weight: 600; }
.dv-table tr:nth-child(even) { background: #ffe4ec; }
.dv-table tr:hover { background: #ffd1e6; }
.dv-btn { background: #ff4081; color: #fff; border: none; border-radius: 6px; padding: 8px 16px; margin: 0 2px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
.dv-btn:hover { background: #e73370; }
.dv-btn.dv-add { background: linear-gradient(90deg, #ff80ab, #ff4081); font-weight: bold; margin-top: 8px; width: 160px; display: block; margin-left: auto; margin-right: auto; }
@media (max-width: 600px) { .dv-container { padding: 10px; } .dv-title { font-size: 1.2rem; } .dv-table th, .dv-table td { padding: 6px 4px; font-size: 0.95rem; } }
</style>
<div class="dv-container">
    <div class="dv-title">Quản lý Dịch vụ</div>
    <div id="dv-table-wrap">
        <table class="dv-table">
            <thead>
                <tr>
                    <th>Mã DV</th>
                    <th>Tên dịch vụ</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="dv-tbody">
                <tr><td colspan="5">Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
    <button class="dv-btn dv-add" id="dv-btn-add">+ Thêm mới</button>
    <div id="dv-content"></div>
</div>
<script src="views/dichvu/dichvu.js"></script>
<script>
let _dvData = null;
const dvTbody = document.getElementById('dv-tbody');
const dvContent = document.getElementById('dv-content');
const dvTableWrap = document.getElementById('dv-table-wrap');

function renderDVRows(data) {
    if (!Array.isArray(data) || data.length === 0) {
        dvTbody.innerHTML = '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        return;
    }
    dvTbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.MaDV}</td>
            <td>${item.Tendichvu || ''}</td>
            <td>${item.Gia ? Number(item.Gia).toLocaleString('vi-VN') + 'đ' : ''}</td>
            <td>${item.MoTa || ''}</td>
            <td>
                <button class="dv-btn dv-edit" data-madv="${item.MaDV}">Sửa</button>
                <button class="dv-btn dv-delete" data-madv="${item.MaDV}">Xoá</button>
            </td>
        </tr>
    `).join('');
}
function fetchDichVu() {
    dvTbody.innerHTML = '<tr><td colspan="5">Đang tải dữ liệu...</td></tr>';
    fetch("http://localhost:86/cnpm-be/api/dichvu")
        .then(res => res.json())
        .then(data => {
            _dvData = data;
            renderDVRows(data);
        })
        .catch(() => {
            dvTbody.innerHTML = '<tr><td colspan="5">Lỗi tải dữ liệu</td></tr>';
        });
}
fetchDichVu();

document.getElementById('dv-btn-add').onclick = () => loadDVView('add');

function loadDVView(view, madv = '') {
    fetch(`views/dichvu/${view}.php`)
        .then(res => res.text())
        .then(html => {
            dvContent.innerHTML = html;
            dvTableWrap.style.display = 'none';
            dvContent.scrollIntoView({behavior: 'smooth'});
            if (view === 'add' && typeof initAddDichVuForm === 'function') {
                initAddDichVuForm();
            }
            if (view === 'edit' && typeof window.initEditDichVuForm === 'function') {
                window.initEditDichVuForm();
            }
            if (view !== 'add' && madv) {
                document.querySelectorAll('[name="madv"]').forEach(e => e.value = madv);
            }
        });
}
function backToDVMain() {
    dvContent.innerHTML = '';
    dvTableWrap.style.display = '';
    fetchDichVu();
}
dvContent.addEventListener('click', function(e) {
    if (e.target.classList.contains('dv-back')) backToDVMain();
});
dvTbody.addEventListener('click', function(e) {
    if (e.target.classList.contains('dv-edit')) {
        loadDVView('edit', e.target.dataset.madv);
    } else if (e.target.classList.contains('dv-delete')) {
        loadDVView('delete', e.target.dataset.madv);
    }
});
</script> 