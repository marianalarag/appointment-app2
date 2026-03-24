<x-mail::message>
# Reporte General de Citas

Hola **{{ $admin->name }}**,

A continuación se muestra el resumen de todas las citas médicas agendadas para el día de hoy ({{ now()->format('d/m/Y') }}):

<x-mail::table>
| Hora | Doctor | Paciente |
|:-----|:-------|:---------|
@foreach($appointments as $appointment)
| {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} | Dr. {{ $appointment->doctor->user->name }} | {{ $appointment->patient->user->name }} |
@endforeach
</x-mail::table>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
