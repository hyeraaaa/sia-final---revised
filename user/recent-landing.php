<?php
require_once '../login/dbh.inc.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit();
}

//Get info from admin session
$user = $_SESSION['user'];
$student_id = $_SESSION['user']['student_id'];
$user_id = $_SESSION['user']['student_id'];
$first_name = $_SESSION['user']['first_name'];
$last_name = $_SESSION['user']['last_name'];
$email = $_SESSION['user']['email'];
$contact_number = $_SESSION['user']['contact_number'];
$department_id = $_SESSION['user']['department_id'];
$profile_picture = $_SESSION['user']['profile_picture'];

$default = $user['profile_picture'] ?? 'default student.png';

if (isset($_GET['id'])) {
    $announcement_id = $_GET['id'];
    try {
        $query = "
            SELECT a.*, ad.first_name, ad.last_name, ad.profile_picture,
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
        WHERE a.announcement_id = :announcement_id
        GROUP BY a.announcement_id, ad.first_name, ad.last_name, ad.profile_picture";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':announcement_id', $announcement_id, PDO::PARAM_INT);
        $stmt->execute();

        $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($announcement) {
            $title = $announcement['title'];
            $description = $announcement['description'];
            $image = $announcement['image'];
            $admin_name = $announcement['first_name'] . ' ' . $announcement['last_name'];
            $updated_at = date('F d, Y', strtotime($announcement['updated_at']));
            $year_levels = explode(',', $announcement['year_levels']);
            $departments = explode(',', $announcement['departments']);
            $courses = explode(',', $announcement['courses']);
            $profile_picture = $announcement['profile_picture'];
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
        exit;
    }
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Recent Post</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include '../cdn/head.html'; ?>
    <link rel="stylesheet" href="../admin/css/admin.css">
    <link rel="stylesheet" href="../admin/css/modals.css">
    <link rel="stylesheet" href="../admin/css/sidebar.css">
    <link rel="stylesheet" href="../admin/css/feeds-card.css">
    <link rel="stylesheet" href="../admin/css/bsu-bg.css">
    <link rel="stylesheet" href="../admin/css/filter-modal.css">
    <link rel="stylesheet" href="../admin/css/nav-bottom.css">
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
                                <img src="<?php echo "uploads/" . htmlspecialchars($default); ?>" alt="Profile Picture" style="height: 40px; width: 40px; border-radius; 50%;">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mt-2 py-2 shadow-sm" style="width: 300px;">

                                <li>
                                    <div class="px-2 py-2 d-flex align-items-center">
                                        <img class="rounded-circle me-2" src="<?php echo 'uploads/' . htmlspecialchars($default); ?>" alt="Profile Picture" style="width: 40px; height: 40px; object-fit: cover;">
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

        <div class="container-fluid pt-5 parent">
            <div class="row g-4">
                <!-- Left sidebar -->
                <div class="col-lg-3 sidebar sidebar-left d-none d-lg-block">
                    <div class="sticky-sidebar">
                        <ul class="nav flex-column">

                            <li class="nav-item">
                                <a href="user.php"><i class="fas fa-newspaper me-2"></i>Feed</a>
                            </li>

                            <li class="nav-item">
                                <a href="features/logPage.php"><i class="fas fa-clipboard-list me-2"></i>Logs</a>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="col-12 col-xxl-9 col-lg-8 main-content pt-4">
                    <div class="row g-0" style="margin-top: 80px;">
                        <div class="col-xxl-8 col-lg-12 feed-container mt-4">
                            <div class="feed-container">
                                <div class="card mb-3">
                                    <div class="profile-container d-flex px-3 pt-3">
                                        <div class="profile-pic">
                                            <img src="<?php echo '../admin/uploads/' . htmlspecialchars($announcement['profile_picture']); ?>" alt="Profile Picture" style="width: 30px;height: 30px;object-fit: cover;border-radius: 50%">
                                        </div>
                                        <p class="ms-1 mt-1"><?php echo htmlspecialchars($admin_name); ?></p>
                                    </div>
                                    <?php if (!empty($row['image'])): ?>
                                        <div class="image-container mx-3">
                                            <div class="blur-background"></div>
                                            <a href="../admin/uploads/<?php echo htmlspecialchars($image); ?>" data-lightbox="image-<?php echo $announcement_id; ?>" data-title="<?php echo htmlspecialchars($title); ?>">
                                                <img src="../admin/uploads/<?php echo htmlspecialchars($image); ?>" alt="Post Image" class="img-fluid">
                                            </a>
                                            <script src="../admin/js/blur.js"></script>
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

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <?php include 'userLogout.php' ?>
        <nav class="navbar nav-bottom fixed-bottom d-block d-lg-none mt-5">
            <div class="container-fluid d-flex justify-content-around">
                <a href="user.php" class="btn nav-bottom-btn">
                    <i class="fas fa-newspaper"></i>
                </a>

                <a href="features/logPage.php" class="btn nav-bottom-btn">
                    <i class="fas fa-clipboard-list"></i>
                </a>
            </div>
        </nav>

        <?php include 'features/changePassMainPage.html' ?>
    </main>


    <script src="js/blur.js"></script>
    <script src="js/edit-profile.js"></script>

    <script src="js/admin.js"></script>

    <?php include '../cdn/body.html'; ?>
</body>

</html>