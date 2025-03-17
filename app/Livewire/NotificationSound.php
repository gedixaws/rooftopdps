<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

class NotificationSound extends Component
{

    public $unreadCount = 0;

    protected $listeners = ['refreshNotifications' => 'checkNotifications'];

    public function checkNotifications()
    {
        $this->unreadCount = DatabaseNotification::where('notifiable_id', auth()->user())
            ->whereNull('read_at')
            ->count();

        if ($this->unreadCount > 0) {
            $this->dispatchBrowserEvent('play-notification-sound');
        }
    }
    public function render()
    {
        return view('livewire.notification-sound');
    }
}
