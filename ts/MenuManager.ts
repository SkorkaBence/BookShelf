class MenuManager {

    public static OpenMenu() : void {
        $(".sidepanel").classList.add("visible");
    }

    public static CloseMenu() : void {
        $(".sidepanel").classList.remove("visible");
    }

}