<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DoctorSchedule;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManageDoctorSchedules extends Component
{
    public int $doctorId;

    /** @var array<int, array<string, bool>> $grid[day_of_week][HHMM] = bool */
    public array $grid = [];

    private const HOUR_START   = 7;
    private const HOUR_END     = 21;  // exclusive — last slot starts at 20:45
    private const SLOT_MINUTES = 15;

    // ──────────────────────────────────────────
    // Boot
    // ──────────────────────────────────────────

    public function mount(int $doctorId): void
    {
        $this->doctorId = $doctorId;
        $this->initGrid();
        $this->loadGrid();
    }

    // ──────────────────────────────────────────
    // Grid helpers
    // ──────────────────────────────────────────

    /** All 15-min slot start times as "HHMM" keys */
    public function getTimeSlotKeys(): array
    {
        $keys    = [];
        $current = Carbon::createFromTime(self::HOUR_START, 0);
        $end     = Carbon::createFromTime(self::HOUR_END, 0);

        while ($current < $end) {
            $keys[] = $current->format('Hi'); // e.g. "0800"
            $current->addMinutes(self::SLOT_MINUTES);
        }

        return $keys;
    }

    /** Keys for the 4 slots within a given zero-padded hour (e.g. "08") */
    public function getSlotsForHour(string $hour): array
    {
        return [
            $hour . '00',
            $hour . '15',
            $hour . '30',
            $hour . '45',
        ];
    }

    /** Zero-padded hours array: ["07","08",…,"20"] */
    public function getHours(): array
    {
        $hours = [];
        for ($h = self::HOUR_START; $h < self::HOUR_END; $h++) {
            $hours[] = str_pad($h, 2, '0', STR_PAD_LEFT);
        }
        return $hours;
    }

    /** Human-readable label "HH:MM – HH:MM" for a HHMM key */
    public function slotLabel(string $key): string
    {
        $start = substr($key, 0, 2) . ':' . substr($key, 2, 2);
        $end   = Carbon::parse($start)->addMinutes(self::SLOT_MINUTES)->format('H:i');
        return "{$start} - {$end}";
    }

    private function keyToTime(string $key): string
    {
        return substr($key, 0, 2) . ':' . substr($key, 2, 2);
    }

    private function initGrid(): void
    {
        foreach (array_keys(DoctorSchedule::getDaysOfWeek()) as $day) {
            foreach ($this->getTimeSlotKeys() as $key) {
                $this->grid[$day][$key] = false;
            }
        }
    }

    private function loadGrid(): void
    {
        $schedules = DoctorSchedule::where('doctor_id', $this->doctorId)->get();

        foreach ($schedules as $schedule) {
            $current = Carbon::parse($schedule->start_time);
            $end     = Carbon::parse($schedule->end_time);

            while ($current < $end) {
                $key = $current->format('Hi');
                if (isset($this->grid[$schedule->day_of_week][$key])) {
                    $this->grid[$schedule->day_of_week][$key] = (bool) $schedule->is_active;
                }
                $current->addMinutes(self::SLOT_MINUTES);
            }
        }
    }

    // ──────────────────────────────────────────
    // Toggle actions
    // ──────────────────────────────────────────

    /** Toggle all 4 slots for a specific day + hour */
    public function toggleAllForHourDay(int $day, string $hour): void
    {
        $slots      = $this->getSlotsForHour($hour);
        $allChecked = collect($slots)->every(fn ($s) => $this->grid[$day][$s] ?? false);

        foreach ($slots as $slot) {
            $this->grid[$day][$slot] = ! $allChecked;
        }
    }

    /** Toggle all slots for a given hour across every day */
    public function toggleAllForHour(string $hour): void
    {
        $days       = array_keys(DoctorSchedule::getDaysOfWeek());
        $slots      = $this->getSlotsForHour($hour);
        $allChecked = collect($days)->every(
            fn ($d) => collect($slots)->every(fn ($s) => $this->grid[$d][$s] ?? false)
        );

        foreach ($days as $day) {
            foreach ($slots as $slot) {
                $this->grid[$day][$slot] = ! $allChecked;
            }
        }
    }

    // ──────────────────────────────────────────
    // Save
    // ──────────────────────────────────────────

    public function saveSchedule(): void
    {
        DB::transaction(function () {
            DoctorSchedule::where('doctor_id', $this->doctorId)->delete();

            foreach ($this->grid as $day => $slots) {
                foreach ($slots as $key => $checked) {
                    if ($checked) {
                        $start = $this->keyToTime($key);
                        $end   = Carbon::parse($start)
                            ->addMinutes(self::SLOT_MINUTES)
                            ->format('H:i');

                        DoctorSchedule::create([
                            'doctor_id'   => $this->doctorId,
                            'day_of_week' => $day,
                            'start_time'  => $start,
                            'end_time'    => $end,
                            'is_active'   => true,
                        ]);
                    }
                }
            }
        });

        $this->dispatch('notification', [
            'type'    => 'success',
            'message' => 'Horario guardado exitosamente.',
        ]);
    }

    // ──────────────────────────────────────────
    // Render
    // ──────────────────────────────────────────

    public function render()
    {
        $hours = $this->getHours();
        $days  = DoctorSchedule::getDaysOfWeek();

        // Pre-compute "Todos" checked state for each [day][hour] and [all][hour]
        $todosChecked = [];
        foreach ($hours as $hour) {
            $slots = $this->getSlotsForHour($hour);

            $todosChecked['all'][$hour] = collect(array_keys($days))->every(
                fn ($d) => collect($slots)->every(fn ($s) => $this->grid[$d][$s] ?? false)
            );

            foreach (array_keys($days) as $day) {
                $todosChecked[$day][$hour] = collect($slots)->every(
                    fn ($s) => $this->grid[$day][$s] ?? false
                );
            }
        }

        return view('livewire.manage-doctor-schedules', [
            'hours'        => $hours,
            'days'         => $days,
            'todosChecked' => $todosChecked,
        ]);
    }
}
