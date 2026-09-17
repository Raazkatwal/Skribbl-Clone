---
paths:
  - '**'
---

# General

## Livewire v4 migration gotchas already applied
Livewire v4 uses `component_layout` => 'components.layouts.app' in config/livewire.php (hole-page pages render into this layout). `wire:model.blur`/`.change` now delay BOTH client-side + network sync; to keep v3 behavior use `wire:model.live.blur` (applied in join-game.blade.php). No `livewire:upgrade` command in v4.4.3 - upgrade is manual. Broadcasts are faked in tests with Event::fake() since PusherBroadcaster has no fake(); there is no .env.testing so reverb connection fails in tests otherwise.
