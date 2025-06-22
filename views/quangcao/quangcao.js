// Quản lý Quảng cáo - JS
function fetchQuangCaoList() {
    const msg = document.getElementById('quangcao-list-msg');
    const loading = document.getElementById('quangcao-list-loading');
    const table = document.getElementById('quangcao-list-table');
    const tbody = document.getElementById('quangcao-list-tbody');
    if (!tbody) return;
    loading && (loading.style.display = '');
    table && (table.style.display = 'none');
    msg && (msg.textContent = '');
    fetch('http://localhost:86/cnpm-be/api/quangcao')
        .then(res => res.json())
        .then(data => {
            const arr = Array.isArray(data) ? data : (data.data || []);
            tbody.innerHTML = arr.length ? arr.map(qc => `
                <tr>
                    <td>${qc.Maquangcao || ''}</td>
                    <td>${qc.Tieude || ''}</td>
                    <td>${qc.Image ? `<img src="${qc.Image}" alt="QC" style="max-width:60px;max-height:40px;border-radius:6px;">` : ''}</td>
                    <td>${qc.Ngaybatdau || ''}</td>
                    <td>${qc.Ngayketthuc || ''}</td>
                    <td>
                        <button class="quangcao-action-btn" onclick="viewDetail('${qc.Maquangcao}')">Chi tiết</button>
                        <button class="quangcao-action-btn" onclick="editQuangCao('${qc.Maquangcao}')">Sửa</button>
                        <button class="quangcao-action-btn" onclick="deleteQuangCao('${qc.Maquangcao}')">Xoá</button>
                    </td>
                </tr>
            `).join('') : '<tr><td colspan="6" style="text-align:center;color:#ff4081">Không có dữ liệu</td></tr>';
            loading && (loading.style.display = 'none');
            table && (table.style.display = '');
        })
        .catch(() => {
            msg && (msg.textContent = 'Lỗi tải dữ liệu!');
            msg && (msg.className = 'quangcao-msg error');
            loading && (loading.style.display = 'none');
        });
}
window.fetchQuangCaoList = fetchQuangCaoList;

// Gọi fetch khi load file JS
fetchQuangCaoList();

function initAddQuangCaoForm() {
    const form = document.getElementById('qc-add-form');
    const msg = document.getElementById('qc-add-msg');
    if (!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'qc-msg';
        fetch('http://localhost:86/cnpm-be/api/quangcao', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                Tieude: form.tenqc.value,
                Noidung: form.noidung.value,
                Loaiquangcao: form.loaiquangcao.value,
                Image: form.hinhanh.value,
                Ngaybatdau: form.ngaybd.value,
                Ngayketthuc: form.ngaykt.value,
                Manguoidung: form.manguoidung.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message || data.success || data.status === 'success') {
                msg.textContent = data.message || 'Thêm quảng cáo thành công!';
                msg.className = 'qc-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); fetchQuangCaoList(); }, 1000);
            } else {
                msg.textContent = data.message || 'Thêm quảng cáo thất bại!';
                msg.className = 'qc-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'qc-msg error';
        });
    };
}
window.initAddQuangCaoForm = initAddQuangCaoForm; 