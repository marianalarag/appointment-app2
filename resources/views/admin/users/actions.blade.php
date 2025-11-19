<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.users.edit', $model) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    @if($model->id !== 1) {{-- No permitir eliminar al admin principal --}}
    <form action="{{ route('admin.users.destroy', $model) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
    @endif
</div>
