<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>FAQ's Details - Yeokart</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="../res/icon.png">
</head>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        overflow-x: auto;
        white-space: nowrap;
    }

    th,
    td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        max-width: 200px;
        /* Set a fixed width for the columns */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Use ellipsis to indicate truncated text */
        white-space: nowrap;
        /* Prevent wrapping */
    }

    td.expandable {
        cursor: pointer;
        max-width: 200px;
        /* Set the maximum width to prevent the cell from expanding too much */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* Display ellipsis (...) for overflow text */
    }

    td.expandable.expanded {
        white-space: normal;
        max-width: none;
        overflow: auto;
    }
</style>
<script>
    function openDeletePopup(faqs_id) {
        document.getElementById('deleteConfirmationPopup').style.display = 'flex';
        document.getElementById('deleteForm_' + faqs_id).faqs_id.value = faqs_id;
    }

    function closeDeletePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'none';
    }

    function confirmDelete(faqs_id) {
        // Get the form associated with the delete action
        var deleteForm = document.getElementById('deleteForm_' + faqs_id);
        // Submit the form
        deleteForm.submit();
    }

    function openLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'flex';
    }

    function closeLogoutPopup() {
        document.getElementById('logoutConfirmationPopup').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
    }
    document.addEventListener('DOMContentLoaded', function() {
        var expandableCells = document.querySelectorAll('.expandable');
        expandableCells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                this.classList.toggle('expanded');
            });
        });
    });
</script>

<?php
session_start();

if (!isset($_SESSION['nav_toggle'])) {
    // Set it to unchecked by default
    $_SESSION['nav_toggle'] = false;
}

// Check if the nav-toggle checkbox has been toggled
if (isset($_POST['nav_toggle'])) {
    // Update the session variable accordingly
    $_SESSION['nav_toggle'] = $_POST['nav_toggle'] === 'true' ? true : false;
}

if (isset($_SESSION['firstname'])) {
    $firstname = $_SESSION['firstname'];
} else {
    header("Location: login_page.php");
    exit();
}

if (isset($_SESSION['email'])) {
    $email = strtolower($_SESSION['email']);
} else {
    header("Location: login_page.php");
    exit();
}
?>

<body>

    <input type="checkbox" id="nav-toggle" <?php echo $_SESSION['nav_toggle'] ? 'checked' : ''; ?>>
    <div class="sidebar <?php echo $_SESSION['nav_toggle'] ? 'open' : ''; ?>">
        <div class="sidebar-brand">
            <h2><span>Yeokart</span></h2>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="emp_dashboard.php"><span class="las la-igloo"></span>
                        <span>Employee Dashboard</span></a>
                </li>
                <li>
                    <a href="emp_view_customer.php"><span class="las la-users"></span>
                        <span>Customers</span></a>
                </li>
                <li>
                    <a href="emp_item_homepage.php"><span class="las la-shopping-basket"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="emp_orders.php"><span class="las la-shopping-bag"></span>
                        <span>Orders</span></a>
                </li>
                <li>
                    <a href="emp_content_details.php" class="active"><span class="las la-tasks"></span>
                        <span>Manage Content</span></a>
                </li>
                <li>
                    <a href="#" onclick="openLogoutPopup(); return false;"><span class="las la-sign-out-alt"></span>
                        <span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h3>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                </label>

                Manage Content
            </h3>

            <div class="user-wrapper">
                <div>
                    <h3>Hi, <?php echo $firstname; ?></h3>
                    <small>Employee</small>
                </div>
            </div>
        </header>
        <main>
            <div class="head-title">
                <div class="left">
                    <h3>Manage FAQ's Details</h3>
                </div>

            </div>
            <div class="head-buttons">
                <a href="emp_content_details.php" class="btn-employee">
                    <i class="las la-edit"></i>
                    <span class="text">Edit Contact Details</span>
                </a>
                <a href="emp_featured.php" class="btn-employee">
                    <i class="las la-edit"></i>
                    <span class="text">Edit Featured Section</span>
                </a>
                <a href="emp_faqs.php" class="btn-employee active">
                    <i class="las la-edit"></i>
                    <span class="text">Edit FAQ's Section</span>
                </a>

                <a href="emp_add_faqs.php" class="btn-main">
                    <i class="las la-plus"></i>
                    <span class="text">Add FAQ's</span>
                </a>
            </div>

            <div class="scrollable-container">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <center>Question</center>
                            </th>
                            <th>
                                <center>Answer</center>
                            </th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../database/db_yeokart.php');

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_faqs'])) {
                            $faq_id = $_POST['faq_id'];
                            // Perform deletion query
                            $delete_query = "DELETE FROM faqs WHERE faq_id='$faq_id'";
                            $result_query = mysqli_query($con, $delete_query);
                            if ($result_query) {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'FAQ deleted successfully',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'emp_faqs.php';
                                        }
                                    });
                                </script>";
                            } else {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to delete FAQ',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'emp_faqs.php';
                                        }
                                    });
                                </script>";
                            }
                        }

                        $faqsPerPage = 10;
                        $pageNumber = 1;

                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $pageNumber = $_GET['page'];
                        }

                        $offset = ($pageNumber - 1) * $faqsPerPage;

                        $totalFaqsQuery = "SELECT COUNT(*) AS total_faqs FROM faqs";
                        $totalFaqsResult = mysqli_query($con, $totalFaqsQuery);
                        $totalFaqsRow = mysqli_fetch_assoc($totalFaqsResult);
                        $totalFaqs = $totalFaqsRow['total_faqs'];

                        $select_query = "SELECT * FROM faqs LIMIT $faqsPerPage OFFSET $offset";
                        $result_query = mysqli_query($con, $select_query);
                        if (mysqli_num_rows($result_query) == 0) {
                            echo "<tr><td colspan='3'><center><b>No FAQs found</b></center></td></tr>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result_query)) {
                                $faq_id = $row['faq_id'];
                                $question = $row['question'];
                                $answer = $row['answer'];
                                echo "<tr>";
                                echo "<td class='expandable'>$question</td>";
                                echo "<td class='expandable'><center>$answer</center></td>";
                                echo "<td>";
                                echo "<div class='button-class'>";
                                echo "<a href='emp_edit_faqs.php?faq_id=$faq_id' class='edit-button'><i class='las la-edit'></i></a>";
                                echo "<button type='button' onclick='openDeletePopup($faq_id)' class='delete-button'><i class='las la-trash'></i></button>";
                                echo "<form id='deleteForm_$faq_id' method='post'>";
                                echo "<input type='hidden' name='faq_id' value='$faq_id'>";
                                echo "<input type='hidden' name='delete_faqs' value='true'>";
                                echo "</form>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            $baseUrl = 'emp_faqs.php?';

            $pageQuery = '';
            if (isset($_GET['search_button'])) {
                $pageQuery = 'search_button&search=' . urlencode($_GET['search']);
            } elseif (isset($_GET['filter_button'])) {
                if (isset($_GET['category'])) {
                    $pageQuery = 'filter_button&category=' . urlencode($_GET['category']);
                } elseif (isset($_GET['artist'])) {
                    $pageQuery = 'filter_button&artist=' . urlencode($_GET['artist']);
                }
            }

            $pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            $totalPages = ceil($totalFaqs / $faqsPerPage);

            $startPage = max(1, $pageNumber - 1);
            $endPage = min($totalPages, $pageNumber + 1);

            if ($pageNumber == 1) {
                $startPage = 1;
                $endPage = min(3, $totalPages);
            } elseif ($pageNumber == $totalPages) {
                $startPage = max(1, $totalPages - 2);
                $endPage = $totalPages;
            }

            echo "<div class='pagination'>";

            $prevPage = max(1, $pageNumber - 1);
            echo "<a href='{$baseUrl}page=$prevPage&$pageQuery' class='pagination-link' " . ($pageNumber <= 1 ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">&laquo; Previous</a>";

            for ($i = $startPage; $i <= $endPage; $i++) {
                $linkClass = $i == $pageNumber ? 'pagination-link current-page' : 'pagination-link';
                echo "<a href='{$baseUrl}page=$i&$pageQuery' class='$linkClass'>$i</a>";
            }

            $nextPage = min($totalPages, $pageNumber + 1);
            echo "<a href='{$baseUrl}page=$nextPage&$pageQuery' class='pagination-link' " . ($pageNumber >= $totalPages ? "style='pointer-events: none; opacity: 0.5; cursor: not-allowed;'" : "") . ">Next &raquo;</a>";

            echo "</div>";
            ?>


            <div id="logoutConfirmationPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeLogoutPopup()">&times;</span>
                    <p>Are you sure you want to logout?</p>
                    <div class="logout-btns">
                        <button onclick="confirmLogout()" class="confirm-logout-btn">Logout</button>
                        <button onclick="closeLogoutPopup()" class="cancel-logout-btn">Cancel</button>
                    </div>
                </div>
            </div>

            <div id="deleteConfirmationPopup" class="popup-container" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeDeletePopup()">&times;</span>
                    <p>Are you sure you want to delete this FAQ?</p>
                    <div class="logout-btns">
                        <button onclick="confirmDelete(<?php echo $faq_id; ?>)" class="confirm-logout-btn">Delete</button>
                        <button onclick="closeDeletePopup()" class="cancel-logout-btn">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- <div class="form-outline mb-4 mt-5">
        <a href="./owner_dashboard.php" class="btn btn-danger mb-3 px-3 mx-auto">
            Back
        </a>
    </div> -->

            <script>
                // Function to toggle the sidebar and update session variable
                function toggleSidebar() {
                    var isChecked = document.getElementById('nav-toggle').checked;
                    var newState = isChecked ? 'true' : 'false';

                    // Update session variable using AJAX
                    var xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("nav_toggle=" + newState);
                }

                // Add event listener to checkbox change
                document.getElementById('nav-toggle').addEventListener('change', toggleSidebar);
            </script>
            <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>