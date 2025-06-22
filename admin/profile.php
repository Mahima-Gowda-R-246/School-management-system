<?php
include('../includes/config.php'); 
include('header.php');
include('sidebar.php');

$message = '';

// Handle Edit Form Submission
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($db_conn, $_POST['id']);
    $email = mysqli_real_escape_string($db_conn, $_POST['email']);
    $name = mysqli_real_escape_string($db_conn, $_POST['name']);
    $password = mysqli_real_escape_string($db_conn, $_POST['password']); 

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $update_query = "UPDATE accounts 
                     SET email = '$email', name = '$name', password = '$hashed_password' 
                     WHERE id = '$id' AND type = 'admin'";
    if (mysqli_query($db_conn, $update_query)) {
        $message = '<div class="alert alert-success">Admin details updated successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error updating record: ' . mysqli_error($db_conn) . '</div>';
    }
}

$admin_query = "SELECT * FROM accounts WHERE type = 'admin'";
$admin_result = mysqli_query($db_conn, $admin_query);
?>
<?php include('footer.php')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .card-header {
            background-color: #5a6268;
            color: #fff;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .btn-primary, .btn-success {
            border-radius: 25px;
        }

        .btn-primary:hover, .btn-success:hover {
            background-color: #495057;
            color: #fff;
        }

        .modal-content {
            border-radius: 12px;
            background-color: #f8f9fa;
        }

        .modal-header {
            background-color: #6c757d;
            color: #fff;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .alert {
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }

        .form-control {
            border-radius: 8px;
            background-color: #f1f3f5;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-sm {
            border-radius: 12px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="text-center mb-4">
        <h2 class="display-4 text-secondary font-weight-bold">Admin Management</h2>
    </div>
    <?= $message; ?>
    <div class="card">
        <div class="card-header">
            Admin Details
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($admin = mysqli_fetch_assoc($admin_result)) { ?>
                        <tr>
                            <td><?= $admin['id']; ?></td>
                            <td><?= htmlspecialchars($admin['email']); ?></td>
                            <td><?= htmlspecialchars($admin['name']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?= $admin['id']; ?>">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $admin['id']; ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Admin Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="">
                                            <div class="form-group">
                                                <label for="id">ID</label>
                                                <input type="text" name="id" class="form-control" value="<?= htmlspecialchars($admin['id']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($admin['name']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                                            </div>
                                            <div class="form-group text-center">
                                                <button type="submit" name="update" class="btn btn-success px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Auto-hide success message -->
<script>
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(alert => alert.style.display = 'none');
    }, 3000);
</script>
</body>
</html>