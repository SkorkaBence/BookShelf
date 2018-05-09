enum Gesture {
    MenuOpen,
    MenuClose
}

interface RegisteredGestureEvent {
    gesture : Gesture;
    callback : Function;
}

class GestureManager {

    private static isGesturing = false;
    private static touchStartX = 0;
    private static touchStartY = 0;
    private static touchEndX = 0;
    private static touchEndY = 0;

    private static events : RegisteredGestureEvent[] = [];

    public static Init() {
        const __this = this;

        document.addEventListener("touchstart", function(event : any) {
            __this.TouchStart(event);
        });
        document.addEventListener("touchend", function(event : any) {
            __this.TouchEnd(event);
        });
    }

    private static TouchStart(event : TouchEvent) : void {
        console.log("TouchStart", event.touches.length);
        if (event.touches.length == 1) {
            this.touchStartX = event.touches[0].pageX;
            this.touchStartY = event.touches[0].pageY;
            this.isGesturing = true;
        } else {
            this.isGesturing = false;
        }
    }

    private static TouchEnd(event : TouchEvent) : void {
        console.log("TouchEnd", event.touches.length);
        if (this.isGesturing) {
            this.isGesturing = false;
            this.touchEndX = event.changedTouches[0].pageX;
            this.touchEndY = event.changedTouches[0].pageY;
            this.HandleGesture();
        }
    }

    private static HandleGesture() : void {
        console.log("X:", this.touchStartX, "->", this.touchEndX);
        console.log("Y:", this.touchStartY, "->", this.touchEndY);

        if (this.touchStartX < 30 && this.touchEndX > 100) {
            this.RunGestureCallbacks(Gesture.MenuOpen);
        }
        if (this.touchStartX > 100 && this.touchEndX < 60) {
            this.RunGestureCallbacks(Gesture.MenuClose);
        }
    }

    private static RunGestureCallbacks(type : Gesture) : void {
        console.log("Running gesture: " + Gesture[type]);
        let eventcount = 0;

        this.events.forEach(function(event) {
            if (event.gesture == type) {
                ++eventcount;
                try {
                    event.callback();
                } catch (e) {
                    console.warn(e);
                }
            }
        });

        console.log("Ran " + eventcount + " callbacks");
    }

    public static RegisterEvent(gesture : Gesture, callback : Function) {
        this.events.push({
            gesture: gesture,
            callback: callback
        });
    }

}

window.addEventListener("load", function() {
    GestureManager.Init();
});