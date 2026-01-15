<?php

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function statusBadge(string $status): array
{
    
    switch ($status) {
        case 'Active':
            return ['bg-green-50', 'text-green-700', 'ring-green-600/20'];
        case 'On Leave':
            return ['bg-yellow-50', 'text-yellow-800', 'ring-yellow-600/20'];
        case 'Graduated':
            return ['bg-indigo-50', 'text-indigo-700', 'ring-indigo-600/20'];
        case 'Inactive':
        default:
            return ['bg-gray-50', 'text-gray-700', 'ring-gray-600/20'];
    }
}
