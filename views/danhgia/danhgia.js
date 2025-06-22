function initAddDanhGiaForm() {
    const dgAddForm = document.getElementById('dg-add-form');
    const dgAddMsg = document.getElementById('dg-add-msg') || document.querySelector('.dg-form-msg');
    if (!dgAddForm) return;
    dgAddForm.onsubmit = function(e) {
        e.preventDefault();
        const danhgiasao = this.danhgiasao.value.trim();
        const nhanxet = this.nhanxet.value.trim();
        const ngaydanhgia = this.ngaydanhgia.value;
        const manguoidung = this.manguoidung.value.trim();
        const mahd = this.mahd.value.trim();
        dgAddMsg.textContent = '';
        dgAddMsg.className = 'dg-form-msg';
        if (!danhgiasao || !nhanxet || !ngaydanhgia || !manguoidung || !mahd) {
            dgAddMsg.textContent = 'Vui lòng nhập đầy đủ thông tin!';
            dgAddMsg.classList.add('error');
            return;
        }
        fetch('http://localhost:86/cnpm-be/api/danhgia', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ Danhgiasao: danhgiasao, Nhanxet: nhanxet, Ngaydanhgia: ngaydanhgia, Manguoidung: manguoidung, MaHD: mahd })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message && data.message.toLowerCase().includes('success')) {
                dgAddMsg.textContent = 'Thêm đánh giá thành công!';
                dgAddMsg.className = 'dg-form-msg success';
                setTimeout(() => {
                    if (typeof backToMain === 'function') backToMain();
                    if (typeof fetchDanhGia === 'function') fetchDanhGia();
                }, 1000);
            } else {
                dgAddMsg.textContent = data.error || data.message || 'Thêm đánh giá thất bại!';
                dgAddMsg.className = 'dg-form-msg error';
            }
        })
        .catch(() => {
            dgAddMsg.textContent = 'Lỗi kết nối máy chủ!';
            dgAddMsg.className = 'dg-form-msg error';
        });
    };
} 