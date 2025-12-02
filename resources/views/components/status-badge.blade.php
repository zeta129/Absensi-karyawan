<div
    @class([
        'px-2 py-1 rounded text-xs font-semibold',
        'bg-green-100 text-green-800' => $status === 'present',
        'bg-yellow-100 text-yellow-800' => $status === 'late',
        'bg-red-100 text-red-800' => $status === 'absent',
        'bg-gray-100 text-gray-800' => !in_array($status, ['present', 'late', 'absent']),
    ])
>
    {{ ucfirst($status) }}
</div>
