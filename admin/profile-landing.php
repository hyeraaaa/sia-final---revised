<?php
require_once '../login/dbh.inc.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION = [];
    session_destroy();
    header("Location: ../login/login.php");
    exit();
}

$viewed_admin_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user']['admin_id'];

$user = $_SESSION['user'];
$admin_id = $user['admin_id'];
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];
$contact_number = $user['contact_number'];
$department_id = $user['department_id'];
$profile_picture = $user['profile_picture'];

$query = "SELECT cover_photo, profile_picture FROM admin WHERE admin_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$admin_id]);
$adminPhotos = $stmt->fetch(PDO::FETCH_ASSOC);

$cover_photo = $adminPhotos['cover_photo'] ?? 'default_cover.jpg';

?>

<!doctype html>
<html lang="en">

<head>
    <title>Admin Posts</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- head CDN links -->
    <?php include '../cdn/head.html'; ?>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/modals.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/feeds-card.css">
    <link rel="stylesheet" href="css/bsu-bg.css">
    <link rel="stylesheet" href="css/cover-photo.css">
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
    </header>
    <main>
        <div class="container-fluid pt-5">
            <div class="row g-4">
                <!-- left sidebar -->
                <div class="col-lg-3 sidebar sidebar-left d-none d-lg-block">
                    <div class="sticky-sidebar">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="features/dashboard.php"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a href="admin.php"><i class="fas fa-newspaper me-2"></i>Feed</a>
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

                <!-- main content -->
                <div class="col-12 col-xxl-9 col-lg-8 main-content pt-4 px-4">
                    <div class="row g-0">
                        <?php
                        try {
                            $query = "SELECT admin_id, first_name, last_name, profile_picture, cover_photo, bio FROM admin WHERE admin_id = :admin_id";

                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':admin_id', $viewed_admin_id, PDO::PARAM_INT);
                            $stmt->execute();

                            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($admin) {
                                $admin_id = $admin['admin_id'];
                                $admin_name = $admin['first_name'] . " " . $admin['last_name'];
                                $admin_profile_picture = $admin['profile_picture'];
                                $admin_cp = $admin['cover_photo'];
                                $admin_bio = $admin['bio'];
                            } else {
                                echo "<!doctype html>
                                        <html lang='en'>
                                        <head>
                                            <title>Content Not Available</title>
                                            <meta charset='utf-8' />
                                            <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no' />
                                            <link rel='stylesheet' href='css/admin.css'>
                                        </head>
                                        <body>
                                            <div style='text-align: center; margin-top: 50px;'>
                                                <h1>Content is not available right now.</h1>
                                                <img src='img/gojo-satoru.gif' alt='Content Not Available' style='max-width: 100px; height: auto;'>
                                            </div>
                                        </body>
                                        </html>";
                                exit();
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>

                        <!-- Desktop Layout -->
                        <div class="col-12 col-xxl-12 cover desktop-layout">
                            <div class="cover-photo-container" style="position: relative;">
                                <a href="<?php echo 'uploads/' . htmlspecialchars($admin_cp); ?>" data-lightbox="cover" data-title="Cover Photo">
                                    <img src="<?php echo 'uploads/' . htmlspecialchars($admin_cp); ?>" alt="Cover Photo">
                                </a>
                            </div>
                            <div class="profile-section">
                                <div class="profile-photo-container" style="position: relative;">
                                    <a href="<?php echo 'uploads/' . htmlspecialchars($admin_profile_picture); ?>" data-lightbox="profile" data-title="Profile Photo">
                                        <img src="<?php echo 'uploads/' . htmlspecialchars($admin_profile_picture); ?>" alt="Profile Photo">
                                    </a>
                                </div>
                                <div class="username-container">
                                    <h5 class="name"><?php echo htmlspecialchars($admin_name); ?></h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mobile-layout">
                            <div class="cover-photo-container">
                                <img src="<?php echo 'uploads/' . htmlspecialchars($admin_cp); ?>" alt="">
                            </div>
                            <div class="profile-section">
                                <div class="profile-photo-container">
                                    <img src="<?php echo 'uploads/' . htmlspecialchars($admin_profile_picture); ?>" alt="">
                                </div>
                                <div class="username-container">
                                    <h5 class="name"><?php echo htmlspecialchars($admin_name); ?></h5>

                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-7 col-lg-12 feed-container">
                            <?php
                            try {
                                $query = "
                                SELECT a.*, ad.first_name, ad.last_name,
                                    STRING_AGG(DISTINCT yl.year_level, ', ') AS year_levels,
                                    STRING_AGG(DISTINCT d.department_name, ', ') AS departments,
                                    STRING_AGG(DISTINCT c.course_name, ', ') AS courses
                                FROM announcement a
                                JOIN admin ad ON a.admin_id = ad.admin_id
                                LEFT JOIN announcement_year_level ayl ON a.announcement_id = ayl.announcement_id
                                LEFT JOIN year_level yl ON ayl.year_level_id = yl.year_level_id
                                LEFT JOIN announcement_department adp ON a.announcement_id = adp.announcement_id
                                LEFT JOIN department d ON adp.department_id = d.department_id
                                LEFT JOIN announcement_course ac ON a.announcement_id = ac.announcement_id
                                LEFT JOIN course c ON ac.course_id = c.course_id 
                                WHERE a.admin_id = :admin_id
                                GROUP BY a.announcement_id, ad.first_name, ad.last_name 
                                ORDER BY a.updated_at DESC";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
                                $stmt->execute();

                                $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($announcements) {
                                    foreach ($announcements as $row) {
                                        $announcement_id = $row['announcement_id'];
                                        $title = $row['title'];
                                        $description = $row['description'];
                                        $image = $row['image'];
                                        $announcement_admin_id = $row['admin_id'];
                                        $admin_first_name = $row['first_name'];
                                        $admin_last_name = $row['last_name'];
                                        $admin_name =  $admin_first_name . ' ' . $admin_last_name;
                                        $updated_at = date('F d, Y', strtotime($row['updated_at']));

                                        $year_levels = !empty($row['year_levels']) ? explode(',', $row['year_levels']) : [''];
                                        $departments = !empty($row['departments']) ? explode(',', $row['departments']) : [''];
                                        $courses = !empty($row['courses']) ? explode(',', $row['courses']) : [''];
                            ?>


                                        <div class="card mb-3">
                                            <div class="profile-container d-flex px-3 pt-3">
                                                <div class="profile-pic">
                                                    <img src="<?php echo 'uploads/' . htmlspecialchars($admin_profile_picture); ?>" alt="Profile Picture" style="width: 30px;height: 30px;object-fit: cover;border-radius: 50%;">
                                                </div>
                                                <p class="ms-1 mt-1"><?php echo htmlspecialchars($admin_name); ?></p>
                                                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin'): ?>
                                                    <div class="dropdown ms-auto">
                                                        <span id="dropdownMenuButton<?php echo $announcement_id; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </span>
                                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?php echo $announcement_id; ?>">
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deletePost"
                                                                    data-announcement-id="<?php echo $announcement_id; ?>">Delete</a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <?php if (!empty($row['image'])): ?>
                                                <div class="image-container mx-3" style="position: relative; overflow: hidden;">
                                                    <div class="blur-background"></div>
                                                    <a href="uploads/<?php echo htmlspecialchars($row['image']); ?>" data-lightbox="image-<?php echo $row['announcement_id']; ?>" data-title="<?php echo htmlspecialchars($row['title']); ?>">
                                                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image" class="img-fluid">
                                                        <script src="js/blur.js"></script>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                                <div class="card-text">
                                                    <p class="mb-2"><?php echo htmlspecialchars($description); ?></p>

                                                    Tags:
                                                    <?php

                                                    $all_tags = array_merge($year_levels, $departments, $courses);

                                                    foreach ($all_tags as $tag) : ?>
                                                        <span class="badge rounded-pill bg-danger mb-2"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                                    <?php endforeach; ?>
                                                </div>

                                                <small>Updated at <?php echo htmlspecialchars($updated_at); ?></small>
                                            </div>
                                        </div>

                            <?php
                                    }
                                } else {
                                    echo '<p class="text-center">No announcements found.</p>';
                                }
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                            ?>

                        </div>
                        <div class="col-lg-5 info-card d-none d-xxl-block">
                            <div class="sticky-card m-0 w-100">
                                <div class="card card-info p-4">
                                    <div class="left-card">
                                        <div class="d-flex flex-column">
                                            <p class="text-center"><?php echo htmlspecialchars($admin_bio); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav class="navbar nav-bottom fixed-bottom d-block d-lg-none mt-5">
            <div class="container-fluid d-flex justify-content-around">
                <a href="features/dashboard.php" class="btn nav-bottom-btn">
                    <i class="fas fa-chart-line"></i>
                </a>
                <a href="admin.php" class="btn nav-bottom-btn">
                    <i class="fas fa-newspaper"></i>
                </a>
                <a href="features/create.php" class="btn nav-bottom-btn">
                    <i class="fas fa-bullhorn"></i>
                </a>
                <a href="features/logPage.php" class="btn nav-bottom-btn">
                    <i class="fas fa-clipboard-list"></i>
                </a>
                <a href="features/manage_student.php" class="btn nav-bottom-btn">
                    <i class="fas fa-users-cog"></i>
                </a>
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin'): ?>
                    <a href="features/manage_admin.php" class="btn nav-bottom-btn">
                        <i class="fas fa-user-shield"></i>
                    </a>
                <?php endif; ?>
                <a href="features/manage.php" class="btn nav-bottom-btn">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </nav>
    </main>

    <!-- Logout Modal -->
    <?php include 'logoutmodaloutside.php' ?>

    <!-- Body CDN links -->
    <?php include '..//cdn/body.html'; ?>
    <script src="js/admin.js"></script>
    <script src="js/manage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>