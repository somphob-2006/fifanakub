<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';

// Profile
$profile = null;
$result = mysqli_query($conn, "SELECT * FROM profile LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
    $profile = mysqli_fetch_assoc($result);
}

// Education
$educations = [];
$result = mysqli_query($conn, "SELECT * FROM education ORDER BY start_year DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $educations[] = $row;
    }
}

// Skills & Training (ขึ้นกับ profile)
$skills = [];
$trainings = [];
if ($profile) {
    $profile_id = intval($profile['id']);

    $result = mysqli_query($conn, "SELECT * FROM skill WHERE profile_id = $profile_id");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $skills[] = $row;
        }
    }

    $result = mysqli_query($conn, "SELECT * FROM training WHERE profile_id = $profile_id ORDER BY training_year DESC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $trainings[] = $row;
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $profile ? htmlspecialchars($profile['full_name']) : 'My Resume'; ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css?v=3">
</head>
<body>
<div class="container">

    <header class="hero card">
        <img class="avatar" src="assets/images/<?php echo $profile ? htmlspecialchars($profile['photo']) : 'eiei.jpg'; ?>" alt="Profile Photo">
        <div>
            <h1><?php echo $profile ? htmlspecialchars($profile['full_name']) : 'Your Name'; ?></h1>
            <p class="headline"><?php echo $profile ? htmlspecialchars($profile['headline']) : 'Student Resume Website'; ?></p>
            <?php if ($profile): ?>
                <div class="contact">
                    <p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($profile['email']); ?></p>
                    <p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($profile['phone']); ?></p>
                    <p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile['address']); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <section class="card">
        <h2><i class="bi bi-person"></i> About Me</h2>
        <p><?php echo $profile ? nl2br(htmlspecialchars($profile['about_me'])) : 'Add your profile data in phpMyAdmin.'; ?></p>
    </section>

    <section class="card">
        <h2><i class="bi bi-mortarboard"></i> Education</h2>
        <?php if (count($educations) > 0): ?>
            <?php foreach ($educations as $edu): ?>
                <div class="item">
                    <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                    <p><?php echo htmlspecialchars($edu['institution']); ?></p>
                    <p><?php echo htmlspecialchars($edu['start_year']); ?> - <?php echo htmlspecialchars($edu['end_year']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No education data found.</p>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2><i class="bi bi-lightning-charge"></i> Skills</h2>
        <div class="skills">
            <?php if (count($skills) > 0): ?>
                <?php foreach ($skills as $skill): ?>
                    <div class="skill-badge">
                        <strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong>
                        <span><?php echo htmlspecialchars($skill['skill_level']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No skill data found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="card">
        <h2><i class="bi bi-journal-text"></i> Training / Certificates</h2>
        <?php if (count($trainings) > 0): ?>
            <?php foreach ($trainings as $training): ?>
                <div class="item">
                    <h3><?php echo htmlspecialchars($training['course_name']); ?></h3>
                    <p><?php echo htmlspecialchars($training['organization']); ?>, <?php echo htmlspecialchars($training['training_year']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No training data found.</p>
        <?php endif; ?>
    </section>

    <footer>
        Database Lab: Personal Resume Website with PHP + MySQL
    </footer>
</div>
</body>
</html>
<?php mysqli_close($conn); ?>