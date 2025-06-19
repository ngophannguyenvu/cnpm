<h2>Đánh giá</h2>
<div id="dg-content-main">
<p>Đây là giao diện quản lý Đánh giá. Bạn có thể thêm, sửa, xóa hoặc xem danh sách đánh giá tại đây.</p>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
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
    <tbody>
        <tr>
            <td>5</td><td>Rất tốt</td><td>2024-06-01</td><td>ND001</td><td>HD001</td>
            <td>
                <button class="btn-detail" data-id="ND001-HD001">Chi tiết</button>
                <button class="btn-edit" data-id="ND001-HD001">Sửa</button>
                <button class="btn-delete" data-id="ND001-HD001">Xoá</button>
            </td>
        </tr>
        <tr>
            <td>4</td><td>Tốt</td><td>2024-06-02</td><td>ND002</td><td>HD002</td>
            <td>
                <button class="btn-detail" data-id="ND002-HD002">Chi tiết</button>
                <button class="btn-edit" data-id="ND002-HD002">Sửa</button>
                <button class="btn-delete" data-id="ND002-HD002">Xoá</button>
            </td>
        </tr>
    </tbody>
</table>
<button class="btn-add">Thêm mới</button>
</div>
<div id="dg-content"></div>
<script>
const dgContent = document.getElementById('dg-content');
const dgContentMain = document.getElementById('dg-content-main');
function loadDGView(view) {
    fetch('views/danhgia/' + view + '.php')
        .then(res => res.text())
        .then(html => {
            dgContent.innerHTML = html;
            dgContentMain.style.display = 'none';
        });
}
function backToMainDG() {
    dgContent.innerHTML = '';
    dgContentMain.style.display = '';
}
document.querySelector('.btn-add').onclick = () => loadDGView('add');
document.querySelectorAll('.btn-edit').forEach(btn => btn.onclick = () => loadDGView('edit'));
document.querySelectorAll('.btn-delete').forEach(btn => btn.onclick = () => loadDGView('delete'));
document.querySelectorAll('.btn-detail').forEach(btn => btn.onclick = () => loadDGView('detail'));
dgContent.addEventListener('click', function(e) {
    if (e.target.tagName === 'BUTTON' && e.target.textContent.includes('Quay lại')) {
        backToMainDG();
    }
});
</script> 