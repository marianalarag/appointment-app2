# Quick Reference - Sistema de Citas Médicas

## ✅ Status - Todo Funcionando

Las tablas están creadas y pobladas con datos de ejemplo:

- `doctor_schedules`: Horarios de disponibilidad de doctores
- `appointments`: Registro de todas las citas

## 📍 Rutas Web (Admin)

```
GET    /admin/appointments              → Listar todas las citas
GET    /admin/appointments/create       → Formulario crear cita
GET    /admin/appointments/{id}/edit    → Formulario editar cita
DELETE /admin/appointments/{id}         → Cancelar cita
GET    /admin/doctors/{doctor}/schedules → Gestionar horarios del doctor
```

## 🔌 API REST

**URL Base:** `GET /api/appointments/availability`

### Parámetros

```
doctor_id       (required) → ID del doctor
date            (required) → Fecha en formato YYYY-MM-DD (debe ser futura)
slot_duration   (optional) → Duración del slot [15|30|45|60] mins. Default: 30
```

### Ejemplo de Uso

```bash
curl "http://localhost:8000/api/appointments/availability?doctor_id=1&date=2026-03-15&slot_duration=30"
```

### Respuesta (200 OK)

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
        },
        {
            "time": "09:00",
            "display": "09:00 - 09:30"
        }
    ],
    "message": "Slots disponibles"
}
```

## 📊 Datos de Ejemplo Cargados

### Horarios de Doctores

- **Lunes a Viernes:** 8:00-12:00 AM y 2:00-6:00 PM
- **Sábado:** 9:00 AM-1:00 PM
- **Domingo:** Cerrado

### Citas de Ejemplo

- 10 citas generadas en fechas futuras
- Estados: Programado, Completado, Cancelado
- Doctores y pacientes variados

## 🎨 Componentes Principales

### 1. Listado de Citas (`/admin/appointments`)

- Filtros: Doctor, Paciente, Estado, Búsqueda
- Ordenamiento: Por fecha, doctor, paciente
- Paginación: 10 items por página
- Acciones: Editar, Cancelar

### 2. Crear Cita (`/admin/appointments/create`)

- Selección de doctor y paciente
- Selector de fecha (futuras)
- **Carga automática de slots disponibles** ⭐
- Validación de conflictos de horarios
- Duración configurable (default: 30 min)

### 3. Editar Cita (`/admin/appointments/{id}/edit`)

- Cambiar doctor, fecha, hora
- Modificar estado
- Actualizar notas
- Recálculo automático de slots

### 4. Gestionar Horarios (`/admin/doctors/{id}/schedules`)

- Crear/editar/eliminar horarios
- Activar/desactivar horarios
- Vista de todos los horarios del doctor
- Validación de duplicados

## 🔍 Características de Validación

✅ **Detección de Conflictos**

- Previene que un doctor tenga 2+ citas en el mismo horario
- Previene que un paciente tenga 2+ citas simultáneamente
- Excluye citas canceladas de validación

✅ **Validaciones de Datos**

- Fechas solo futuras
- Hora de fin posterior a hora de inicio
- IDs válidos en base de datos
- Duración de slot 15-120 minutos

## 🚀 Próximas Acciones Sugeridas

1. **Personalizar componentes UI** - Ajustar colores/estilos según tema del proyecto
2. **Notificaciones** - Agregar toasts/alerts para confirmaciones
3. **Exportar citas** - Generar reportes PDF/Excel
4. **Recordatorios** - Enviar emails a doctores y pacientes
5. **Cancelación cliente** - Permitir que pacientes cancelen sus citas
6. **Múltiples especialidades** - Filtrar doctores por especialidad

## 📝 Base de Datos

### Tabla: doctor_schedules

```
id              bigint (PK)
doctor_id       bigint (FK)
day_of_week     int (1=Mon, 7=Sun)
start_time      time
end_time        time
is_active       boolean
created_at      timestamp
updated_at      timestamp
```

### Tabla: appointments

```
id              bigint (PK)
doctor_id       bigint (FK)
patient_id      bigint (FK)
appointment_date date
start_time      time
end_time        time
status          string (programado|completado|cancelado)
notes           text
created_at      timestamp
updated_at      timestamp
```

## 🔧 Comandos Útiles

```bash
# Ver estado de migraciones
php artisan migrate:status

# Crear nuevas citas (seeder)
php artisan db:seed --class=AppointmentSeeder

# Crear horarios de doctores (seeder)
php artisan db:seed --class=DoctorScheduleSeeder

# Abrir consola interactiva
php artisan tinker

# Limpiar todo y recomenzar
php artisan migrate:refresh
```

## 🎯 Flujo Típico de Uso

1. **Admin accede a** `/admin/appointments`
2. **Click en "Nueva Cita"**
3. **Selecciona Doctor → se cargan especialidades**
4. **Selecciona Paciente**
5. **Selecciona Fecha → se cargan slots automáticamente**
6. **Selecciona slot disponible → hora de fin se calcula**
7. **Opcionalmente agrega notas**
8. **Click "Crear Cita"**
9. ✅ Sistema valida conflictos y crea la cita

## 📱 Modo Responsivo

Todos los componentes son **fully responsive**:

- Mobile: Diseño optimizado para pantallas pequeñas
- Tablet: Distribución flexible
- Desktop: Vistas completas

---

**Última actualización:** 10/03/2026
**Estado:** ✅ Totalmente Funcional
