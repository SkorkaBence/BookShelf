class AccountManager {

    public static Init() {
        const __this = this;

        $("#account_settings_form").addEventListener("submit", function(event) {
            event.preventDefault();
            __this.OnAccountEditSubmit();
        });

        $("#account_password_form").addEventListener("submit", function(event) {
            event.preventDefault();
            __this.OnPasswordSubmit();
        });
    }

    private static async OnAccountEditSubmit() {
        const loadbox = $("#account_loading");
        loadbox.innerHTML = '';
        loadbox.appendChild(GetLoadingElement());

        const response = await fetch("api/account/edit", {
            method: "PATCH",
            credentials: "same-origin",
            body: JSON.stringify({
                name: ($("#name") as HTMLInputElement).value,
                email: ($("#email") as HTMLInputElement).value,
            }),
            headers: {
                "Content-type": "application/json"
            }
        });

        if (response.ok) {
            loadbox.innerHTML = 'Mentve!';
        } else {
            const data = await response.json();
            loadbox.innerHTML = '';
            loadbox.appendChild(GenerateErrorBox(data.error));
        }
    }

    private static async OnPasswordSubmit() {
        const loadbox = $("#password_loading");
        loadbox.innerHTML = '';
        loadbox.appendChild(GetLoadingElement());

        const response = await fetch("api/account/edit", {
            method: "PATCH",
            credentials: "same-origin",
            body: JSON.stringify({
                current_password: ($("#current_password") as HTMLInputElement).value,
                new_password1: ($("#new_password1") as HTMLInputElement).value,
                new_password2: ($("#new_password2") as HTMLInputElement).value,
            }),
            headers: {
                "Content-type": "application/json"
            }
        });

        if (response.ok) {
            loadbox.innerHTML = 'A jelszó megváltozott!';
        } else {
            const data = await response.json();
            loadbox.innerHTML = '';
            loadbox.appendChild(GenerateErrorBox(data.error));
        }
    }

}
