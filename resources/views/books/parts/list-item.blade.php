<a href="{{ $book->getUrl() }}" class="book entity-list-item" data-entity-type="book"
    data-entity-id="{{ $book->id }}">
    @if (isset($shelf) && !empty($shelf) && $shelf->name != 'Tin nóng')
        <div class="entity-list-item-image bg-book" style="background-image: url('{{ $book->getBookCover() }}')">
            @icon('book')
        </div>
    @endif
    <div class="content">
        <h4 class="entity-list-item-name break-text">{{ $book->name }}</h4>
        <hr width="100%">
        <div class="entity-item-snippet">
            <p class="text-muted break-text mb-s text-limit-lines-1">{{ $book->description }}</p>
        </div>
    </div>
</a>
