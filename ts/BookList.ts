interface BookListResponse {
    items : BookData[];
    has_next_page : boolean;
    has_previous_page : boolean;
    page_count : number;
}

interface BookData {
    id : string;
    author : string;
    title : string;
    pages : number;
    category : string;
    isbn : string;
    hasread : boolean;
}

class BookList {

    private static Table : HTMLElement|null = null;
    private static CurrentPage : number = 0;
    private static SearchTimer : number = -1;

    public static Init(page : number) : void {
        const __this = this;

        this.Table = $("#booktable");
        this.CurrentPage = page;
        this.LoadPage();

        $("#search_input").addEventListener("change", function() {
            __this.LoadPage();
        });
        $("#search_input").addEventListener("keyup", function() {
            if (__this.SearchTimer > 0) {
                clearTimeout(__this.SearchTimer);
            }
            __this.SearchTimer = setTimeout(function() {
                __this.LoadPage();
            }, 500);
        });
        $("#prevpage").addEventListener("click", function() {
            --__this.CurrentPage;
            __this.LoadPage();
        });
        $("#nextpage").addEventListener("click", function() {
            ++__this.CurrentPage;
            __this.LoadPage();
        });
    }

    private static UpdateUrl() : void {
        const query = ($("#search_input") as HTMLInputElement).value;
        let url = '?page=' + encodeURIComponent(this.CurrentPage.toString());
        if (query != "") {
            url += "&q=" + encodeURIComponent(query);
        }
        history.replaceState(null, document.title, url);
    }

    private static LoadPage(noload? : boolean) {
        if (!this.Table) {
            throw new Error("Must be initialized first");
        }

        const __this = this;

        if (typeof(noload) != "boolean" || noload !== true) {
            this.Table.innerHTML = '';
            this.Table.appendChild(GetLoadingElement());
            $("#prevpage").style.display = "none";
            $("#nextpage").style.display = "none";
        }

        const query = ($("#search_input") as HTMLInputElement).value;
        this.UpdateUrl();

        fetch("api/books/list?page=" + encodeURIComponent(this.CurrentPage.toString()) + "&q=" + encodeURIComponent(query), {
            credentials: "same-origin"
        }).then(function(response) {
            return response.json();
        }).then(function(bookdata : BookListResponse) {
            __this.GenerateBookList((__this.Table as HTMLElement), bookdata.items);
            if (bookdata.has_previous_page) {
                $("#prevpage").style.display = "";
            }
            if (bookdata.has_next_page) {
                $("#nextpage").style.display = "";
            }
        }).catch(function(e) {
            (__this.Table as HTMLElement).innerText = "Hiba a lekérdezés során";
            console.warn(e);
        });
    }

    private static GenerateBookList(cnt : HTMLElement, state : BookData[]) : void {
        const __this = this;
        cnt.innerHTML = '';

        if (state.length > 0) {
            state.forEach(function(book) {
                const box = document.createElement("div");
                box.className = 'booklistitem';

                const title = document.createElement("span");
                title.className = 'title';
                title.innerText = book.title;
                box.appendChild(title);

                const author = document.createElement("span");
                author.className = 'author';
                author.innerText = book.author;
                box.appendChild(author);

                const pagecount = document.createElement("span");
                pagecount.className = 'pagecount';
                if (book.pages > 0) {
                    pagecount.innerText = book.pages + ' oldal';
                }
                box.appendChild(pagecount);

                const buttons = document.createElement("span");
                buttons.className = 'buttons';

                const editbtn = document.createElement("button");
                editbtn.className = 'iconbtn material-icons';
                editbtn.innerText = 'mode_edit';
                buttons.appendChild(editbtn);

                const deletebtn = document.createElement("button");
                deletebtn.className = 'iconbtn material-icons';
                deletebtn.innerText = 'delete';
                deletebtn.addEventListener("click", function() {
                    __this.DeleteBook(book.id);
                });
                buttons.appendChild(deletebtn);

                box.appendChild(buttons);

                const hasread = document.createElement("span");
                hasread.className = 'hasread';
                if (book.hasread) {
                    hasread.classList.add('tick');
                }
                hasread.addEventListener("click", function() {
                    hasread.classList.remove('tick');
                    hasread.classList.add('load');
                    __this.ToggleReadStatus(book.id);
                });
                box.appendChild(hasread);

                cnt.appendChild(box);
            });
        } else {
            this.CurrentPage = 1;
            cnt.innerHTML = 'Nincs még könyv a listádban';
            this.UpdateUrl();
        }
    }

    private static ToggleReadStatus(id : string) : void {
        const __this = this;
        fetch("api/books/toggleread?id=" + encodeURIComponent(id), {
            credentials: "same-origin"
        }).then(function(response) {
            return response.text();
        }).then(function() {
            __this.LoadPage(true);
        });
    }

    private static DeleteBook(id : string) : void {
        const __this = this;
        fetch("api/books/delete?id=" + encodeURIComponent(id), {
            credentials: "same-origin"
        }).then(function(response) {
            return response.text();
        }).then(function() {
            __this.LoadPage();
        });
    }

}