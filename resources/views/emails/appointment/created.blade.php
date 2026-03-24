<x-mail::message>
# Cita Programada Exitosamente

Hola **{{ $appointment->patient->user->name }}**,

Le informamos que su cita médica con el **Dr. {{ $appointment->doctor->user->name }}** ha sido programada exitosamente.

Adjunto a este correo encontrará su comprobante en formato PDF.

**Fecha de la cita:** {{ $appointment->date->format('d/m/Y') }}
**Hora:** {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
