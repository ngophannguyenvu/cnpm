<h2>Chi tiết dịch vụ</h2>
<div id="ctdv-content-main">
<p>Đây là giao diện quản lý Chi tiết dịch vụ. Bạn có thể thêm, sửa, xóa hoặc xem danh sách chi tiết dịch vụ tại đây.</p>
<!-- Thêm bảng mẫu -->
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Mã ĐL</th>
            <th>Mã DV</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>DL001</td><td>DV001</td>
            <td>
                <button class="btn-detail" data-id="DL001-DV001">Chi tiết</button>
                <button class="btn-edit" data-id="DL001-DV001">Sửa</button>
                <button class="btn-delete" data-id="DL001-DV001">Xoá</button>
            </td>
        </tr>
        <tr>
            <td>DL002</td><td>DV002</td>
            <td>
                <button class="btn-detail" data-id="DL002-DV002">Chi tiết</button>
                <button class="btn-edit" data-id="DL002-DV002">Sửa</button>
                <button class="btn-delete" data-id="DL002-DV002">Xoá</button>
            </td>
        </tr>
    </tbody>
</table>
<button class="btn-add">Thêm mới</button>
</div>
<div id="ctdv-content"></div>
<script>
const ctdvContent = document.getElementById('ctdv-content');
const ctdvContentMain = document.getElementById('ctdv-content-main');
function loadCTDVView(view) {
    fetch('views/chitietdichvu/' + view + '.php')
        .then(res => res.text())
        .then(html => {
            ctdvContent.innerHTML = html;
            ctdvContentMain.style.display = 'none';
        });
}
function backToMain() {
    ctdvContent.innerHTML = '';
    ctdvContentMain.style.display = '';
}
document.querySelector('.btn-add').onclick = () => loadCTDVView('add');
document.querySelectorAll('.btn-edit').forEach(btn => btn.onclick = () => loadCTDVView('edit'));
document.querySelectorAll('.btn-delete').forEach(btn => btn.onclick = () => loadCTDVView('delete'));
document.querySelectorAll('.btn-detail').forEach(btn => btn.onclick = () => loadCTDVView('detail'));
// Xử lý nút quay lại trong các view con
ctdvContent.addEventListener('click', function(e) {
    if (e.target.tagName === 'BUTTON' && e.target.textContent.includes('Quay lại')) {
        backToMain();
    }
});
</script> 