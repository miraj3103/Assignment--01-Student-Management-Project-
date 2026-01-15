<?php
require_once __DIR__ . '/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?error=' . urlencode('Invalid request method.'));
    exit;
}

$id = isset($_POST['id']) ? trim((string)$_POST['id']) : '';

if ($id === '') {
    header('Location: index.php?error=' . urlencode('Student ID is missing.'));
    exit;
}

$deleted = $manager->delete($id);

if ($deleted) {
    header('Location: index.php?success=' . urlencode('Student deleted successfully.'));
    exit;
}

header('Location: index.php?error=' . urlencode('Student not found.'));
exit;
