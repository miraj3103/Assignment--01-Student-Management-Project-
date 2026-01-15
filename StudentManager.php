<?php

class StudentManager
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/students.json')
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT), LOCK_EX);
        }
    }


    public function getAllStudents(): array
    {
        return $this->readStudents();
    }

    public function getStudentById($id): ?array
    {
        $students = $this->readStudents();
        foreach ($students as $student) {
            if ((string)($student['id'] ?? '') === (string)$id) {
                return $student;
            }
        }
        return null;
    }


    public function create($data): array
    {
        $students = $this->readStudents();

        $clean = $this->sanitizeAndValidate($data);

        
        $clean['id'] = $this->generateNextId($students);

        if ($this->idExists($students, $clean['id'])) {
            throw new Exception('Duplicate ID detected. Please try again.');
        }

        $students[] = $clean;
        $this->saveStudents($students);

        return $clean;
    }


    public function update($id, $data): ?array
    {
        $students = $this->readStudents();
        $found = false;

        $clean = $this->sanitizeAndValidate($data);

        foreach ($students as $i => $student) {
            if ((string)($student['id'] ?? '') === (string)$id) {
                $clean['id'] = $student['id'];
                $students[$i] = $clean;
                $found = true;
                break;
            }
        }

        if (!$found) {
            return null;
        }

        $this->saveStudents($students);
        return $clean;
    }

    public function delete($id): bool
    {
        $students = $this->readStudents();
        $before = count($students);

        $students = array_values(array_filter($students, function ($s) use ($id) {
            return (string)($s['id'] ?? '') !== (string)$id;
        }));

        $after = count($students);

        if ($after === $before) {
            return false;
        }

        $this->saveStudents($students);
        return true;
    }

    private function readStudents(): array
    {
        $json = @file_get_contents($this->filePath);
        if ($json === false) {
            return [];
        }

        $data = json_decode($json, true);
        if (!is_array($data)) {
            return [];
        }

        $students = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $students[] = $item;
            }
        }

        return $students;
    }

    private function saveStudents(array $students): void
    {
        $json = json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $json = '[]';
        }

        file_put_contents($this->filePath, $json, LOCK_EX);
    }

    private function sanitizeAndValidate($data): array
    {
        $name = trim((string)($data['name'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $phone = trim((string)($data['phone'] ?? ''));
        $status = trim((string)($data['status'] ?? ''));

        if ($name === '' || $email === '' || $phone === '' || $status === '') {
            throw new Exception('All fields are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        $allowedStatus = ['Active', 'On Leave', 'Graduated', 'Inactive'];
        if (!in_array($status, $allowedStatus, true)) {
            throw new Exception('Invalid status selected.');
        }

        return [
            'id' => null,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
        ];
    }

    private function generateNextId(array $students): int
    {
        $max = 0;
        foreach ($students as $s) {
            $id = $s['id'] ?? null;
            if (is_numeric($id)) {
                $max = max($max, (int)$id);
            }
        }
        return $max + 1;
    }

    private function idExists(array $students, $id): bool
    {
        foreach ($students as $s) {
            if ((string)($s['id'] ?? '') === (string)$id) {
                return true;
            }
        }
        return false;
    }
}
