<div class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group" style="margin-left: 0.35rem;">
    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="dev" wire:model.live="environment" @if(session('API_used')=="development") checked @endif>
    <label class="btn btn-outline-secondary btn-sm" for="btnradio1">Dev</label>

    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" value="prod" wire:model.live="environment" @if(session('API_used')=="production") checked @endif>
    <label class="btn btn-outline-secondary btn-sm" for="btnradio2">Prod</label>
</div>