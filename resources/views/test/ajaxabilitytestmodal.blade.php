@if(isset($abilitytests))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">Frage {{ $abilitytests->currentPage() }}/{{ $abilitytests->lastPage() }}</h4>
    </div>
    <div class="modal-body">
    @foreach($abilitytests as $abilitytest)
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">{!! $abilitytest->question_de !!}?</div>
            <input type="hidden" class="paginationabilityquestionid" value="{!! $abilitytest->id !!}">
        </div>
    @endforeach
    @if ($abilitytests->lastPage() > 1 && $abilitytests->count() != 0)
        <ul class="pager">
            <li class="{{ ($abilitytests->currentPage() == $abilitytests->lastPage()) ? ' disabled' : '' }}">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <label>
                        <a href="{{ $abilitytests->url($abilitytests->currentPage()+1) }}" name="paginationabilityanswer" id="0" class="paginationabilityanswer btn btn-default btn-sm">Nicht zutreffend</a>
                    </label>

                    <label>
                        <a href="{{ $abilitytests->url($abilitytests->currentPage()+1) }}" name="paginationabilityanswer" id="1" class="paginationabilityanswer btn btn-default btn-sm">Zutreffend</a>
                    </label>

                    <label>
                        <a href="{{ $abilitytests->url($abilitytests->currentPage()+1) }}" name="paginationabilityanswer" id="3" class="paginationabilityanswer btn btn-default btn-sm">Besonders zutreffend</a>
                    </label>
                </div>
            </li>
        </ul>
    @endif
    @if ($abilitytests->count() == 0)
        <div class="alert alert-success" role="alert"><strong>Vielen Dank!</strong> Dein Test wurde erfolgreich abgeschlossen.</div>
    @endif
    </div>
@endif