"use strict";

class TagElement {

    constructor(tag) {
        this.tag = tag;
    }

    prepareTagSpan() {
        const span = document.createElement("span");

        span.innerText = `${this.tag} `;
        span.classList.add("tag", "pink");

        return span;
    }

    /**
     * @param {HTMLElement} element
     * */
    render(element) {
        element.appendChild(this.prepareTagSpan());
    }
}
