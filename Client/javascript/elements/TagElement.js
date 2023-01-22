"use strict";

class TagElement {

    /** @param {Tag} tag */
    constructor(tag) {
        this.tag = tag;
    }

    prepareTagSpan() {
        const span = document.createElement("span");
        const nameSpan = document.createElement("span");
        const descriptor = document.createElement("span");

        nameSpan.innerText = this.tag.tag;
        nameSpan.classList.add("bold");
        nameSpan.addEventListener("click", () => {
            localStorage.setItem("chosen-tag", this.tag.tag);
        });

        descriptor.innerText = ` - ${this.tag.descriptor}  `;
        span.classList.add("tag");

        span.append(nameSpan, descriptor);

        return span;
    }

    /**
     * @param {HTMLElement} element
     * */
    render(element) {
        element.appendChild(this.prepareTagSpan());
    }
}
