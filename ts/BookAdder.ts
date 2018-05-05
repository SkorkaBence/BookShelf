interface BookSearchItem {
    title : string;
    author : string;
    pages : number;
    category : string;
    image : string;
}

class BookAdder {

    private static SearchResultCache : BookSearchItem[] = [];
    private static SearchTimer : number = -1;
    private static LastSearchQuery : string = "";

    public static Init() {
        const __this = this;

        $("#search_input").addEventListener("change", function() {
            __this.Search(($("#search_input") as HTMLInputElement).value);
        });
        $("#search_input").addEventListener("keyup", function() {
            if (__this.SearchTimer > 0) {
                clearTimeout(__this.SearchTimer);
            }
            __this.SearchTimer = setTimeout(function() {
                __this.Search(($("#search_input") as HTMLInputElement).value);
            }, 500);
        });
    }

    public static Search(term : string) : void {
        const __this = this;

        if (term == this.LastSearchQuery) {
            return;
        }
        this.LastSearchQuery = term;

        const searchresults = $("#searchresults");

        searchresults.innerHTML = '';
        searchresults.appendChild(GetLoadingElement());

        fetch("api/books/search?q=" + encodeURIComponent(term), {
            credentials: "same-origin"
        }).then(function(response) {
            return response.json();
        }).then(function(bookdata) {
            __this.SearchResultCache = bookdata;
            __this.GenerateSearchResults(searchresults, bookdata);
        }).catch(function() {
            searchresults.innerText = "Hiba a keresés során";
        });
    }

    public static GenerateSearchResults(cnt : HTMLElement, state : BookSearchItem[]) : void {
        cnt.innerHTML = '';
        state.forEach(function(book) {
            const bookcnt = document.createElement("div");
            bookcnt.className = 'searchbook';
            bookcnt.style.backgroundImage = `url('${book.image}')`;
            bookcnt.addEventListener("click", function() {
                ($("#author") as HTMLInputElement).value = book.author;
                ($("#title") as HTMLInputElement).value = book.title;
                ($("#pages") as HTMLInputElement).value = book.pages.toString();
                ($("#category") as HTMLInputElement).value = book.category;
                ($("#isbn") as HTMLInputElement).value = '';
                ($("#hasread") as HTMLInputElement).checked = false;
            });
            cnt.appendChild(bookcnt);
        });
    }

}