import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
    }

    searchMovie(event) {
        const url = event.currentTarget.dataset.url;
        const query = event.currentTarget.value;
        const list = $('.movies-list');

        $.ajax({
            url: url,
            method: 'POST',
            data: {query: query},
            success: (movies) => {
                list.html(movies.html);
            },
            error: (xhr, textStatus, errorThrown) => {
                console.error('Erreur lors de la recherche de films :', errorThrown);
            },
        });
    }
}