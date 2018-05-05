function $(e : string) : HTMLElement {
    const d = document.querySelector(e);
    if (d) {
        return (d as HTMLElement);
    }
    throw new Error("Invalid query");
}