<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Category;

class InterestScreen extends Component
{
    public string $typeLoadPage = '';
    public $user;
    public ?array $categories = [];
    public ?array $payload = [];

    public function mount()
    {
          $this->typeLoadPage = request('type') ?? '';
        $this->user = auth()->user()->load('profile')->toArray();
        $skillRemove = [];
        if ($this->typeLoadPage === 'edit') {
            foreach ($this->user['interests'] as $interests) {
                $skillRemove[] = $interests['skill_id'];
            }
        }

        $this->user['typeResource'] = 'interests';
        $this->categories = Category::with([
                'skills' => function ($query) use ($skillRemove) {
                    $query->whereNotIn('id', $skillRemove);
                }
            ])
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
    public function render()
    {
        return view('livewire.components.interest-screen');
    }

    public function save() {
        try {
            $this->insertInterestsData();

            if (userIsDeveloper()) {
                if ($this->typeLoadPage === 'edit') {
                    return redirect()->route('app.profile');
                }
                return redirect()->route('app.knowledge');
            }

            return redirect()->route('app.developers');
        } catch (\Exception $exception) {
            //todo: adicionar notificação com erro para o usuário (izitoast)
            dd($exception->getMessage());
        }
    }
}
