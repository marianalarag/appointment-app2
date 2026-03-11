# Módulo de Citas Médicas - Documentación

## Descripción General

Este módulo implementa un sistema completo de CRUD para citas médicas con gestión inteligente de disponibilidad de horarios. El sistema valida conflictos de horarios tanto para doctores como para pacientes, evitando que se sobreprogramen citas.

## Características Principales

### 1. Gestión de Citas

- **Crear Cita**: Formulario interactivo con búsqueda de disponibilidad en tiempo real
- **Editar Cita**: Modificar fecha, hora, estado y notas
- **Listar Citas**: Vista con filtros y búsqueda avanzada
- **Cambiar Estado**: Programado, Completado, Cancelado

### 2. Gestión de Disponibilidad de Horarios

- **Configurar Horarios por Doctor**: Definir horarios de trabajo por día de la semana
- **Horarios Flexibles**: Múltiples franjas horarias por día
- **Activar/Desactivar**: Horarios pueden estar activos o inactivos
- **Validación de Disponibilidad**: Sistema automático que verifica conflictos

### 3. Validación de Conflictos de Horarios

- **Validación de Doctor**: Evita que un doctor tenga dos citas en el mismo horario
- **Validación de Paciente**: Evita que un paciente tenga dos citas en el mismo horario
- **Slots Automáticos**: Genera slots de tiempo disponibles (configurable: 15, 30, 45, 60 min)

## Estructura de la Base de Datos

### Tabla: `doctor_schedules`

```sql
- id (PK)
- doctor_id (FK to doctors)
- day_of_week (1-7: Lunes-Domingo)
- start_time (TIME)
- end_time (TIME)
- is_active (BOOLEAN)
- timestamps
```

### Tabla: `appointments`

```sql
- id (PK)
- doctor_id (FK to doctors)
- patient_id (FK to patients)
- appointment_date (DATE)
- start_time (TIME)
- end_time (TIME)
- status (programado|completado|cancelado)
- notes (TEXT)
- timestamps
```

## Rutas Disponibles

### Rutas Web (Admin)

```
GET    /admin/appointments              - Listar citas
GET    /admin/appointments/create       - Crear nueva cita
GET    /admin/appointments/{id}/edit    - Editar cita
DELETE /admin/appointments/{id}         - Cancelar cita (soft delete)
GET    /admin/doctors/{doctor}/schedules - Gestionar horarios del doctor
```

### Rutas API

```
GET /api/appointments/availability?doctor_id=1&date=2026-03-15&slot_duration=30
```

**Parámetros:**

- `doctor_id` (required): ID del doctor
- `date` (required): Fecha en formato YYYY-MM-DD (debe ser futura)
- `slot_duration` (optional): Duración del slot en minutos (15, 30, 45, 60). Default: 30

**Respuesta exitosa:**

```json
{
    "success": true,
    "data": [
        {
            "time": "08:00",
            "display": "08:00 - 08:30"
        },
        {
            "time": "08:30",
            "display": "08:30 - 09:00"
        }
    ],
    "message": "Slots disponibles"
}
```

## Componentes Livewire

### 1. `AppointmentList`

Componente para listar todas las citas con filtros avanzados.

**Propiedades:**

- `search`: Búsqueda por nombre de paciente o doctor
- `filter_doctor`: Filtrar por doctor
- `filter_patient`: Filtrar por paciente
- `filter_status`: Filtrar por estado
- `sortBy`: Campo para ordenar
- `sortDir`: Dirección de ordenamiento (asc/desc)

**Métodos:**

- `updatingSearch()`: Actualiza búsqueda
- `sort($column)`: Ordena por columna
- `deleteAppointment($id)`: Cancela una cita

### 2. `CreateAppointment`

Componente para crear una nueva cita.

**Características:**

- Selección de doctor y paciente
- Selector de fecha futura
- Carga dinámica de slots disponibles
- Validación de horarios
- Notas opcionales

**Validaciones:**

- Doctor y paciente son obligatorios
- Fecha debe ser en el futuro
- Hora de fin debe ser posterior a hora de inicio
- No debe haber conflictos de horarios

### 3. `EditAppointment`

Componente para editar una cita existente.

**Características:**

- Editar todos los campos
- Cambiar estado
- Recalcular slots disponibles si cambia doctor/fecha

### 4. `ManageDoctorSchedules`

Componente para gestionar horarios de un doctor.

**Características:**

- Crear nuevos horarios
- Editar horarios existentes
- Activar/Desactivar horarios
- Eliminar horarios

## Modelos

### Appointment

```php
// Relaciones
public function doctor()
public function patient()

// Scopes
scopeByDoctor($doctorId)
scopeByPatient($patientId)
scopeInDateRange($startDate, $endDate)
scopeActive() // Excluye canceladas
```

### DoctorSchedule

```php
// Métodos estáticos
static function getAvailableSlotsForDoctor($doctorId, $date, $slotDuration = 30)
static function getDaysOfWeek()
```

## Seeders

### DoctorScheduleSeeder

Crea horarios de ejemplo para todos los doctores:

- Lunes a Viernes: 8:00-12:00 y 14:00-18:00
- Sábado: 9:00-13:00

### AppointmentSeeder

Crea 10 citas de ejemplo en fechas futuras.

## Cómo Usar

### 1. Ejecutar migraciones

```bash
php artisan migrate
```

### 2. Ejecutar seeders (opcional)

```bash
php artisan db:seed --class=DoctorScheduleSeeder
php artisan db:seed --class=AppointmentSeeder
```

### 3. Acceder a la interfaz

1. Ir a `/admin/appointments` para ver todas las citas
2. Hacer clic en "Nueva Cita" para crear una
3. En doctores, hacer clic en el ícono de calendario para gestionar horarios

### 4. Usar la API

```bash
curl "http://localhost:8000/api/appointments/availability?doctor_id=1&date=2026-03-15&slot_duration=30"
```

## Validaciones Implementadas

### Conflictos de Horarios

El sistema utiliza consultas SQL con condiciones de tiempo para detectar conflictos:

```sql
WHERE doctor_id = ?
  AND appointment_date = ?
  AND TIME(start_time) < ?
  AND TIME(end_time) > ?
  AND status != 'cancelado'
```

Esta lógica asegura que:

- Si la hora de inicio de la nueva cita es antes del fin de la existente
- Y la hora de fin de la nueva cita es después del inicio de la existente
- Entonces hay un conflicto

### Validaciones de Inputs

- Todas las fechas deben ser en el futuro
- Horas deben estar en formato HH:mm
- La hora de fin siempre debe ser posterior a la de inicio
- Los IDs deben existir en la base de datos

## Notas Técnicas

### Resolución de Conflictos

El componente `DoctorSchedule::getAvailableSlotsForDoctor()` resuelve conflictos mediante:

1. Obtiene los horarios del doctor para el día seleccionado
2. Itera sobre cada horario generando slots
3. Para cada slot, verifica si existe una cita (no cancelada)
4. Si no hay cita en ese slot, lo incluye en la lista disponible

### Cálculo de Slots

La duración del slot es configurable (default: 30 min):

- Slot 1: 08:00 - 08:30
- Slot 2: 08:30 - 09:00
- Slot 3: 09:00 - 09:30
- etc...

## Próximas Mejoras (Sugerencias)

1. **Notificaciones**: Enviar emails a doctores y pacientes sobre citas
2. **Recordatorios**: Sistema de recordatorios automáticos
3. **Cancelaciones**: Permitir que pacientes cancelen sus propias citas
4. **Historial**: Auditoría de cambios en citas
5. **Reportes**: Generar reportes de utilización de doctores
6. **Calendarios**: Vista de calendario visual
7. **SMS**: Notificaciones por SMS
8. **Multi-idioma**: Soporte para múltiples idiomas
