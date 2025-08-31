    </main>

<?php
// Student Footer - Include this at the end of all student pages
?>

<script src="js/jquery-3.7.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script src="loader/waitMe.js"></script>
<script src="js/forms.js"></script>
<script src="js/sweetalert2@11.js"></script>
<script src="js/header-enhanced.js"></script>

<?php if (isset($include_datatables) && $include_datatables): ?>
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.html"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if srmsTable exists and has proper structure before initializing
            if ($('#srmsTable').length && $('#srmsTable thead tr').length > 0) {
                const columnCount = $('#srmsTable thead tr:first th').length;
                const rowCount = $('#srmsTable tbody tr').length;
                
                // Only initialize if table has proper structure
                if (columnCount > 0 && rowCount > 0) {
                    $('#srmsTable').DataTable({ 
                        "sort": false,
                        "responsive": true,
                        "autoWidth": false
                    });
                }
            }
        });
    </script>
<?php endif; ?>

<?php require_once('const/check-reply.php'); ?>

</body>

</html> 