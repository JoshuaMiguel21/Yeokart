<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Employee</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.js"></script>
  <link href="../css/add_employee.css" rel="stylesheet" />
</head>
<body style="background-color: #DD2F6E;">
  <div class="container">
    <h2 class="mt-4 mb-4">Edit Employee</h2>

    <?php
        require('../database/db_account.php');

        if (isset($_GET['id'])) {
        $employeeId = $_GET['id'];
        $query = "SELECT * FROM `employee_accounts` WHERE id = $employeeId";
        $result = $con->query($query);

        if ($result->num_rows > 0) {
            $employee = $result->fetch_assoc();
      ?>
        <form action="update_employee.php" method="post">
          <input type="hidden" name="employeeId" value="<?php echo $employee['id']; ?>">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="firstName">First Name</label>
              <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" value="<?php echo $employee['firstname']; ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label for="lastName">Last Name</label>
              <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" value="<?php echo $employee['lastname']; ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo $employee['username']; ?>" required>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo $employee['email']; ?>" readonly>
          </div>

          <div class="button-container">
            <button type="submit" id="submit" class="btn btn-custom btn-lg" name="submit">Update</button>
          </div>
        </form>
    <?php
      } else {
        echo "<p>No employee found with the provided ID.</p>";
      }
    } else {
      echo "<p>Employee ID not provided.</p>";
    }
    ?>

  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
