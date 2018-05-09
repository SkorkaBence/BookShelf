function GetLoadingElement() : HTMLElement {
    const cnt = document.createElement("div");
    cnt.className = 'loading-cnt';

    for (let i = 0; i < 3; ++i) {
        const bar = document.createElement("div");
        bar.className = 'bar';
        cnt.appendChild(bar);
    }

    return cnt;
}

function GenerateErrorBox(msg : string) : HTMLElement {
    const cnt = document.createElement("div");
    cnt.className = 'errorbox';
    cnt.innerText = msg;
    return cnt;
}