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
    fetch('http://localhost:81/cnpm/api/quangcao')
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
                        <button class="quangcao-action-btn btn-detail" data-id="${qc.Maquangcao}">Chi tiết</button>
                        <button class="quangcao-action-btn btn-edit" data-id="${qc.Maquangcao}">Sửa</button>
                        <button class="quangcao-action-btn btn-delete" data-id="${qc.Maquangcao}">Xoá</button>
                    </td>
                </tr>
            `).join('') : '<tr><td colspan="6" style="text-align:center;color:#ff4081">Không có dữ liệu</td></tr>';
            loading && (loading.style.display = 'none');
            table && (table.style.display = '');
            addQuangCaoEventListeners();
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

function addQuangCaoEventListeners() {
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (typeof loadQuangCaoView === 'function') loadQuangCaoView('detail', id);
        });
    });
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (typeof loadQuangCaoView === 'function') loadQuangCaoView('edit', id);
        });
    });
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (typeof loadQuangCaoView === 'function') loadQuangCaoView('delete', id);
        });
    });
}

function initAddQuangCaoForm() {
    const form = document.getElementById('qc-add-form');
    const msg = document.getElementById('qc-add-msg');
    if (!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'qc-msg';
        fetch('http://localhost:81/cnpm/api/quangcao', {
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

function initEditQuangCaoForm() {
    const form = document.getElementById('qc-edit-form');
    const msg = document.getElementById('qc-edit-msg');
    if (!form) return;
    const maqc = form.maqc.value;
    if (typeof _quangCaoData !== 'undefined' && maqc) {
        const qc = (_quangCaoData || []).find(x => x.Maquangcao == maqc || x.MaQC == maqc);
        if (qc) {
            form.tenqc.value = qc.Tieude || qc.TenQC || '';
            form.noidung.value = qc.Noidung || '';
            form.loaiquangcao.value = qc.Loaiquangcao || '';
            form.hinhanh.value = qc.Image || qc.HinhAnh || '';
            form.ngaybd.value = qc.Ngaybatdau || qc.NgayBatDau || '';
            form.ngaykt.value = qc.Ngayketthuc || qc.NgayKetThuc || '';
            form.manguoidung.value = qc.Manguoidung || '';
            // Hiển thị thông tin cũ
            const oldInfo = document.getElementById('qc-old-info');
            if (oldInfo) {
                oldInfo.style.display = '';
                document.getElementById('qc-old-maqc').textContent = qc.Maquangcao || qc.MaQC || '';
                document.getElementById('qc-old-tenqc').textContent = qc.Tieude || qc.TenQC || '';
                document.getElementById('qc-old-noidung').textContent = qc.Noidung || '';
                document.getElementById('qc-old-loaiquangcao').textContent = qc.Loaiquangcao || '';
                document.getElementById('qc-old-hinhanh').textContent = qc.Image || qc.HinhAnh || '';
                document.getElementById('qc-old-ngaybd').textContent = qc.Ngaybatdau || qc.NgayBatDau || '';
                document.getElementById('qc-old-ngaykt').textContent = qc.Ngayketthuc || qc.NgayKetThuc || '';
                document.getElementById('qc-old-manguoidung').textContent = qc.Manguoidung || '';
            }
        }
    }
    form.onsubmit = function(e) {
        e.preventDefault();
        msg.textContent = 'Đang xử lý...';
        msg.className = 'qc-msg';
        fetch('http://localhost:81/cnpm/api/quangcao/' + form.maqc.value, {
            method: 'PUT',
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
                msg.textContent = data.message || 'Cập nhật quảng cáo thành công!';
                msg.className = 'qc-msg success';
                setTimeout(() => { if (typeof backToMain === 'function') backToMain(); if (typeof fetchQuangCaoList === 'function') fetchQuangCaoList(); }, 1000);
            } else {
                msg.textContent = data.message || 'Cập nhật thất bại!';
                msg.className = 'qc-msg error';
            }
        })
        .catch(() => {
            msg.textContent = 'Lỗi kết nối máy chủ!';
            msg.className = 'qc-msg error';
        });
    };
}
window.initEditQuangCaoForm = initEditQuangCaoForm; 