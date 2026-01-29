# Layout Structure

## Architecture

```
layouts/
├── demo1/
│   └── base.blade.php          # Layout principal pour l'app authentifiée
├── guest.blade.php             # Layout pour auth (login, register, etc.)
└── partials/
    ├── head.blade.php          # Meta tags, Vite imports, Livewire styles
    └── scripts.blade.php        # Scripts externes, Livewire scripts
```

## Utilisation

### Layout Authentifié (Demo1)
```blade
@extends('layouts.demo1.base')

@section('content')
    <!-- Ton contenu ici -->
@endsection
```

### Layout Invité (Guest/Auth)
```blade
<x-guest-layout>
    <!-- Ton contenu ici -->
</x-guest-layout>
```

## Règles Importantes

### ✅ À FAIRE
- Utiliser `{{ }}` pour les variables
- Charger les données via Controller/Livewire
- Utiliser Livewire Components pour la réactivité
- Importer Alpine via `resources/js/app.js`

### ❌ À NE PAS FAIRE
- `{{ \App\Models\User::count() }}` (logique dans vue)
- Charger Alpine via CDN
- `@livewireStyles` / `@livewireScripts` en dehors des layouts

## Assets Management

Tous les assets sont gérés par Vite:
- CSS: `resources/css/app.css`
- JS: `resources/js/app.js`
- Chargé une seule fois dans `head.blade.php`

Assets externes (Metronic) sont chargés dans `scripts.blade.php` avec `data-navigate-once` pour Livewire compatibility.
