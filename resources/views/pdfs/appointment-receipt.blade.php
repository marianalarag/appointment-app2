<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #333; padding-bottom: 1rem; }
        .details { margin: 0 auto; width: 80%; border-collapse: collapse; }
        .details th, .details td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        .details th { background-color: #f7f7f7; width: 40%; }
        .footer { text-align: center; margin-top: 3rem; font-size: 0.9em; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 {{ config('app.name', 'Clinica Médica') }}</h1>
        <h2>Comprobante de Cita Médica</h2>
    </div>

    <table class="details">
        <tbody>
            <tr>
                <th>Paciente</th>
                <td>{{ $appointment->patient->user->name }}</td>
            </tr>
            <tr>
                <th>Doctor</th>
                <td>Dr. {{ $appointment->doctor->user->name }} (Esp. {{ $appointment->doctor->specialty->name }})</td>
            </tr>
            <tr>
                <th>Fecha de la Cita</th>
                <td>{{ $appointment->date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Hora Programada</th>
                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>Duración Estimada</th>
                <td>{{ $appointment->duration }} minutos</td>
            </tr>
            <tr>
                <th>Motivo de la Consulta</th>
                <td>{{ $appointment->reason ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Estado</th>
                <td>{{ $appointment->status_label }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Este documento es un comprobante válido para su cita. Por favor, asista 15 minutos antes de su horario programado.
    </div>
</body>
</html>
