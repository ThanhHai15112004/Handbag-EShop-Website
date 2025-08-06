<!-- Modal Thông tin Admin -->
<div id="adminProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeProfileModal">&times;</span>
        <h3>Thông tin tài khoản Admin</h3>
        <form id="adminProfileForm">
            <div class="form-row">
                <div class="form-group">
                    <label>Tên đăng nhập:</label>
                    <input type="text" id="adminUsername" class="form-control" readonly />
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="adminEmail" class="form-control" required />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Họ tên:</label>
                    <input type="text" id="adminFullName" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <input type="tel" id="adminPhone" class="form-control" required />
                </div>
            </div>
            <div class="form-group">
                <label>Chức vụ:</label>
                <input type="text" id="adminPosition" class="form-control" readonly value="Quản trị viên" />
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Ngày tạo:</label>
                    <input type="text" id="adminCreatedAt" class="form-control" readonly />
                </div>
                <div class="form-group">
                    <label>Lần đăng nhập cuối:</label>
                    <input type="text" id="adminLastLogin" class="form-control" readonly />
                </div>
            </div>
            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelProfileModal">Hủy</button>
                <button type="submit" class="btn btn-success">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Đổi mật khẩu -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeChangePasswordModal">&times;</span>
        <h3>Đổi mật khẩu</h3>
        <form id="changePasswordForm">
            <div class="form-group">
                <label>Mật khẩu hiện tại:</label>
                <input type="password" id="currentPassword" class="form-control" required />
            </div>
            <div class="form-group">
                <label>Mật khẩu mới:</label>
                <input type="password" id="newPassword" class="form-control" required minlength="6" />
            </div>
            <div class="form-group">
                <label>Xác nhận mật khẩu mới:</label>
                <input type="password" id="confirmPassword" class="form-control" required minlength="6" />
            </div>
            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelChangePasswordModal">Hủy</button>
                <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
            </div>
        </form>
    </div>
</div>

