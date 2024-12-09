<?php
require_once '../login/dbh.inc.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION = [];
    session_destroy();
    header("Location: ../login/login.php");
    exit();
}

//Get info from admin session
$user = $_SESSION['user'];
$admin_id = $_SESSION['user']['admin_id'];
$first_name = $_SESSION['user']['first_name'];
$last_name = $_SESSION['user']['last_name'];
$email = $_SESSION['user']['email'];
$contact_number = $_SESSION['user']['contact_number'];
$department_id = $_SESSION['user']['department_id'];
$profile_picture = $_SESSION['user']['profile_picture'];

$selected_departments = [];
$selected_year_levels = [];
$selected_courses = [];

?>


<!DOCTYPE html>
<html lang="en">


<head>
    <title>ISMS Portal</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include '../cdn/head.html'; ?>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/modals.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/feeds-card.css">
    <link rel="stylesheet" href="css/bsu-bg.css">
    <link rel="stylesheet" href="css/filter-modal.css">
    <link rel="stylesheet" href="css/nav-bottom.css">
    <link rel="icon" href="img/brand.png" type="image/x-icon">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white text-black fixed-top mb-5" style="border-bottom: 1px solid #e9ecef; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
            <div class="container-fluid">
                <div class="user-left d-flex">
                    <a class="navbar-brand d-flex align-items-center" href="#"><img src="img/brand.png" class="img-fluid branding" alt=""></a>
                </div>

                <div class="search-container d-none d-lg-block">
                    <form id="searchForm" class="d-flex align-items-center gap-2">
                        <!-- Filter Button -->
                        <button type="button" class="btn btn-light filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel"></i>
                        </button>

                        <!-- Search Input Group -->
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text"
                                name="search"
                                class="form-control border-start-0 ps-0"
                                placeholder="Search announcements..."
                                aria-label="Search announcements">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-search me-2 d-none d-md-inline"></i>Search
                            </button>
                        </div>
                    </form>
                </div>

                <div class="user-right d-flex align-items-center justify-content-center">
                    <p class="username d-flex align-items-center m-0 me-2"><?php echo $first_name ?></p>
                    <div class="user-profile">
                        <div class="dropdown">

                            <button class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="border: none; background: none; padding: 0;">
                                <img src="<?php echo "uploads/" . htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 40px;height: 40px;object-fit: cover;border-radius: 50%">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mt-2 py-2 shadow-sm">

                                <li>
                                    <div class="px-2 py-2 d-flex align-items-center">
                                        <img class="rounded-circle me-2" src="<?php echo 'uploads/' . htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <p class="mb-0 small"><?php echo htmlspecialchars($first_name . " " . $last_name); ?></p>
                                            <p class="mb-0 small text-muted"><?php echo htmlspecialchars($email); ?></p>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bi bi-key me-2"></i>
                                        Change Password
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="#" data-bs-toggle="modal" data-bs-target="#changeProfilePictureModal">
                                        <i class="bi bi-person-circle me-2"></i>
                                        Change Profile Picture
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="#" onclick="return confirmLogout()">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        </nav>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
    </header>

    <main>
        <div class="container-fluid pt-5 parent">
            <div class="row g-4">
                <!-- Left sidebar -->
                <div class="col-lg-3 sidebar sidebar-left d-none d-lg-block">
                    <div class="sticky-sidebar">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="features/dashboard.php"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
                            </li>

                            <li class="nav-item">
                                <a class="active" href="admin.php"><i class="fas fa-newspaper me-2"></i>Feed</a>
                            </li>

                            <li class="nav-item">
                                <a href="features/manage.php"><i class="fas fa-user me-2"></i>My Profile</a>
                            </li>

                            <li class="nav-item">
                                <a href="features/create.php"><i class="fas fa-bullhorn me-2"></i>Create Announcement</a>
                            </li>

                            <li class="nav-item">
                                <a href="features/logPage.php"><i class="fas fa-clipboard-list me-2"></i>Logs</a>
                            </li>

                            <li class="nav-item">
                                <a href="features/manage_student.php"><i class="fas fa-users-cog me-2"></i>Manage Accounts</a>
                            </li>

                            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin'): ?>
                                <li class="nav-item">
                                    <a href="features/manage_admin.php"><i class="fas fa-user-shield me-2"></i>Manage Admins</a>
                                </li>
                                <li class="nav-item">
                                    <a href="features/feedbackPage.php">
                                        <i class="fas fa-comments me-2"></i>
                                        <span class="menu-text">Feedback</span>
                                    </a>
                                </li>
                            <?php endif; ?>


                        </ul>
                    </div>
                </div>
                <!-- Main content -->
                <div class="col-12 col-xxl-9 col-lg-8 main-content pt-4">
                    <div class="row g-0">
                        <div class="col-xxl-7 col-lg-12 feed-container mt-4">
                            <div id="loading" style="display: none;" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <?php include 'filter_announcements.php'; ?>
                        </div>

                        <div class="col-lg-5 announcement-card d-none d-xxl-block">
                            <?php
                            require_once '../login/dbh.inc.php';

                            try {
                                $query = "SELECT a.*, b.first_name, b.last_name,  b.profile_picture
                                FROM announcement a 
                                JOIN admin b ON a.admin_id = b.admin_id 
                                ORDER BY a.updated_at DESC 
                                LIMIT 3";


                                $stmt = $pdo->prepare($query);
                                $stmt->execute();

                                $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                                <div class="sticky-recent-post mt-3">
                                    <div class="filter">
                                        <div class="card latest-card p-2">
                                            <div class="card-body">
                                                <p class="card-title mb-3">RECENT POSTS</p>
                                                <div class="posts">
                                                    <?php
                                                    if ($announcements) {
                                                        $announcementCount = count($announcements);
                                                        foreach ($announcements as $index => $row) {
                                                            $id = $row['announcement_id'];
                                                            $title = $row['title'];
                                                            $image = $row['image'];
                                                            $admin_first_name = $row['first_name'];
                                                            $admin_last_name = $row['last_name'];
                                                            $admin_pp = $row['profile_picture'];
                                                            $admin_name =  $admin_first_name . ' ' . $admin_last_name;
                                                    ?>
                                                            <div class="d-flex flex-column recent mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-8 recent-profile-container">
                                                                        <div class="recent-container d-flex">
                                                                            <a href="profile-landing.php?id=<?php echo htmlspecialchars($row['admin_id']); ?>">
                                                                                <img class="profile-picture" src="<?php echo "uploads/" . htmlspecialchars($admin_pp); ?>" alt="Profile Picture" style="width: 30px;height: 30px;object-fit: cover;border-radius: 50%">
                                                                            </a>
                                                                            <a href="profile-landing.php?id=<?php echo htmlspecialchars($row['admin_id']); ?>" style="text-decoration: none; color:black">
                                                                                <p class=" mt-1 ms-2"><?php echo htmlspecialchars($admin_name) ?></p>
                                                                            </a>
                                                                        </div>
                                                                        <div class="title-container mt-0">
                                                                            <a style="color:black; text-decoration: none;" href="recent-landing.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a>
                                                                        </div>
                                                                    </div>
                                                                    <?php if (!empty($row['image'])): ?>
                                                                        <div class="col-md-4 post-img">
                                                                            <div class="post-img-container">
                                                                                <img class="post-image" src="uploads/<?php echo htmlspecialchars($image); ?>" alt="Post Image" class="img-fluid">
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                    <?php
                                                            if ($index < $announcementCount - 1) {
                                                                echo '<hr>';
                                                            }
                                                        }
                                                    } else {
                                                        echo '<p class="text-center">No announcements found.</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } catch (PDOException $e) {
                                echo "Error: " . htmlspecialchars($e->getMessage());
                            }
                            ?>

                        </div>
                        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] !== 'superadmin'): ?>
                            <div class="col-xxl-12 w-100 d-flex justify-content-end send-feedback">
                                <div class="img-container">
                                    <img class="customer-icon" src="img/headset.png" alt="" onclick="toggleFeedbackForm()">
                                </div>

                                <div class="card shadow feedback-form" id="feedbackForm">
                                    <div class="form-header mb-4">
                                        <h5 class="fw-bold">Send Feedback</h5>
                                        <button type="button" class="btn-close" onclick="toggleFeedbackForm()"></button>
                                    </div>

                                    <form id="customerFeedback">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="name" placeholder="Name">
                                            <label for="name">Name</label>
                                            <div class="error-message" id="nameError" style="color: red; display: none;"></div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="email" placeholder="name@example.com">
                                            <label for="email">Email address</label>
                                            <div class="error-message" id="emailError" style="color: red; display: none;"></div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" id="message" placeholder="Leave a message here" style="height: 100px"></textarea>
                                            <label for="message">Message</label>
                                            <div class="error-message" id="messageError" style="color: red; display: none;"></div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-danger">Send Message</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif ?>

                        <script>
                            function toggleFeedbackForm() {
                                const form = document.getElementById('feedbackForm');
                                const feedbackIcon = document.querySelector('.customer-icon');

                                form.classList.toggle('active');
                                feedbackIcon.style.display = form.classList.contains('active') ? 'none' : 'block';
                            }

                            function closeFeedbackForm() {
                                const form = document.getElementById('feedbackForm');
                                const feedbackIcon = document.querySelector('.customer-icon');

                                form.classList.remove('active');
                                feedbackIcon.style.display = 'block';
                            }

                            document.addEventListener('keydown', function(event) {
                                if (event.key === 'Escape') {
                                    closeFeedbackForm();
                                }
                            });

                            $(document).ready(function() {
                                $('#customerFeedback').on('submit', function(event) {
                                    event.preventDefault(); // Prevent the default form submission

                                    // Get the values of the input fields
                                    const name = $('#name').val().trim();
                                    const email = $('#email').val().trim();
                                    const message = $('#message').val().trim();

                                    $('.error-message').hide();

                                    let isValid = true;


                                    if (name === '') {
                                        $('#nameError').text('Please enter your name.').show();
                                        $('#name').focus();
                                        isValid = false;
                                    }


                                    if (email === '') {
                                        $('#emailError').text('Please enter your email address.').show();
                                        $('#email').focus();
                                        isValid = false;
                                    }


                                    if (message === '') {
                                        $('#messageError').text('Please enter your message.').show();
                                        $('#message').focus();
                                        isValid = false;
                                    }

                                    if (isValid) {
                                        console.log('Name:', name);
                                        console.log('Email:', email);
                                        console.log('Message:', message);
                                        $('#customerFeedback')[0].reset();
                                    }
                                });
                            });
                        </script>

                    </div>


                </div>


            </div>
        </div>


        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="filterModalLabel">
                            <i class="bi bi-funnel-fill me-2"></i>Announcements Filter
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="filtered_option" id="filterForm">
                            <!-- Department Section -->
                            <div class="filter-section">
                                <h6 class="filter-title">Choose Department</h6>
                                <div class="filter-options">
                                    <label class="filter-chip">
                                        <input type="checkbox" name="department_filter[]" value="CICS">
                                        <span>CICS</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="department_filter[]" value="CABE">
                                        <span>CABE</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="department_filter[]" value="CAS">
                                        <span>CAS</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="department_filter[]" value="CIT">
                                        <span>CIT</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="department_filter[]" value="CTE">
                                        <span>CTE</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="department_filter[]" value="CE">
                                        <span>CE</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Year Level Section -->
                            <div class="filter-section">
                                <h6 class="filter-title"> Select Year Level</h6>
                                <div class="filter-options">
                                    <label class="filter-chip">
                                        <input type="checkbox" name="year_level[]" value="1st Year">
                                        <span>1st Year</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="year_level[]" value="2nd Year">
                                        <span>2nd Year</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="year_level[]" value="3rd Year">
                                        <span>3rd Year</span>
                                    </label>
                                    <label class="filter-chip">
                                        <input type="checkbox" name="year_level[]" value="4th Year">
                                        <span>4th Year</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Courses Section -->
                            <div class="filter-section">
                                <h6 class="filter-title">Courses</h6>
                                <div class="filter-options">
                                    <label class="filter-chip" title="Bachelor of Science in Business Accounting">
                                        <input type="checkbox" name="course[]" value="BSBA" <?php if (in_array('BSBA', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSBA</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Science in Management Accounting">
                                        <input type="checkbox" name="course[]" value="BSMA" <?php if (in_array('BSMA', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSMA</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Science in Psychology">
                                        <input type="checkbox" name="course[]" value="BSP" <?php if (in_array('BSP', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSP</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Arts in Communication">
                                        <input type="checkbox" name="course[]" value="BAC" <?php if (in_array('BAC', $selected_courses)) echo 'checked'; ?>>
                                        <span>BAC</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Science in Industrial Engineering">
                                        <input type="checkbox" name="course[]" value="BSIE" <?php if (in_array('BSIE', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSIE</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Industrial Technology - Computer Technology">
                                        <input type="checkbox" name="course[]" value="BSIT-CE" <?php if (in_array('BSIT-CE', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSIT-CE</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Industrial Technology - Electrical Technology">
                                        <input type="checkbox" name="course[]" value="BSIT-Electrical" <?php if (in_array('BSIT-Electrical', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSIT-E</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Industrial Technology - Electronics Technology">
                                        <input type="checkbox" name="course[]" value="BSIT-Electronic" <?php if (in_array('BSIT-Electronic', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSIT-ET</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Industrial Technology - Instrumentation and Control Technology">
                                        <input type="checkbox" name="course[]" value="BSIT-ICT" <?php if (in_array('BSIT-ICT', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSIT-ICT</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Science in Information Technology">
                                        <input type="checkbox" name="course[]" value="BSIT" <?php if (in_array('BSIT', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSIT</span>
                                    </label>
                                    <label class="filter-chip" title="Bachelor of Secondary Education">
                                        <input type="checkbox" name="course[]" value="BSE" <?php if (in_array('BSE', $selected_courses)) echo 'checked'; ?>>
                                        <span>BSE</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" form="filterForm">
                            <i class="bi bi-x-circle me-2"></i>Clear Filters
                        </button>
                        <button type="submit" class="btn btn-danger" form="filterForm">
                            <i class="bi bi-check2-circle me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php require 'features/changePassMainPage.html'; ?>

        <nav class="navbar nav-bottom fixed-bottom d-block d-lg-none mt-5">
            <div class="container-fluid d-flex justify-content-around">
                <a href="../admin/features/dashboard.php" class="btn nav-bottom-btn">
                    <i class="fas fa-chart-line"></i>
                </a>

                <a href="admin.php" class="btn nav-bottom-btn active-btn">
                    <i class="fas fa-newspaper"></i>
                </a>

                <a href="../admin/features/create.php" class="btn nav-bottom-btn">
                    <i class="fas fa-bullhorn"></i>
                </a>

                <a href="../admin/features/logPage.php" class="btn nav-bottom-btn">
                    <i class="fas fa-clipboard-list"></i>
                </a>

                <a href="../admin/features/manage_student.php" class="btn nav-bottom-btn">
                    <i class="fas fa-users-cog"></i>
                </a>

                <a href="../admin/features/manage.php" class="btn nav-bottom-btn">
                    <i class="fas fa-user"></i>
                </a>

                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin'): ?>
                    <a href="features/manage_admin.php" class="btn nav-bottom-btn">
                        <i class="fas fa-user-shield"></i>
                    </a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content custom" style="border-radius: 15px;">
                    <div class="modal-header pb-1" style="border: none;">
                        <h1 class="modal-title fs-5" id="messageModalLabel">Notification</h1>
                        <button type="button" class="btn-close delete-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0 logout-modal" style="border: none;">
                        <p style="font-size: 15px;" id="modalMessageBody">
                        </p>
                    </div>
                    <div class="modal-footer pt-0" style="border: none;">
                        <button type="button" class="btn btn-danger" id="modalConfirmButton" style="border-radius: 25px;">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Logout Modal -->
    <?php include 'logoutmodaloutside.php' ?>

    <script src="js/success.js"></script>
    <script src="js/blur.js"></script>
    <script src="js/edit-profile.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('#filterForm');
            const searchForm = document.querySelector('#searchForm');
            const searchInput = searchForm.querySelector('input[name="search"]');
            const loadingIndicator = document.getElementById('loading');
            const feedContainer = document.querySelector('.feed-container');

            function fetchAnnouncements(form) {
                loadingIndicator.style.display = 'block';
                feedContainer.style.opacity = '0.5';

                const formData = new FormData(form);

                fetch('filter_announcements.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        feedContainer.innerHTML = data;
                        feedContainer.style.opacity = '1';
                        loadingIndicator.style.display = 'none';

                        applyBlurBackground();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingIndicator.style.display = 'none';
                        feedContainer.style.opacity = '1';
                    });
            }

            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchAnnouncements(this);
            });

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchAnnouncements(this);
            });

            filterForm.addEventListener('reset', function(e) {
                setTimeout(() => {
                    searchInput.value = '';
                    fetchAnnouncements(filterForm);
                }, 10);
            });
        });

        document.getElementById('customerFeedback').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                message: document.getElementById('message').value
            };

            fetch('features/handle_feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modalMessageBody');
                    if (data.status === 'success') {
                        modalBody.textContent = 'Thank you for your feedback!';
                        document.getElementById('customerFeedback').reset();
                        closeFeedbackForm();
                    } else {
                        modalBody.textContent = data.message || 'An error occurred';
                    }
                    const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                    messageModal.show();

                    setTimeout(() => {
                        messageModal.hide();
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalBody = document.getElementById('modalMessageBody');
                    modalBody.textContent = 'An error occurred while submitting feedback';
                    const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                    messageModal.show();

                    setTimeout(() => {
                        messageModal.hide();
                    }, 3000);
                });
            document.getElementById('modalConfirmButton').addEventListener('click', function() {
                const messageModal = bootstrap.Modal.getInstance(document.getElementById('messageModal'));
                messageModal.hide();
            });
        });
    </script>


    <script src="js/admin.js"></script>

    <?php include '../cdn/body.html'; ?>
</body>

</html>