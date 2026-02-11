<?php

namespace App\Livewire\Modals;

use Livewire\Component;

class DeleteRecord extends Component
{
    public $recordId;
    public $modelClass;

    #[\Livewire\Attributes\On('confirmDelete')]
    public function confirmDelete($id, $model)
    {
        $this->recordId  = $id;
        $this->modelClass = $model;

        $this->dispatch('open-delete-modal');
    }

    public function delete()
    {
        if ($this->recordId && $this->modelClass) {

            $model = $this->modelClass;

            $model::find($this->recordId)?->delete();
            

            // Tell table to remove row
            $this->dispatch('record-deleted', [
                'id' => $this->recordId,
                'model' => $this->modelClass,
            ]);
        }

        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        return view('livewire.modals.delete-record');
    }
}
