<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Category;

class PreferenceScreen extends Component
{
    public string $typeLoadPage = '';
    public $user;
    public ?array $categories = [];
    public ?array $payload = [];

    public function mount(): void
    {
        $this->typeLoadPage = request('type') ?? '';
        $this->user = auth()->user()
            ?->load('profile', 'preference.skill.category', 'interests')
            ->toArray();
        $skillRemove = [];
        if ($this->typeLoadPage === 'edit') {
            foreach ($this->user['preference'] as $interests) {
                $skillRemove[] = $interests['skill_id'];
            }
        }

        $this->user['typeResource'] = 'preference';
        $this->categories = Category::with([
            'skills' => function ($query) use ($skillRemove) {
                $query->whereNotIn('id', $skillRemove);
            }
        ])->get()->toArray();
    }

    public function render()
    {
        return view('livewire.components.preference-screen');
    }
}
