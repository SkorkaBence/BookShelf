class BookManager {

    public static Init(id : string) {
        const __this = this;

        $("#book_edit_form").addEventListener("submit", function(event) {
            event.preventDefault();
            __this.OnBookEditSubmit(id);
        });
    }

    private static async OnBookEditSubmit(id : string) {
        const loadbox = $("#bookedit_loading");
        loadbox.innerHTML = '';
        loadbox.appendChild(GetLoadingElement());

        const response = await fetch("api/books/edit?id=" + encodeURIComponent(id), {
            method: "PATCH",
            credentials: "same-origin",
            body: JSON.stringify({
                author: ($("#author") as HTMLInputElement).value,
                title: ($("#title") as HTMLInputElement).value,
                pages: ($("#pages") as HTMLInputElement).value,
                category: ($("#category") as HTMLInputElement).value,
                isbn: ($("#isbn") as HTMLInputElement).value,
                hasread: ($("#hasread") as HTMLInputElement).checked
            }),
            headers: {
                "Content-type": "application/json"
            }
        });

        if (response.ok) {
            const data = await response.text();
            loadbox.innerHTML = 'Mentve!';
        } else {
            const data = await response.json();
            loadbox.innerHTML = '';
            loadbox.appendChild(GenerateErrorBox(data.error));
        }
    }

}
