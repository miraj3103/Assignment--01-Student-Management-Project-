<?php
require_once __DIR__ . '/StudentManager.php';
require_once __DIR__ . '/functions.php';

$manager = new StudentManager(__DIR__ . '/students.json');
$students = $manager->getAllStudents();

$success = isset($_GET['success']) ? trim($_GET['success']) : '';
$error = isset($_GET['error']) ? trim($_GET['error']) : '';
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: "Inter", sans-serif;
      }
    </style>
  </head>
  <body class="h-full">
    <div class="min-h-full flex flex-col">
      <nav class="bg-indigo-600 pb-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <span class="text-white font-bold text-xl tracking-tight"
                  >STUDENT.IO</span
                >
              </div>
            </div>
            <div class="hidden md:block">
              <div class="ml-4 flex items-center md:ml-6">
                <button
                  class="rounded-full bg-indigo-700 p-1 text-indigo-200 hover:text-white focus:outline-none"
                >
                  <span class="sr-only">View notifications</span>
                  <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <header class="py-10">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">
              Student List
            </h1>
          </div>
        </header>
      </nav>

      <main class="-mt-32">
        <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
          <div class="rounded-lg bg-white px-5 py-6 shadow sm:px-6">

            <?php if ($success !== ''): ?>
              <div class="mb-6 rounded-md bg-green-50 p-4 ring-1 ring-inset ring-green-600/20">
                <p class="text-sm text-green-800"><?php echo e($success); ?></p>
              </div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
              <div class="mb-6 rounded-md bg-red-50 p-4 ring-1 ring-inset ring-red-600/20">
                <p class="text-sm text-red-800"><?php echo e($error); ?></p>
              </div>
            <?php endif; ?>

            <div class="sm:flex sm:items-center mb-8">
              <div class="sm:flex-auto">
                <p class="mt-2 text-sm text-gray-700">
                  A list of all students currently enrolled including their name
                  and email address.
                </p>
              </div>
              <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a
                  href="create.php"
                  class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                  Add Student
                </a>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-300">
                <thead>
                  <tr>
                    <th
                      scope="col"
                      class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                    >
                      Email
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                    >
                      Phone
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                    >
                      Status
                    </th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                  <?php if (count($students) === 0): ?>
                    <tr>
                      <td colspan="5" class="py-8 text-center text-sm text-gray-500">
                        No students found. Click "Add Student" to create your first student.
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($students as $student): ?>
                      <?php
                        $status = (string)($student['status'] ?? 'Inactive');
                        [$bg, $text, $ring] = statusBadge($status);
                        $id = $student['id'] ?? '';
                      ?>
                      <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                          <?php echo e($student['name'] ?? ''); ?>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <?php echo e($student['email'] ?? ''); ?>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <?php echo e($student['phone'] ?? ''); ?>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <span class="inline-flex items-center rounded-md <?php echo e($bg); ?> px-2 py-1 text-xs font-medium <?php echo e($text); ?> ring-1 ring-inset <?php echo e($ring); ?>">
                            <?php echo e($status); ?>
                          </span>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                          <a href="edit.php?id=<?php echo urlencode((string)$id); ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>

                          <form action="delete.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            <input type="hidden" name="id" value="<?php echo e($id); ?>" />
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>

      <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <p class="text-center text-sm text-gray-500">
            &copy; 2025 Student Management System.
          </p>
        </div>
      </footer>
    </div>
  </body>
</html>
