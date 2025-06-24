<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Category;
use App\Models\Knowledge;

class KnowledgeScreen extends Component
{
    public string $typeLoadPage = '';
    public $user;
    public ?array $categories = [];
    public ?array $payload = [];

    public function save() {
        # 
        try {
            #dd($this->payload);
            $this->insertKnowledgesData();

            return redirect()->route('app.developers');
        } catch (\Exception $exception) {
            //todo: adicionar notificação com erro para o usuário (izitoast)
            dd($exception->getMessage());
        }
    }

    public function insertKnowledgesData() {
        knowledge::query()->updateOrCreate([
            'user_id' => auth()->user()->id,
        ], [
            'data' => json_encode($this->payload)
        ]);
    }

    public function mount(): void
    {
        $this->typeLoadPage = request('type') ?? '';
        $this->user = auth()->user()
            ?->load('profile', 'knowledge.skill.category', 'interests')
            ->toArray();
        $skillRemove = [];
        if ($this->typeLoadPage === 'edit') {
            foreach ($this->user['knowledge'] as $interests) {
                $skillRemove[] = $interests['skill_id'];
            }
        }

        $this->user['typeResource'] = 'knowledge';
        $this->categories = Category::with([
            'skills' => function ($query) use ($skillRemove) {
                $query->whereNotIn('id', $skillRemove);
            }
        ])->get()->toArray();
    }

    public function render()
    {
        return view('livewire.components.knowledge-screen');
    }
}
