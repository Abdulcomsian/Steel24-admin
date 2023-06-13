<!-- resources/views/admin/lots/import-csv.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>CSV Import and Data</title>
</head>
<body>
    <h1>CSV Import</h1>

    <form action="<?php echo e(url('admin/lots/import-csv')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="file" name="csv_file">
        <button type="submit">Import</button>
    </form>

    <hr>

    <h1>CSV Data</h1>
   
    
</body>
</html>



<?php /**PATH C:\xampp\htdocs\Steel24-admin\resources\views/admin/lots/import-csv.blade.php ENDPATH**/ ?>