@if ($crud->buttons()->where('stack', $stack)->count())
	@foreach ($crud->buttons()->where('stack', $stack) as $button)
	  <div class="p-1"> {!! $button->getHtml($entry ?? null) !!} </div>
	@endforeach
@endif
