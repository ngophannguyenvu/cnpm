<style>
.pt-form-wrap {
    background: #fff0f6;
    border-radius: 12px;
    padding: 32px 24px 24px 24px;
    max-width: 400px;
    margin: 40px auto 0 auto;
    box-shadow: 0 2px 16px rgba(255, 105, 135, 0.10);
}
.pt-form-title {
    color: #ff4081;
    text-align: center;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 18px;
}
.pt-form label {
    display: block;
    margin-bottom: 10px;
    color: #d81b60;
    font-weight: 500;
}
.pt-form input[type="text"] {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ff80ab;
    border-radius: 6px;
    margin-bottom: 18px;
    font-size: 1rem;
    background: #fff;
    transition: border 0.2s;
}
.pt-form input[type="text"]:focus {
    border: 1.5px solid #ff4081;
    outline: none;
}
.pt-form-btns {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.pt-btn {
    background: #ff4081;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.pt-btn:hover { background: #e73370; }
.pt-btn.pt-back { background: #ff80ab; }
.pt-form-msg {
    text-align: center;
    margin-top: 12px;
    font-weight: 500;
}
.pt-form-msg.success { color: #43a047; }
.pt-form-msg.error { color: #d32f2f; }
</style>
<div class="pt-form-wrap">
    <div class="pt-form-title">Thêm Phương thức thanh toán</div>
    <form class="pt-form" id="pt-add-form" autocomplete="off">
        <label>Mã phương thức:
            <input type="text" name="mapt" required placeholder="Nhập mã phương thức">
        </label>
        <label>Tên phương thức:
            <input type="text" name="tenpt" required placeholder="Nhập tên phương thức">
        </label>
        <label>Mô tả:
            <input type="text" name="mota" required placeholder="Nhập mô tả">
        </label>
        <div class="pt-form-btns">
            <button type="submit" class="pt-btn">Thêm</button>
            <button type="button" class="pt-btn pt-back">Quay lại</button>
        </div>
        <div class="pt-form-msg" id="pt-add-msg"></div>
    </form> 