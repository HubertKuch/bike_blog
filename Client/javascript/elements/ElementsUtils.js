"use strict";

class ElementsUtils {
    static getRemoveButton() {
        const button = document.createElement('button');

        button.classList.add("button-without-background");

        const removeIcon = document.createElement("i");

        removeIcon.classList.add("fa-solid", "fa-x");

        button.append(removeIcon);

        return button;
    }

    static getInput(type) {
        const input = document.createElement("input");

        input.type = type;
        input.classList.add("input");

        return input;
    }

    static appendInLabel(element, labelText) {
        const label = document.createElement("label");

        label.innerHTML = `${labelText}<br>`;
        label.append(element, document.createElement("br"));

        return label;
    }
}
