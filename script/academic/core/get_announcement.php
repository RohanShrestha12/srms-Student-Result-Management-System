<?php
chdir('../../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_announcements WHERE id = ?");
        $stmt->execute([$id]);
        $announcement = $stmt->fetch();
        
        if ($announcement) {
?>
            <form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_announcement.php">
                <div class="mb-2">
                    <label class="form-label">Enter Title</label>
                    <input required type="text" name="title" class="form-control txt-cap" 
                           placeholder="Enter Announcement Title" value="<?php echo htmlspecialchars($announcement[1]); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Audience</label>
                    <select class="form-control" name="audience" required>
                        <option value="" disabled>Select one</option>
                        <option value="1" <?php echo ($announcement[4] == '1') ? 'selected' : ''; ?>>Students Only</option>
                        <option value="0" <?php echo ($announcement[4] == '0') ? 'selected' : ''; ?>>Teachers Only</option>
                        <option value="2" <?php echo ($announcement[4] == '2') ? 'selected' : ''; ?>>Students & Teachers</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Announcement</label>
                    <textarea name="announcement" id="summernote_edit" required><?php echo htmlspecialchars($announcement[2]); ?></textarea>
                    <script>
                        $('#summernote_edit').summernote({
                            tabsize: 2,
                            height: 120,
                            fontNames: ['Comic Sans MS']
                        });
                    </script>
                </div>

                <input type="hidden" name="id" value="<?php echo $announcement[0]; ?>">
                <button type="submit" name="submit" value="1" class="btn btn-primary app_btn">Update</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </form>
<?php
        } else {
            echo '<div class="alert alert-danger">Announcement not found.</div>';
        }
    } catch(PDOException $e) {
        echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
    }
} else {
    echo '<div class="alert alert-danger">No announcement ID provided.</div>';
}
?> 