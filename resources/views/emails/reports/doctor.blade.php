<x-mail::message>
# Reporte Diario de Citas

Hola **Dr. {{ $doctor->user->name }}**,

A continuación se muestra la lista de pacientes que tiene agendados para el día de hoy ({{ now()->format('d/m/Y') }}):

<x-mail::table>
| Hora | Paciente | Motivo |
|:-----|:---------|:-------|
@foreach($appointments as $appointment)
| {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} | {{ $appointment->patient->user->name }} | {{ $appointment->reason ?? 'No especificado' }} |
@endforeach
</x-mail::table>

Que tenga un excelente día,<br>
{{ config('app.name') }}
</x-mail::message>
