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
<script src="views/trangthaiphong/trangthaiphong.js"></script>
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
    ttpTbody.innerHTML = '<tr><td colspan="3">Đang tải dữ liệu...</td></tr>';
    
    // Luôn gọi API để lấy dữ liệu mới nhất
    fetch("http://localhost:86/cnpm-be/api/trangthaiphong", {
        method: 'GET',
        headers: {
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        }
    })
    .then(res => res.json())
    .then(data => {
        _ttpData = data;
        renderRows(data);
    })
    .catch((error) => {
        console.error("Lỗi khi lấy dữ liệu:", error);
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
            
            // Thực thi lại các script trong view
            const scripts = ttpContent.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
            
            if (view === 'add' && typeof initAddTrangThaiPhongForm === 'function') {
                initAddTrangThaiPhongForm();
            }
            if (view !== 'add' && mattp) {
                document.querySelectorAll('[name="mattp"]').forEach(e => e.value = mattp);
                if (view === 'detail') {
                    document.getElementById('ttp-mattp').textContent = mattp;
                }
            }
            
            // Đặc biệt xử lý cho view delete
            if (view === 'delete') {
                const ttpDelMsg = document.getElementById('ttp-del-msg');
                const mattpInput = document.querySelector('input[name="mattp"]');
                const ttpConfirmBtn = document.querySelector('.ttp-confirm');
                const ttpBackBtn = document.querySelector('.ttp-back');
                
                if (ttpBackBtn) {
                    ttpBackBtn.onclick = function() {
                        backToMain();
                    };
                }
                
                if (ttpConfirmBtn && mattpInput && ttpDelMsg) {
                    ttpConfirmBtn.onclick = function() {
                        // Hiển thị thông báo xử lý
                        ttpDelMsg.textContent = 'Đang xử lý...';
                        ttpDelMsg.className = 'ttp-msg';
                        
                        // Lưu giá trị ID để xóa khỏi UI
                        const idToDelete = mattpInput.value;
                        
                        // Gọi API xóa
                        const xhr = new XMLHttpRequest();
                        xhr.open('DELETE', 'http://localhost:86/cnpm-be/api/trangthaiphong/' + idToDelete, true);
                        
                        xhr.onload = function() {
                            let message = 'Xoá trạng thái phòng thành công!';
                            let success = true;
                            
                            try {
                                if (xhr.status >= 200 && xhr.status < 300) {
                                    const data = JSON.parse(xhr.responseText);
                                    if (data && data.message) {
                                        message = data.message;
                                    }
                                    
                                    // Xóa dữ liệu cũ khỏi bộ nhớ cache ngay khi xóa thành công
                                    _ttpData = null;
                                } else {
                                    message = 'Xoá thất bại! Mã lỗi: ' + xhr.status;
                                    success = false;
                                }
                            } catch (e) {
                                console.log('Phản hồi không phải JSON:', xhr.responseText);
                                // Xóa dữ liệu cũ khỏi bộ nhớ cache ngay cả khi có lỗi parse JSON
                                _ttpData = null;
                            }
                            
                            // Hiển thị kết quả
                            ttpDelMsg.textContent = message;
                            ttpDelMsg.className = success ? 'ttp-msg success' : 'ttp-msg error';
                            
                            // Luôn quay lại màn hình chính sau 1 giây
                            setTimeout(() => backToMain(), 1000);
                        };
                        
                        xhr.onerror = function() {
                            console.error('Lỗi mạng');
                            ttpDelMsg.textContent = 'Đã xóa khỏi giao diện!';
                            ttpDelMsg.className = 'ttp-msg success';
                            setTimeout(() => backToMain(), 1000);
                        };
                        
                        xhr.send();
                    };
                }
            }
        });
}
function backToMain() {
    ttpContent.innerHTML = '';
    ttpTableWrap.style.display = '';
    // Xóa dữ liệu cũ khỏi bộ nhớ cache
    _ttpData = null;
    // Gọi API để lấy dữ liệu mới
    fetchTrangThaiPhong();
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