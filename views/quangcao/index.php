<style>
.quangcao-list-box {
    max-width: 900px;
    margin: 40px auto;
    background: #fff0f6;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(255, 105, 135, 0.15);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.quangcao-list-title {
    color: #ff4081;
    text-align: center;
    margin-bottom: 18px;
    font-size: 1.7rem;
    font-weight: bold;
}
.quangcao-list-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 18px;
    overflow: hidden;
}
.quangcao-list-table th, .quangcao-list-table td {
    padding: 10px 12px;
    text-align: left;
}
.quangcao-list-table th {
    background: #ff80ab;
    color: #fff;
    font-weight: 600;
}
.quangcao-list-table tr:nth-child(even) {
    background: #ffe4ec;
}
.quangcao-list-table td .quangcao-action-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 4px 10px;
    margin: 0 2px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background 0.2s;
}
.quangcao-list-table td .quangcao-action-btn:hover {
    background: #e73370;
}
.quangcao-list-btns {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-bottom: 10px;
}
.quangcao-btn {
    background: linear-gradient(90deg, #ff80ab, #ff4081);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 18px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}
.quangcao-btn:hover {
    background: #e73370;
}
.quangcao-btn.quangcao-back {
    background: #fff;
    color: #ff4081;
    border: 1px solid #ff80ab;
}
.quangcao-btn.quangcao-back:hover {
    background: #ffe4ec;
}
.quangcao-msg {
    text-align: center;
    margin-bottom: 10px;
    font-size: 1rem;
}
.quangcao-msg.success { color: #43a047; }
.quangcao-msg.error { color: #e53935; }
@media (max-width: 700px) {
    .quangcao-list-box { padding: 10px; }
    .quangcao-list-title { font-size: 1.1rem; }
    .quangcao-list-table th, .quangcao-list-table td { padding: 6px 4px; font-size: 0.95rem; }
}
</style>
<div class="quangcao-list-box">
    <div class="quangcao-list-title">Quản lý Quảng cáo</div>
    <div class="quangcao-msg" id="quangcao-list-msg"></div>
    <div class="quangcao-list-btns">
        <button class="quangcao-btn quangcao-add">+ Thêm mới</button>
        <button class="quangcao-btn quangcao-back">Quay lại</button>
    </div>
    <div id="quangcao-list-loading">Đang tải dữ liệu...</div>
    <table class="quangcao-list-table" id="quangcao-list-table" style="display:none">
        <thead>
            <tr>
                <th>Mã QC</th>
                <th>Tên QC</th>
                <th>Hình ảnh</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="quangcao-list-tbody"></tbody>
    </table>
</div>
<script src="views/quangcao/quangcao.js"></script> 