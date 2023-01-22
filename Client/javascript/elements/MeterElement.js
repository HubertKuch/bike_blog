"use strict";

class MeterElement {

    /**
     * @param {Meter} data
     * */
    constructor(data = {}) {
        this.meter = data;
    }

    getContainer() {
        const container = document.createElement("div");

        container.classList.add("meter");

        return container;
    }

    getMaxSpeed() {
        const input = ElementsUtils.getInput("text");
        input.name = "max-speed";

        input.value = this.meter.maxSpeed ?? "";

        return ElementsUtils.appendInLabel(input, "Maksymalna predkosc");
    }

    getTripLength() {
        const input = ElementsUtils.getInput("text");
        input.name = "trip-length";

        input.value = this.meter.tripLength ?? "";

        return ElementsUtils.appendInLabel(input, "Dlugosc trasy");
    }

    getTime() {
        const input = ElementsUtils.getInput("text");
        input.name = "time";

        input.value = this.meter.time ?? "";

        return ElementsUtils.appendInLabel(input, "Czas");
    }

    getEndState() {
        const input = ElementsUtils.getInput("text");
        input.name = "meter-end-state";

        input.value = this.meter.meterEndState ?? "";


        return ElementsUtils.appendInLabel(input, "Licznik koncowy");
    }

    getStartState() {
        const input = ElementsUtils.getInput("text");
        input.name = "meter-start-state";
        input.value = this.meter.meterStartState ?? "";

        return ElementsUtils.appendInLabel(input, "Licznik poczatkowy");
    }

    render(element) {
        const container = this.getContainer();
        const inputsContainer = document.createElement("div");

        inputsContainer.append(this.getMaxSpeed(), this.getTime(), this.getStartState(), this.getEndState(), this.getTripLength());

        const removeButton = ElementsUtils.getRemoveButton();

        removeButton.addEventListener("click", () => {
            removeButton.parentElement.remove();
        });

        container.append(inputsContainer, removeButton)

        element.append(container, )
    }
}
