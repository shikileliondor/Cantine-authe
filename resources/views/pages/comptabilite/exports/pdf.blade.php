<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export comptabilité</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { margin-bottom: 4px; }
        .meta { margin-bottom: 16px; color: #475569; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 6px; text-align: left; }
        th { background: #f1f5f9; }
    </style>
</head>
<body>
    <h1>Export comptabilité</h1>
    <p class="meta">Statut: {{ $statusLabel }} | Généré le {{ $generatedAt->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Élève</th>
                <th>Classe</th>
                <th>Total dû</th>
                <th>Versements</th>
                <th>Remises</th>
                <th>Reste</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['student']->last_name }} {{ $row['student']->first_name }}</td>
                    <td>{{ $row['student']->schoolClass->name }}</td>
                    <td>{{ number_format($row['totals']['expected_cents'] / 100, 2, ',', ' ') }}</td>
                    <td>{{ number_format($row['totals']['paid_cents'] / 100, 2, ',', ' ') }}</td>
                    <td>{{ number_format($row['totals']['discount_cents'] / 100, 2, ',', ' ') }}</td>
                    <td>{{ number_format($row['totals']['remaining_cents'] / 100, 2, ',', ' ') }}</td>
                    <td>{{ $row['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucune donnée pour ce filtre.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
