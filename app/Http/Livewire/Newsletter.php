<?php

namespace App\Http\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;
use Spatie\Newsletter\Facades\Newsletter as SpatieNewsletter;

class Newsletter extends Component
{
    public $email;

    public function render()
    {
        return view('livewire.newsletter');
    }

    public function submit()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        SpatieNewsletter::subscribe($this->email);

        Notification::make()
            ->title('You have been subscribed to our newsletter.')
            ->success()
            ->duration(5000)
            ->send();

        $this->reset('email');
    }
}
